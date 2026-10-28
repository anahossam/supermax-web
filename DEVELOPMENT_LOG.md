# Development log

## 2026-10-28 — Release v1.0.0
- First "ship it now": tagged `v1.0.0`, pushed to private GitHub repo `anahossam/supermax-web`, release zip
  `../backups/supermax-web-v1.0.0-2026-10-28.zip`. Contents: Phase 1 foundation.

## 2026-10-28 — Phase 1: foundation
- Created Laragon `php.ini` (extensions: curl, fileinfo, gd, intl, mbstring, openssl, pdo_mysql, pdo_sqlite,
  sqlite3, zip, mysqli, sodium; timezone Africa/Cairo; memory 512M).
- Installed Laravel 13.39 into `supermax-web/`; removed the skeleton's AGENTS.md, welcome page and example tests.
- Migrations: users (username, role, is_active), customers, lookups, production_orders, maintenance_orders,
  audit_logs. Models with relations; `App\Enums\Role`; gates `admin` and `edit`.
- Username login with throttling, remember-me, inactive-user block (`active` middleware); no registration.
- Seeders: lookup lists from Access, single admin (random password unless `SEED_ADMIN_PASSWORD` is set).
- Front end: Bootstrap 5.3.8 RTL + Bootstrap Icons via Vite (no CDN), Segoe UI/Tahoma; RTL shell matching the
  mockups (navy sidebar, page header, stat cards, status badge colours), phone top bar, offcanvas menu, bottom
  nav and "+" button. Placeholder pages for later phases.
- Tests: 18 passing (auth, role access, seeder).

## 2026-10-28 — Phase 0: setup
- Read the brief and reviewed the five mockups (dashboard, orders list, order form, A4 print, phone).
- Collected hosting and user answers (see PROJECT_NOTES.md).
- Checked local tools: PHP/Composer only inside Laragon (not on PATH); Laragon PHP had no php.ini.
- Created `supermax-web/` (git init, branch `main`), `../backups/`, `../data-exports/`, and the memory files.
