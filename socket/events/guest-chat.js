module.exports = (io, bridgeAuth, app) => {

    /**
     * =========================================
     * HELPERS
     * =========================================
     */
    const getRoomName = (sessionId) => {
        return `session-${sessionId}`;
    };

    /**
     * =========================================
     * SAFE EMIT
     * =========================================
     */
    function emitToRoom(
        room,
        event,
        data
    ) {

        io.to(room).emit(
            event,
            data
        );

        console.log(
            `📡 EMIT => ${event} | ROOM: ${room}`
        );
    }

    /**
     * =========================================
     * BRIDGE
     * Laravel -> NodeJS
     * =========================================
     */
    app.post(
        '/guest-broadcast',
        bridgeAuth,
        (req, res) => {

            try {

                const {
                    event,
                    data = {},
                    channel = null,
                } = req.body;

                if (!event) {

                    return res.status(400)
                        .json({
                            success: false,
                            error: 'Missing event'
                        });
                }

                /**
                 * Resolve room
                 */
                const sessionId =
                    data.chat_session_id
                    ||
                    data.session_id
                    ||
                    null;

                const room =
                    channel
                    ||
                    (
                        sessionId
                            ? getRoomName(sessionId)
                            : null
                    );

                /**
                 * Emit
                 */
                if (room) {

                    emitToRoom(
                        room,
                        event,
                        data
                    );

                } else {

                    io.emit(
                        event,
                        data
                    );
                }

                return res.json({
                    success: true,
                });

            } catch (e) {

                console.error(
                    '❌ GUEST BROADCAST ERROR:',
                    e
                );

                return res.status(500)
                    .json({
                        success: false,
                    });
            }
        }
    );

    /**
     * =========================================
     * SOCKET
     * =========================================
     */
    io.on('connection', (socket) => {

        console.log(
            '✅ GUEST CONNECTED:',
            socket.id
        );

        /**
         * =====================================
         * JOIN SESSION
         * =====================================
         */
        socket.on(
            'guest-join-session',
            (sessionId) => {

                if (!sessionId) {
                    return;
                }

                /**
                 * Leave old room
                 */
                if (socket.data.guestRoom) {

                    socket.leave(
                        socket.data.guestRoom
                    );
                }

                /**
                 * Join new room
                 */
                const room =
                    getRoomName(sessionId);

                socket.join(room);

                socket.data.guestRoom =
                    room;

                console.log(
                    `🚪 GUEST JOINED: ${room}`
                );
            }
        );

        /**
         * =====================================
         * TYPING
         * =====================================
         */
        socket.on(
            'guest-typing',
            (data = {}) => {

                if (!data.session_id) {
                    return;
                }

                const room =
                    getRoomName(
                        data.session_id
                    );

                socket.to(room).emit(
                    'guest-display-typing',
                    data
                );
            }
        );

        /**
         * =====================================
         * STOP TYPING
         * =====================================
         */
        socket.on(
            'guest-stop-typing',
            (data = {}) => {

                if (!data.session_id) {
                    return;
                }

                const room =
                    getRoomName(
                        data.session_id
                    );

                socket.to(room).emit(
                    'guest-hide-typing',
                    data
                );
            }
        );

        /**
         * =====================================
         * DISCONNECT
         * =====================================
         */
        socket.on(
            'disconnect',
            () => {

                console.log(
                    '❌ GUEST DISCONNECTED:',
                    socket.id
                );
            }
        );
    });
};