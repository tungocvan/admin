# 📦 Structure: Modules/Chat

```
Chat/
├── Http
│   └── Controllers
│       ├── Api
│       │   └── ChatController.php
│       └── ChatController.php
├── Livewire
│   └── Chat
│       └── ChatManager.php
├── Models
│   ├── ChatMessage.php
│   └── ChatSession.php
├── Services
│   └── ChatService.php
├── config
│   └── module.php
├── database
│   └── migrations
│       ├── -0001_11_30_000041_create_chat_sessions_table.php
│       └── -0001_11_30_000042_create_chat_messages_table.php
├── resources
│   └── views
│       ├── chat.blade.php
│       ├── components
│       │   └── placeholder.blade.php
│       ├── livewire
│       │   └── chat
│       │       └── chat-manager.blade.php
│       └── pages
│           └── chat
│               └── index.blade.php
├── routes
│   ├── api.php
│   └── web.php
└── structure.md
```
