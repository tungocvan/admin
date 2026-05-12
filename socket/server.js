require('dotenv').config();
const express = require("express");
const { createServer } = require("http");
const { Server } = require("socket.io");

const app = express();
app.use(express.json());

const httpServer = createServer(app);

const io = new Server(httpServer, {
    cors: {
        origin: process.env.APP_URL || "*",
        methods: ["GET", "POST"]
    }
});

console.log("🚀 Node Realtime Server starting...");

/**
 * =========================
 * AUTH MIDDLEWARE
 * =========================
 */
const bridgeAuth = (req, res, next) => {
    const secret = req.headers['x-bridge-secret'];

    if (secret !== process.env.BRIDGE_SECRET_KEY) {
        return res.status(401).json({ error: 'Unauthorized bridge request' });
    }

    next();
};

/**
 * =========================
 * IMPORT MODULES (SAAS STYLE)
 * =========================
 * 👉 chat.js sẽ handle toàn bộ socket + bridge logic
 */
require('./events/chat')(io, bridgeAuth, app);
require('./events/internal-chat')(io);

/**
 * =========================
 * HEALTH CHECK (OPTIONAL)
 * =========================
 */
app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        service: 'realtime-node',
        timestamp: new Date().toISOString()
    });
});

/**
 * =========================
 * START SERVER
 * =========================
 */
const PORT = process.env.NODEJS_SERVER_PORT || 6001;

httpServer.listen(PORT, "0.0.0.0", () => {
    console.log(`🚀 Realtime Server running on port ${PORT}`);
});