/leMalagasy-V2
│
├── /app
│   ├── /core
│   ├── /models
│   ├── /services        ← logique métier
│   ├── /middlewares     ← auth, rôles
│   │
│   ├── /controllers
│   │   ├── /front
│   │   └── /admin
│   │
│   ├── /views
│   │   ├── /front
│   │   └── /admin
│
├── /routes
│   ├── web.php          ← front
│   └── admin.php        ← backoffice
│
├── /public
│   ├── index.php
│   ├── .htaccess
│   └── /assets
│       ├── /front
│       └── /admin
│
├── /config
│
├── /storage
│   ├── /uploads         ← images articles
│   └── /logs
│
└── composer.json