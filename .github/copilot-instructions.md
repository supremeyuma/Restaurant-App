## Quick orientation

This is a Laravel 10 application (PHP >= 8.1) using Vite/Tailwind for frontend assets.
Key pieces:
- Routes: `routes/web.php` (public + admin prefix)
- Controllers: `app/Http/Controllers/*` and `app/Http/Controllers/Admin/*`
- Models: `app/Models` (notably `Order`, `OrderItem`, `Item`, `Setting`)
- Views: `resources/views` (look under `admin` and `order` folders for examples)
- Helpers: `app/helpers.php` provides `setting($key, $default)` and other convenience functions

Focus first on `OrderController` and `Admin/*` controllers to understand order lifecycle and exports.

## Important architecture notes
- Orders: created in `OrderController::pay()` — the flow creates an `Order`, then `OrderItem` records, stores `last_order_id` in session, and delegates payment initialization to Paystack via Guzzle.
- Payment verification: `OrderController::confirm()` verifies Paystack using the transaction reference. The code also mentions using webhooks — prefer adding Paystack webhook handlers for reliable final-state updates.
- Session cart: stored in `session('cart')` as an array of items. Each cart item has at least `id`, `name`, `price`, `qty`. Controllers expect this shape (see `OrderController::checkout()` and `pay()`).
- Single-site settings: `Setting::first()` is used in `app/helpers.php` and consumed via `setting('delivery_fee')`. The app assumes a single row in `settings` table.

## Concrete developer workflows (commands)
Use PowerShell on Windows (examples):

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run dev   # vite dev server
# or production build
npm run build
```

Tests and linting:
- Run tests: `php artisan test` or `vendor/bin/phpunit`
- Code style: `./vendor/bin/pint` (installed as a dev dependency)

Notes about tests: `phpunit.xml` sets environment variables for testing but has DB sqlite commented out. Enable an in-memory sqlite DB or configure a test DB before running tests.

## Project-specific conventions and gotchas
- Admin area uses `Route::prefix('admin')->name('admin.')` — controller namespace `App\Http\Controllers\Admin`.
- Order status values observed in code: `pending`, `paid`, `failed`, `completed`. Keep these strings consistent when changing code or UI.
- Pickup codes: generated in `OrderController` as `Str::random(6)` and stored on orders. `OrderScanController::result($code)` looks up `pickup_code` and returns JSON.
- Pay attention to field naming inconsistencies: some code references `total` on the `Order` model while admin export code references `total_amount` (see `app/Http/Controllers/Admin/AdminOrderController.php` and `resources/views/admin/orders/export_pdf.blade.php`). Reconcile these before changing exports.
- Settings accessed via `setting($key, $default)` helper; prefer using this instead of direct Setting queries for single-value reads.

## Integration points & environment
- Paystack: `PAYSTACK_SECRET_KEY` and `PAYSTACK_CALLBACK_URL` must be set in `.env` for payment flows to work. `OrderController` uses Guzzle to call Paystack API.
- PDF export: uses `barryvdh/laravel-dompdf` (see `AdminOrderController::exportPdf`).
- Frontend: Vite + Alpine + Tailwind. Look at `resources/js` and `resources/css` for components and build hooks.

## Where to look for examples of patterns
- Cart lifecycle: `routes/web.php` -> `OrderController::checkout`, `OrderController::pay`, `OrderController::confirm`.
- Admin order workflows and exports: `app/Http/Controllers/Admin/AdminOrderController.php` and `app/Http/Controllers/Admin/OrderScanController.php`.
- Settings helper: `app/helpers.php` and `app/Models/Setting.php`.
- Eloquent conventions and fillable fields: `app/Models/Order.php`, `OrderItem.php`, `Item.php`.

## Safety and recommended first edits for AI agents
- When modifying payment code, do not change live API keys or behaviour without adding tests and a toggle for sandbox/test mode.
- Prefer adding Paystack webhook handlers instead of relying only on the redirect confirmation flow.
- When renaming fields like `total` vs `total_amount`, update all callers and admin exports together and add a migration + tests.

If anything here is unclear or you want more detail (examples of cart payloads, DB schema mapping, or typical request/response shapes), tell me which area to expand and I will iterate.
