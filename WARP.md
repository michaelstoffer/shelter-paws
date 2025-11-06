# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

**Shelter Paws** is a Laravel 12 + Livewire 3 + Inertia/Vue 3 demo application simulating a shelter management system. It showcases the **Intake → Care → Adoption** workflow for animal shelters.

## Development Commands

### Initial Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### Running the Application
```bash
# Start all services (server, queue, logs, vite) with concurrently
composer dev

# Or run services individually:
php artisan serve              # Backend server (http://localhost:8000)
npm run dev                    # Frontend dev server (Vite)
php artisan queue:listen       # Queue worker
php artisan pail               # Log viewer
```

### Database Operations
```bash
php artisan migrate            # Run migrations
php artisan migrate:fresh      # Drop all tables and re-run migrations
php artisan migrate --seed     # Run migrations with seeders
php artisan db:seed            # Run seeders only
```

### Testing
```bash
php artisan test               # Run all tests (PHPUnit)
composer test                  # Alternative: clears config then runs tests
php artisan test --filter=ExampleTest  # Run specific test
```

### Code Quality
```bash
vendor/bin/pint                # Format PHP code (Laravel Pint)
vendor/bin/pint --test         # Check code style without fixing
```

### Frontend Build
```bash
npm run build                  # Production build
npm run dev                    # Development mode with hot reload
```

### Artisan Common Commands
```bash
php artisan route:list         # List all routes
php artisan tinker             # Interactive REPL
php artisan make:model Animal -mfc  # Create model with migration, factory, controller
php artisan make:livewire Animals/Index  # Create Livewire component
php artisan make:controller Api/AnimalApiController --api  # Create API controller
```

## Architecture

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 (via Inertia.js) + Alpine.js + Livewire 3
- **CSS**: TailwindCSS 4
- **Database**: SQLite (default), MySQL supported
- **Auth**: Laravel Breeze (Vue SSR)
- **Testing**: PHPUnit/Pest
- **API**: Laravel Sanctum

### Hybrid Frontend Pattern
This application uses **both** Livewire and Inertia/Vue:
- **Livewire**: For server-side rendered CRUD operations (e.g., Animals Index/Create)
- **Inertia/Vue**: For highly interactive client-side features (e.g., Adoption Queue, Hold Board with drag-and-drop)

When adding new features:
- Use Livewire for traditional CRUD and form-heavy pages
- Use Inertia/Vue for interactive dashboards, drag-and-drop, or complex client-side state

### Key Directories
- `app/Http/Controllers/` - Standard controllers and API controllers
- `app/Http/Livewire/` - Livewire components (planned, not yet implemented)
- `app/Models/` - Eloquent models (Animal, AdoptionApplication, Hold, User planned)
- `resources/js/Pages/` - Inertia.js Vue pages (AdoptionQueue.vue, HoldBoard.vue planned)
- `resources/js/Components/` - Reusable Vue components (AnimalCard.vue planned)
- `resources/views/livewire/` - Livewire Blade templates
- `routes/web.php` - Web routes (Livewire and Inertia routes)
- `routes/api.php` - API routes (planned, not yet present)
- `database/migrations/` - Database schema
- `database/seeders/` - Data seeding (DemoSeeder planned for animals, applications, users)
- `tests/Feature/` - Feature tests

### Data Models (Planned)
- **Animal**: Core entity (name, species, breed, age, status, intake_date, etc.)
- **AdoptionApplication**: Applications from potential adopters
- **Hold**: Represents temporary holds on animals (Available → Hold → Pending workflow)
- **User**: Staff/admin users with authentication

### Configuration
- **Database**: SQLite by default (configured in `.env`)
- **Queue**: Database driver (not sync - queue worker runs in background)
- **Session**: Database-backed sessions
- **Cache**: Database-backed cache
- **Mail**: Log driver (for development)

### Environment Notes
- This project is designed for **Laravel Herd** or **Valet** environments on macOS
- Uses SQLite for simplicity (no MySQL setup required)
- Xdebug may be active - warnings can be ignored if not debugging

## Development Workflow

### Adding a New Animal Feature
1. Create model, migration, factory: `php artisan make:model Animal -mfc`
2. Update migration schema in `database/migrations/`
3. Run migration: `php artisan migrate`
4. For Livewire: `php artisan make:livewire Animals/Index`
5. For Inertia: Create Vue component in `resources/js/Pages/`
6. Add routes in `routes/web.php`
7. Write tests in `tests/Feature/`
8. Run tests: `php artisan test`

### API Development
- API routes will go in `routes/api.php` (create if not exists)
- API controllers in `app/Http/Controllers/Api/`
- Use Laravel Sanctum for authentication if needed
- Return JSON responses consistently

### Styling
- Use TailwindCSS 4 utility classes
- Configuration via `vite.config.js` (uses `@tailwindcss/vite` plugin)
- No separate `tailwind.config.js` needed with v4

### Database
- Primary database is SQLite (`database/database.sqlite`)
- Tests use in-memory SQLite (`:memory:`)
- To switch to MySQL, update `.env` database settings

## Code Style & Best Practices

### PHP Code Style
- Follow PSR-12 coding standards
- Use Laravel Pint for automatic formatting
- Indentation: 4 spaces (per `.editorconfig`)
- Run `vendor/bin/pint` before committing

### Naming Conventions
- Models: Singular (Animal, not Animals)
- Controllers: Plural (AnimalsController) or resource-based (AnimalController with resource methods)
- Livewire components: Namespace by feature (Animals/Index, Animals/Create)
- Database tables: Plural, snake_case (animals, adoption_applications)
- Migration files: Descriptive with date prefix

### Testing
- Write Feature tests for user-facing workflows
- Use PHPUnit assertions (this project uses PHPUnit, not Pest despite pest-plugin in composer.json)
- Tests should be isolated and use database transactions or `RefreshDatabase` trait
- Test files follow naming: `SomethingTest.php` with method `test_something_works()`

## Project-Specific Notes

### Compassionate Language
- This is a shelter management system - use empathetic, humane language
- Avoid clinical terms like "inventory" for animals
- Use "intake" not "acquisition", "adoption" not "sale"
- Status values: "available", "hold", "pending", "adopted" (not "sold", "processed")

### Demo Data
- `DatabaseSeeder` creates a test user: `test@example.com` / password from factory
- Planned `DemoSeeder` will populate animals, applications, and holds
- Use factories for consistent test data generation

### Authentication
- Laravel Breeze provides auth scaffolding with Vue SSR
- Auth views and components provided by Breeze
- Protected routes should use `auth` middleware

### Performance Considerations
- Use eager loading for relationships to avoid N+1 queries
- Database queries for listings should be paginated
- Consider using Laravel's query builder for complex queries

## Troubleshooting

### Common Issues
- **Xdebug warnings**: Safe to ignore if not actively debugging
- **Queue not processing**: Ensure `php artisan queue:listen` is running (or use `composer dev`)
- **Vite not loading**: Check `npm run dev` is running and ports are available
- **Database not found**: Run `touch database/database.sqlite` if SQLite file is missing
- **Cache issues**: Run `php artisan config:clear && php artisan cache:clear`
