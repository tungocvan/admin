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

        // console.log("👥 ROOM MEMBERS:", room ? Array.from(room) : []);

        // console.log("📡 EMIT:", event, roomName);

        io.to(roomName).emit(event, data);

        //console.log("✅ EMITTED");
    }

    /**
     * =========================================================
     * HEALTH CHECK
     * =========================================================
     */
    app.get("/socket-status", (req, res) => {
        const rooms = [];

        io.sockets.adapter.rooms.forEach((clients, roomName) => {
            /**
             * Skip private socket rooms
             */
            if (!roomName.startsWith("session-")) {
                return;
            }

            rooms.push({
                room: roomName,
                clients: clients.size,
            });
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
       // console.log('REQ BODY:', req.body);
        try {
            const { event, data = {}, channel = null } = req.body;

            /**
             * Validate 
             */
            if (!event) {
                return res.status(400).json({
                    success: false,
                    error: "Missing event",
                });
            }

            /**
             * Resolve room
             */
            const sessionId = data.session_id || data.chat_session_id || null;

            const roomName =
                channel || (sessionId ? getRoomName(sessionId) : null);

            // console.log("\n==============================");
            // console.log("📨 LARAVEL BROADCAST");
            // console.log("EVENT:", event);
            // console.log("ROOM:", roomName || "GLOBAL");
            // console.log("PAYLOAD:", data);
            // console.log("==============================\n");

            /**
             * Broadcast
             */
            if (roomName) {
                safeEmitToRoom(roomName, event, data);
            } else {
                console.log(`🌍 GLOBAL EMIT => ${event}`);

                io.emit(event, data);
            }

            return res.json({
                success: true,
            });
        } catch (error) {
            console.error("❌ BROADCAST ERROR:", error);

            return res.status(500).json({
                success: false,
                error: error.message,
            });
        }
    });

    /**
     * =========================================================
     * SOCKET CONNECTION
     * =========================================================
     */
    io.on("connection", (socket) => {
        console.log(`✅ CONNECTED: ${socket.id}`);

        /**
         * -----------------------------------------------------
         * JOIN SESSION
         * -----------------------------------------------------
         */
        socket.on("join-session", (sessionId) => {
            try {
                if (!sessionId) {
                    return;
                }

                /**
                 * Leave old room
                 */
                if (socket.data.sessionId) {
                    const oldRoom = getRoomName(socket.data.sessionId);

                    socket.leave(oldRoom);

                    console.log(`🚪 AUTO LEAVE: ${socket.id} <- ${oldRoom}`);
                }

                /**
                 * Join new room
                 */
                const roomName = getRoomName(sessionId);

                socket.join(roomName);

                socket.data.sessionId = sessionId;

                console.log(`🚪 JOINED: ${socket.id} -> ${roomName}`);

                /**
                 * Notify joined
                 */
                socket.emit("session-joined", {
                    session_id: sessionId,
                    room: roomName,
                });
            } catch (error) {
                console.error("❌ JOIN SESSION ERROR:", error);
            }
        });

        /**
         * -----------------------------------------------------
         * LEAVE SESSION
         * -----------------------------------------------------
         */
        socket.on("leave-session", (sessionId) => {
            try {
                if (!sessionId) {
                    return;
                }

                const roomName = getRoomName(sessionId);

                socket.leave(roomName);

                console.log(`🚪 LEFT: ${socket.id} <- ${roomName}`);
            } catch (error) {
                console.error("❌ LEAVE SESSION ERROR:", error);
            }
        });

        /**
         * -----------------------------------------------------
         * TYPING
         * -----------------------------------------------------
         */
        socket.on("typing", (data = {}) => {
            try {
                if (!data.session_id) {
                    return;
                }

                const roomName = getRoomName(data.session_id);

                socket.to(roomName).emit("display-typing", {
                    session_id: data.session_id,
                    sender_id: data.sender_id || null,
                });
            } catch (error) {
                console.error("❌ TYPING ERROR:", error);
            }
        });

        /**
         * -----------------------------------------------------
         * STOP TYPING
         * -----------------------------------------------------
         */
        socket.on("stop-typing", (data = {}) => {
            try {
                if (!data.session_id) {
                    return;
                }

                const roomName = getRoomName(data.session_id);

                socket.to(roomName).emit("hide-typing", {
                    session_id: data.session_id,
                });
            } catch (error) {
                console.error("❌ STOP TYPING ERROR:", error);
            }
        });

        /**
         * -----------------------------------------------------
         * MESSAGE DELIVERED
         * -----------------------------------------------------
         */
        socket.on("message-delivered", (data = {}) => {
            try {
                if (!data.session_id) {
                    return;
                }

                const roomName = getRoomName(data.session_id);

                socket.to(roomName).emit("message-delivered", data);
            } catch (error) {
                console.error("❌ MESSAGE DELIVERED ERROR:", error);
            }
        });

        /**
         * -----------------------------------------------------
         * SOCKET ERROR
         * -----------------------------------------------------
         */
        socket.on("error", (error) => {
            console.error(`❌ SOCKET ERROR (${socket.id}):`, error);
        });

        /**
         * -----------------------------------------------------
         * DISCONNECT
         * -----------------------------------------------------
         */
        socket.on("disconnect", (reason) => {
            console.log(`❌ DISCONNECTED: ${socket.id} | REASON: ${reason}`);
        });
    });
};
