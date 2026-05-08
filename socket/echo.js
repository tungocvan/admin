import Echo from "laravel-echo";
import { io } from "socket.io-client";

window.io = io;

const SOCKET_HOST =
    window.CHAT_CONFIG_HOST ||
    `${window.location.hostname}:${window.CHAT_CONFIG_PORT}`;

/**
 * SINGLE SOCKET INSTANCE
 */
const socket = io(SOCKET_HOST, {
    transports: ["websocket", "polling"]
});

window.socket = socket; // 👈 quan trọng

/**
 * Echo init (optional nếu bạn vẫn dùng listener Laravel Echo)
 */
window.Echo = new Echo({
    broadcaster: "socket.io",
    client: io,
    host: SOCKET_HOST,
    transports: ["websocket", "polling"],
});

/**
 * CONNECT LOG
 */
socket.on("connect", () => {
    console.log("✅ SOCKET CONNECTED:", socket.id);
});

/**
 * ERROR LOG
 */
socket.on("connect_error", (err) => {
    console.error("❌ SOCKET ERROR:", err.message);
});

/**
 * JOIN SESSION (FIXED)
 */
window.joinSession = (id) => {
    if (!id) return;

    socket.emit("join-session", id);
};

/**
 * LEAVE SESSION
 */
window.leaveSession = (id) => {
    if (!id) return;

    socket.emit("leave-session", id);
};
