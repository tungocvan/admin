module.exports = (io, bridgeAuth, app) => {
    /**
     * =========================
     * 1. LARAVEL BRIDGE LAYER
     * =========================
     */
    app.post("/broadcast", bridgeAuth, (req, res) => {
        const { event, data, channel } = req.body;

        if (!event) {
            return res.status(400).json({ error: "Missing event name" });
        }

        const sessionId = data?.session_id;
        const roomName = channel || (sessionId ? `session-${sessionId}` : null);

        console.log(`📡 EVENT: ${event} | ROOM: ${roomName || "global"}`);

        if (roomName) {
            const room = io.sockets.adapter.rooms.get(roomName);

            if (!room) {
                console.warn(
                    `⚠️ Room not found: ${roomName} (no clients joined yet)`,
                );
            }

            io.to(roomName).emit(event, data);
        } else {
            io.emit(event, data);
        }

        res.json({ ok: true });
    });

    /**
     * =========================
     * 2. SOCKET REALTIME LAYER
     * =========================
     */
    io.on("connection", (socket) => {
        console.log(`🔌 Connected: ${socket.id}`);

        /**
         * JOIN CHAT SESSION
         */
        socket.on("join-session", (sessionId) => {
            if (!sessionId) return;

            const roomName = `session-${sessionId}`;
            socket.join(roomName);

            socket.data.sessionId = sessionId;

            console.log(`🚪 Joined: ${socket.id} -> ${roomName}`);
        });

        /**
         * LEAVE SESSION (BEST PRACTICE ADD)
         */
        socket.on("leave-session", (sessionId) => {
            if (!sessionId) return;

            const roomName = `session-${sessionId}`;
            socket.leave(roomName);

            console.log(`🚪 Left: ${socket.id} <- ${roomName}`);
        });

        /**
         * TYPING INDICATOR (FIXED)
         */
        socket.on("typing", (data) => {
            if (!data?.session_id) return;

            const roomName = `session-${data.session_id}`;

            socket.to(roomName).emit("display-typing", {
                session_id: data.session_id,
                user_id: data.user_id || null,
            });
        });

        /**
         * STOP TYPING (NÊN CÓ - bổ sung chuẩn chat app)
         */
        socket.on("stop-typing", (data) => {
            if (!data?.session_id) return;

            const roomName = `session-${data.session_id}`;

            socket.to(roomName).emit("hide-typing", {
                session_id: data.session_id,
            });
        });

        /**
         * DISCONNECT
         */
        socket.on("disconnect", () => {
            console.log(`❌ Disconnected: ${socket.id}`);
        });
    });
};
