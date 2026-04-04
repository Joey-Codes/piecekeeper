# PieceKeeper

A web app for managing your piano repertoire, tracking practice sessions, and building consistent practice habits.

## Features

- **Repertoire Management** — Add, organize, and track pieces across statuses (Learning, Polishing, Mastered, Relearning, Shelved, Want to Learn). Drag-and-drop reordering for your practice rotation.
- **Daily Practice Sessions** — Automatically generates a daily checklist of pieces based on your rotation settings. Start a timed session, check off pieces as you go, and review your results.
- **Practice History** — See your last 7 days at a glance on the dashboard streak tracker.
- **Customizable Schedule** — Set how many pieces per session and your practice frequency.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2)
- **Frontend:** Vue 3 (SFC) + Vite 7 + Tailwind CSS 4
- **Database:** MySQL

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

### Installation

```bash
# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Set up database
php artisan migrate
php artisan db:seed

# Start development servers
composer dev
```

This runs Laravel on `http://localhost:8000` with Vite HMR on port 5173.