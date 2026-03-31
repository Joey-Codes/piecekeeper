# CLAUDE.md

## About
Piano repertoire manager — a single-page Vue 3 app served by Laravel. Users manage pieces, run timed daily practice sessions with a rotating checklist, and track progress.

## Key Conventions
- All client API calls must use the `resources/js/api.js` helper (never raw `fetch`)
- All API routes live in `routes/web.php` under the `/api` prefix (no separate `api.php`)
- Business logic goes in `app/Services/`, not in controllers or models
- Edits and plans should be production-minded, not just "what works" locally
- PROMPT before any irreversible actions (deleting data, destructive migrations, etc.)

## Tailwind Conventions
- DO NOT use font weights less than 500, there is no reason to have very light text on the site
- 'text-md' is not a valid tailwind utility, do not use

## Stack
- **Backend:** Laravel 12 (PHP 8.2), MySQL
- **Frontend:** Vue 3 (SFC) + Vite 7 + Tailwind CSS 4

## Project Layout
Four tabs: **Dashboard** (default), **My Repertoire**, **Insights**, **Settings** — each maps to a folder under `resources/js/components/`.

Key backend paths:
- `app/Http/Controllers/` — DashboardController, PieceController, AuthController, UserController
- `app/Services/PracticeSessionService.php` — daily session generation (rotation logic)
- `app/Models/` — User, Piece, PracticeSession (with pivot `practice_session_pieces`)

## Development
```bash
composer dev              # All services (Laravel, Vite, queue, logs)
php artisan serve         # Laravel only — http://localhost:8000
npm run dev               # Vite HMR only — http://localhost:5173
composer test             # Run tests
php artisan test --filter TestClassName
./vendor/bin/pint         # PHP linting
```

## Environment Notes
- Composer path: `~/.config/herd-lite/bin/composer.bat` (not on PATH in bash)
- DB password has special chars — must be quoted in `.env`: `DB_PASSWORD="..."`
