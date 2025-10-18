## Folder structure

```bash
tourism-cms/
│
├── public/                          # Public accessible files
│   ├── index.php                    # Front controller
│   ├── .htaccess                    # URL rewriting
│   │
│   ├── assets/                      # Public assets
│   │   ├── css/
│   │   │   ├── admin.css
│   │   │   └── frontend.css
│   │   ├── js/
│   │   │   ├── admin.js
│   │   │   └── frontend.js
│   │   └── images/
│   │
│   └── uploads/                     # Uploaded media files
│       ├── images/
│       ├── videos/
│       └── documents/
│
├── app/                             # Application code
│   │
│   ├── controllers/                 # Controllers
│   │   ├── admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── MediaController.php
│   │   │   ├── LanguageController.php
│   │   │   └── AuthController.php
│   │   └── frontend/
│   │       ├── HomeController.php
│   │       └── PageController.php
│   │
│   ├── models/                      # Models
│   │   ├── Page.php
│   │   ├── PageTranslation.php
│   │   ├── PageMeta.php
│   │   ├── Language.php
│   │   ├── Media.php
│   │   └── User.php
│   │
│   ├── views/                       # Views
│   │   ├── admin/
│   │   │   ├── layouts/
│   │   │   │   ├── header.php
│   │   │   │   ├── sidebar.php
│   │   │   │   └── footer.php
│   │   │   ├── dashboard/
│   │   │   │   └── index.php
│   │   │   ├── pages/
│   │   │   │   ├── index.php       # List pages
│   │   │   │   ├── create.php      # Add new page
│   │   │   │   └── edit.php        # Edit page
│   │   │   └── auth/
│   │   │       └── login.php
│   │   └── frontend/
│   │       └── pages/
│   │           └── view.php
│   │
│   └── core/                        # Core framework files
│       ├── Database.php             # Database connection
│       ├── Model.php                # Base model
│       ├── Controller.php           # Base controller
│       ├── Router.php               # Router
│       ├── Session.php              # Session management
│       ├── Validator.php            # Form validation
│       └── Helper.php               # Helper functions
│
├── config/                          # Configuration files
│   ├── config.php                   # Main config
│   ├── database.php                 # Database config
│   └── routes.php                   # Route definitions
│
├── storage/                         # Storage (not public)
│   ├── logs/                        # Log files
│   └── cache/                       # Cache files
│
└── vendor/                          # Third-party libraries (if using Composer)
```

## Run server

```bash
php -S localhost:8080 server.php
```