module.exports = (io, bridgeAuth, app) => {
    /**
     * =========================================================
     * HELPERS
     * =========================================================
     */
    const getRoomName = (sessionId) => {
        return `session-${sessionId}`;
    };

    function safeEmitToRoom(roomName, event, data) {
        const room = io.sockets.adapter.rooms.get(roomName);
        const numClients = room ? room.size : 0;

        console.log(`📡 [EMIT] Event: "${event}" | Room: "${roomName}" | Clients in room: ${numClients}`);

        // Gửi cho tất cả mọi người trong phòng (bao gồm cả người gửi nếu họ trong phòng)
        io.to(roomName).emit(event, data);
    }

    /**
     * =========================================================
     * HEALTH CHECK
     * =========================================================
     */
    app.get("/socket-status", (req, res) => {
        const rooms = [];
        io.sockets.adapter.rooms.forEach((clients, roomName) => {
            if (roomName.startsWith("session-")) {
                rooms.push({
                    room: roomName,
                    clients: clients.size,
                });
            }
        });

        res.json({
            success: true,
            total_clients: io.engine.clientsCount,
            total_rooms: rooms.length,
            rooms,
        });
    });

    /**
     * =========================================================
     * INTERNAL BRIDGE (Laravel -> Node)
     * =========================================================
     */
    app.post("/broadcast", bridgeAuth, (req, res) => {
        try {
            const { event, data = {}, channel = null } = req.body;

            if (!event) {
                return res.status(400).json({ success: false, error: "Missing event" });
            }

            // Ưu tiên lấy sessionId từ data để xác định phòng
            const sessionId = data.chat_session_id || data.session_id || null;
            
            // Nếu có channel từ Laravel gửi sang thì dùng, không thì tự build từ sessionId
            const roomName = channel || (sessionId ? getRoomName(sessionId) : null);

            console.log(`\n--- 📥 Laravel Bridge Event: ${event} ---`);
            
            if (roomName) {
                safeEmitToRoom(roomName, event, data);
            } else {
                console.log(`🌍 [GLOBAL EMIT] Event: ${event}`);
                io.emit(event, data);
            }

            return res.json({ success: true });
        } catch (error) {
            console.error("❌ [BROADCAST ERROR]:", error);
            return res.status(500).json({ success: false, error: error.message });
        }
    });

    /**
     * =========================================================
     * SOCKET CONNECTION
     * =========================================================
     */
    io.on("connection", (socket) => {
        console.log(`✅ [CONNECTED] Socket ID: ${socket.id}`);

        /**
         * JOIN SESSION: Đưa user vào phòng riêng của họ
         */
        socket.on("join-session", (sessionId) => {
            try {
                if (!sessionId) return;

                // Thoát khỏi phòng cũ nếu có
                if (socket.data.sessionId && socket.data.sessionId !== sessionId) {
                    const oldRoom = getRoomName(socket.data.sessionId);
                    socket.leave(oldRoom);
                    console.log(`🚪 [LEAVE] ${socket.id} left ${oldRoom}`);
                }

                const roomName = getRoomName(sessionId);
                socket.join(roomName);
                socket.data.sessionId = sessionId; // Lưu lại ID vào socket instance

                console.log(`🚪 [JOINED] ${socket.id} -> ${roomName}`);

                socket.emit("session-joined", {
                    session_id: sessionId,
                    room: roomName,
                });
            } catch (error) {
                console.error("❌ [JOIN ERROR]:", error);
            }
        });

        /**
         * TYPING: Thông báo đang nhập tin nhắn
         */
        socket.on("typing", (data = {}) => {
            try {
                const sessionId = data.session_id || socket.data.sessionId;
                if (!sessionId) return;

                const roomName = getRoomName(sessionId);
                // .broadcast.to(roomName) gửi cho tất cả TRỪ người gửi
                socket.broadcast.to(roomName).emit("display-typing", {
                    session_id: sessionId,
                    sender_id: data.sender_id || null,
                });
            } catch (error) {
                console.error("❌ [TYPING ERROR]:", error);
            }
        });

        /**
         * STOP TYPING: Thông báo dừng nhập
         */
        socket.on("stop-typing", (data = {}) => {
            try {
                const sessionId = data.session_id || socket.data.sessionId;
                if (!sessionId) return;

                const roomName = getRoomName(sessionId);
                socket.broadcast.to(roomName).emit("hide-typing", {
                    session_id: sessionId,
                });
            } catch (error) {
                console.error("❌ [STOP TYPING ERROR]:", error);
            }
        });

        /**
         * MESSAGE DELIVERED
         */
        socket.on("message-delivered", (data = {}) => {
            try {
                const sessionId = data.session_id || socket.data.sessionId;
                if (!sessionId) return;

                const roomName = getRoomName(sessionId);
                socket.broadcast.to(roomName).emit("message-delivered", data);
            } catch (error) {
                console.error("❌ [DELIVERED ERROR]:", error);
            }
        });

        socket.on("disconnect", (reason) => {
            console.log(`❌ [DISCONNECTED] Socket ID: ${socket.id} | Reason: ${reason}`);
        });
    });
};