# Copilot instructions — alatikiberat

This repo is a Laravel 12 web app (PHP >= 8.2) using Vite + Tailwind for frontend assets.
Keep suggestions small, concrete, and follow existing naming and Blade patterns.

Quick setup (commands you can suggest or run):

- `composer install`
- copy `.env.example` to `.env` (composer scripts do this)
- `php artisan key:generate`
- ensure `database/database.sqlite` exists (composer scripts may touch it)
- `php artisan migrate`
- `php artisan storage:link`
- `npm install`
- `npm run dev` (or `npm run build` for production)

Helpful project facts (refer to these files):

- Project manifest: [composer.json](composer.json)
- Frontend config: [package.json](package.json) and `vite.config.js`
- Routes: [routes/web.php](routes/web.php)
- Views: [resources/views](resources/views) (examples: sidebar.blade.php, app.blade.php)
- Controllers: [app/Http/Controllers](app/Http/Controllers) (PSR-4 `App\` namespace)
- Tests: [phpunit.xml](phpunit.xml) and `tests/` (run with `composer test` / `php artisan test`)

Patterns & conventions to follow when editing or adding code:

- Blade link/active checks use `request()->routeIs('name')` and named routes. Example: see [resources/views/sidebar.blade.php](resources/views/sidebar.blade.php) which checks `routeIs('dashboard')` and `routeIs('inventory.*')` — keep route names consistent when adding links.
- Use dot notation for views (e.g. `view('admin-gudang-management')` maps to `resources/views/admin-gudang-management.blade.php`).
- Controllers should live under `App\Http\Controllers` and be PSR-4 autoloadable; run `composer dump-autoload` after adding classes.
- Asset edits should be wired via Vite + `laravel-vite-plugin` (see `package.json` scripts). Prefer `npm run dev` during development.

Dev workflow notes for agents:

- Long-running dev: `composer run-script dev` runs `php artisan serve`, queue listener, pail and Vite concurrently (see `composer.json` `scripts.dev`). Use that for local full-stack runs.
- Use `php artisan storage:link` to expose `storage/app/public` to `public/storage` (terminal shows it used).
- If you add or change routes, run `php artisan route:clear` and `php artisan config:clear` if cached config causes confusion.

Examples to follow when creating new features:

- Add a route + controller + view trio:

  Route example:

  ```php
  Route::get('/dashboard', [App\\Http\\Controllers\\DashboardController::class, 'index'])->name('dashboard');
  ```

  Controller boilerplate: extend `App\\Http\\Controllers\\Controller` and return `view('manajer-pembelian-dashboard')`.

When to ask maintainers or the user:

- Database credentials, seed data expectations, and any external API keys — do not assume production values.

If anything here is unclear or you want me to include more examples (e.g., controller skeletons, test patterns, or common Blade helpers used), tell me what to expand.
