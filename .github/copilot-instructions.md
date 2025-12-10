# Copilot instructions for helloUpTown

These instructions give a coding agent the repository-specific knowledge needed to be productive quickly.

**Big Picture:**
- **Architecture:** Laravel app (backend PHP 8.2) with frontend assets built by Vite/Tailwind. Core PHP app lives under `app/`, routes in `routes/`, and views in `resources/views/`.
- **Data model:** Eloquent models live in `app/Models` (examples: `User`, `Role`, `Business`, `RoleUser`). Database schema is defined in `database/migrations` (see `2025_12_05_*` migrations for recently added tables).
- **Why this structure:** Standard Laravel skeleton; controllers are thin (see `app/Http/Controllers/Controller.php`) and business logic should live on models or services to keep controllers minimal.

**How to set up & run (key commands):**
- **Install PHP deps:** `composer install` (or `composer run setup` to run the repository `setup` script which additionally copies `.env` and runs migrations in some flows).
- **Install JS deps & build:** `npm install` then `npm run dev` (development) or `npm run build` (production). `package.json` uses Vite + Tailwind.
- **Run dev suite (convenience):** `composer run dev` — this script uses `npx concurrently` to start `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `vite` together.
- **Run tests:** `composer run test` (runs `php artisan test`). PHPUnit is configured in `phpunit.xml` to use an in-memory SQLite DB for tests (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), so tests should not require local DB setup.

**Project-specific conventions & patterns:**
- **PSR-4 autoloading:** `App\\` namespace maps to `app/` (see `composer.json`). Add classes under `app/` and update namespaces accordingly.
- **Models:** Keep models under `app/Models`. Example: `app/Models/User.php` uses `HasFactory` and a typed `casts()` method — prefer typed return values where used.
- **Migrations naming:** New table migrations follow Laravel timestamped filenames (see `database/migrations/2025_12_05_*`). Avoid manually editing old migration filenames.
- **Controllers:** Minimal base controller located at `app/Http/Controllers/Controller.php`. Add controllers in this folder and register routes in `routes/web.php` or `routes/api.php` (API routes not present in this skeleton).
- **Frontend assets:** Entry points are under `resources/js` and `resources/css` (see `resources/js/app.js`, `resources/css/app.css`); Vite is configured in `vite.config.js` and `package.json` scripts.

**Testing / CI hints:**
- Tests are run via `php artisan test`; `phpunit.xml` sets environment values appropriate for CI (in-memory DB, array cache/session, sync queue). When adding integration tests, prefer the in-memory sqlite setup to keep CI fast.

**Integration points & external deps:**
- Laravel framework & ecosystem packages are in `composer.json` (e.g., `laravel/framework`, `phpunit/phpunit` in dev). JS tooling uses `vite` and `laravel-vite-plugin`.
- Background jobs: Laravel queues are used (see composer `dev` script starting `php artisan queue:listen`). If you add long-running workers, ensure local dev `composer run dev` remains stable.

**Files to inspect for context when making changes:**
- `app/Models/*` — model shape and examples (`User.php`, `Role.php`, `Business.php`, `RoleUser.php`).
- `database/migrations/*` — current table definitions; check timestamps and foreign keys before changing.
- `routes/web.php` — where basic web routes live (root returns `welcome` view).
- `resources/js/*`, `resources/css/*`, `vite.config.js`, `package.json` — frontend build and dev flow.
- `phpunit.xml` — test environment overrides (important for running tests reliably).

**Examples to follow in PRs:**
- Add controllers under `app/Http/Controllers` and keep them thin: put heavy logic on models or new service classes under `app/`.
- When adding DB-backed tests, rely on the in-memory sqlite config found in `phpunit.xml` instead of requiring a MySQL/Postgres service.

If anything here is unclear or you'd like more examples (e.g., a minimal new controller + test PR template), tell me which area to expand and I'll iterate.
