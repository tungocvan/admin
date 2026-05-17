import { io } from "socket.io-client";

/**
 * =========================================
 * CONFIG
 * =========================================
 */
console.log("🔥 ECHO LOADED");
console.trace("ECHO TRACE");
const SOCKET_HOST =
    window.CHAT_CONFIG_HOST ||
    `${window.location.hostname}:${window.CHAT_CONFIG_PORT}`;

console.log("🌐 SOCKET HOST:", SOCKET_HOST);

/**
 * =========================================
 * SINGLETON SOCKET
 * =========================================
 */
if (!window.socket) {
    console.log("🆕 INIT NEW SOCKET...");

    window.socket = io(SOCKET_HOST, {
        transports: ["websocket"], // 🔥 tránh duplicate connect
        reconnection: true,
        reconnectionAttempts: 10,
        reconnectionDelay: 1000,
    });
} else {
    console.log("♻️ REUSE EXISTING SOCKET:", window.socket.id);
}

const socket = window.socket;

/**
 * =========================================
 * GLOBAL STATE
 * =========================================
 */
window.currentSessionId = window.currentSessionId || null;

/**
 * =========================================
 * DEBUG: LISTEN ALL EVENTS
 * =========================================
 */
socket.onAny((event, ...args) => {
    console.log("📡 [SOCKET EVENT]:", event, args);
});

/**
 * =========================================
 * CONNECTION EVENTS
 * =========================================
 */
socket.on("connect", () => {
     console.log("🟢 FRONT SOCKET:", socket.id);

    /**
     * 🔥 AUTO REJOIN ROOM SAU KHI CONNECT / RECONNECT
     */
    if (window.currentSessionId) {
        console.log(
            "🚪 AUTO REJOIN:",
            window.currentSessionId
        );

        socket.emit("join-session", window.currentSessionId);
    }
});

socket.on("disconnect", (reason) => {
    console.warn("❌ DISCONNECTED:", socket.id, "| Reason:", reason);
});

socket.on("connect_error", (err) => {
    console.error("❌ CONNECT ERROR:", err.message);
});

socket.on("reconnect_attempt", (attempt) => {
    console.log("🔄 RECONNECT ATTEMPT:", attempt);
});

socket.on("reconnect", (attempt) => {
    console.log("♻️ RECONNECTED AFTER:", attempt);
});

/**
 * =========================================
 * JOIN / LEAVE SESSION
 * =========================================
 */
window.joinSession = (id) => {
    if (!id) {
        console.warn("⚠️ joinSession: missing id");
        return;
    }

    window.currentSessionId = id;

    if (socket.connected) {
        console.log("🚪 JOIN SESSION:", id);
        socket.emit("join-session", id);
    } else {
        console.warn("⏳ SOCKET NOT CONNECTED → WAIT REJOIN");
    }
};

window.leaveSession = (id) => {
    if (!id) return;

    console.log("🚪 LEAVE SESSION:", id);

    socket.emit("leave-session", id);

    if (window.currentSessionId === id) {
        window.currentSessionId = null;
    }
};
window.socket.onAny((event, ...args) => {
    console.log("📡 EVENT:", event, args);
});
/**
 * =========================================
 * OPTIONAL: MANUAL DEBUG
 * =========================================
 */
window.debugSocket = () => {
    console.log("===== SOCKET DEBUG =====");
    console.log("ID:", socket.id);
    console.log("Connected:", socket.connected);
    console.log("Session:", window.currentSessionId);
};