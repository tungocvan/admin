const onlineAdmins = new Map();

module.exports = (io) => {
    io.on("connection", (socket) => {
        console.log("✅ CONNECTED:", socket.id);

        /**
         * =========================================
         * ADMIN ONLINE
         * =========================================
         */
        socket.on("admin-online", (data) => {
            if (!data?.user_id) {
                return;
            }

            onlineAdmins.set(data.user_id, socket.id);

            io.emit("online-admins", [...onlineAdmins.keys()]);

            console.log("🟢 ADMIN ONLINE:", data.user_id);
        });

        /**
         * =========================================
         * JOIN DM ROOM
         * =========================================
         */
        socket.on("join-dm-room", (data) => {
            if (!data?.room) {
                return;
            }

            const room = String(data.room).trim();

            /**
             * leave old room
             */
            if (socket.data.room) {
                socket.leave(socket.data.room);

                console.log(`🚪 LEFT: ${socket.id} <- ${socket.data.room}`);
            }

            /**
             * join new room
             */
            socket.join(room);

            socket.data.room = room;

            console.log(`🚪 JOINED: ${socket.id} -> ${room}`);

            console.log(socket.rooms);
        });

        /**
         * =========================================
         * DISCONNECT
         * =========================================
         */
        socket.on("disconnect", () => {
            for (const [userId, socketId] of onlineAdmins.entries()) {
                if (socketId === socket.id) {
                    onlineAdmins.delete(userId);
                }
            }

            io.emit("online-admins", [...onlineAdmins.keys()]);

            console.log("❌ DISCONNECTED:", socket.id);
        });
    });
};
