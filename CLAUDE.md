# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Coolify Overview

Coolify is an open-source & self-hostable alternative to Heroku/Netlify/Vercel that helps manage servers, applications, and databases via SSH.

**Important**: The main branch is `v4.x`, not `main`.

## Tech Stack

- **Frontend**: Livewire, Alpine.js, Blade templates, Tailwind CSS, Monaco Editor, XTerm.js
- **Backend**: Laravel 11 (PHP 8.4), PostgreSQL 15, Redis 7, Soketi (WebSocket)
- **Infrastructure**: Docker, Docker Compose, Nginx, S6 Overlay

## Development Commands

### Local Development Setup
```bash
# Clone and setup using spin tool
git clone https://github.com/coollabsio/coolify && cd coolify
spin up

# Access at http://localhost:8000
# Default credentials: test@example.com / password
```

### Frontend (Vite)
```bash
npm run dev    # Start development server
npm run build  # Build for production
```

### Backend (Laravel/PHP)
```bash
# Code formatting (Laravel Pint)
./vendor/bin/pint

# Static analysis (PHPStan)
./vendor/bin/phpstan analyse

# Tests (Pest)
./vendor/bin/pest

# Run specific test
./vendor/bin/pest tests/Feature/YourTest.php
./vendor/bin/pest --filter "test name"

# Database migrations
php artisan migrate
php artisan migrate:fresh --seed  # Reset and seed database
```

### Additional Development Tools
- **Horizon** (Queue dashboard): http://localhost:8000/horizon
- **Mailpit** (Email testing): http://localhost:8025
- **Telescope** (Debugging): http://localhost:8000/telescope (disabled by default)

## Architecture & Key Directories

### Application Structure
```
app/
├── Livewire/         # All Livewire components organized by feature
│   ├── Server/       # Server management components
│   ├── Project/      # Application/database project components
│   ├── Settings/     # User and system settings
│   └── ...
├── Jobs/             # Async jobs for deployments, backups, server operations
├── Models/           # Eloquent models (Application, Server, Database, etc.)
├── Actions/          # Service layer for complex operations
└── Notifications/    # Multi-channel notifications (Discord, Telegram, Email, etc.)

templates/compose/    # 100+ Docker compose templates for self-hosted apps
bootstrap/helpers/    # Custom helper functions
docker/              # Docker configurations for different environments
```

### Key Architectural Patterns

1. **Livewire-First Approach**: Most UI interactions are handled through Livewire components, minimizing JavaScript needs
2. **Job-Based Operations**: Heavy operations (deployments, backups) use Laravel Jobs with Horizon
3. **Docker Integration**: Core functionality revolves around Docker container management
4. **Multi-tenant Architecture**: Supports teams and projects with proper isolation
5. **Event-Driven**: Uses Laravel events for notifications and state changes

### Database Models Hierarchy
- **Team** → has many **Projects**
- **Project** → has many **Applications**, **Databases**, **Services**
- **Server** → hosts applications and databases
- **Application** → deployable units with various sources (Git, Docker, etc.)

## Important Development Notes

1. **Database Migrations**: Always run migrations after switching branches
   ```bash
   php artisan migrate
   ```

2. **Pull Request Target**: PRs should target `next` branch, not `main` or `v4.x`

3. **Docker Compose Templates**: When adding new application templates:
   - Place in `templates/compose/`
   - Include proper labels and configurations
   - Test deployment thoroughly

4. **Livewire Component Organization**: Components are feature-grouped, not by type

5. **Helper Functions**: Common functions are in `bootstrap/helpers/`:
   - `application.php`, `docker.php`, `github.php`, `proxy.php`, `regex.php`, `remoteProcess.php`, `s3.php`, `services.php`, `shared.php`

6. **Notification System**: Supports Discord, Telegram, Email, Slack, and Pushover - test all channels when modifying

7. **Subscription Features**: Stripe integration for cloud version - be careful with billing-related code

8. **Server Communication**: All server operations happen via SSH - ensure proper error handling

9. **Queue Workers**: Horizon manages all background jobs - monitor failed jobs during development