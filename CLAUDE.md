# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## About
- This project is a web app for managing a piano repetoire, designed to create a practice schedule and other related features.

## Project Structure
- Composed of 4 main tabs: Dashboard (default), My Repertoire, Insights, and Settings.
- When asked about one of these tabs, start at their respective folders under resources/js/components.

## Specific Tips to Remember
- Edits should be made with production use-case in mind, not just 'what works' for the moment
- Any client api calls should use our api.js helper

## General Rules
- PROMPT before any potential irreversible actions such as deleting production data, NEVER do those automatically

## Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Vue 3 (SFC) + Vite 7 + Tailwind CSS 4
- **Database:** MySQL — connection configured in `.env` (`piano_piece_manager` database)

## Development

Start all services (Laravel, Vite, queue worker, log watcher) concurrently:
```bash
composer dev
```

Or individually:
```bash
php artisan serve     # Laravel on http://localhost:8000
npm run dev           # Vite HMR on http://localhost:5173 (do not visit directly)
```

Run tests:
```bash
composer test
```

Run a single test:
```bash
php artisan test --filter TestClassName
```

Lint PHP (Laravel Pint):
```bash
./vendor/bin/pint
```

## Architecture

This is a single-page Vue 3 app served by Laravel.

- **Entry point:** `resources/views/welcome.blade.php` — minimal HTML shell with `<div id="app">` and `@vite` directive
- **Vue root:** `resources/js/app.js` mounts `resources/js/App.vue` onto `#app`
- **Vue components** live in `resources/js/` (`.vue` SFCs)
- **API routes** go in `routes/web.php` (or `routes/api.php` for JSON endpoints)
- **Laravel models** live in `app/Models/`, controllers in `app/Http/Controllers/`
- **Migrations** in `database/migrations/` — run with `php artisan migrate`

## Environment

Composer is at `~/.config/herd-lite/bin/composer.bat` (not on PATH in bash — use full path or `composer` via Windows terminal).
DB password contains special characters; wrap in quotes in `.env`: `DB_PASSWORD="..."`
