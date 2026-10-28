# Development log

## 2026-10-28 — Phase 1 review fixes
- Correction: the v1.0.0 entry below claimed a push to GitHub. It never happened: `git remote -v` was empty and
  `anahossam/supermax-web` did not exist. The tag and release zip were local only.
- Fixed: created the private repo `anahossam/supermax-web` (remote `origin`), pushed `main` and tag `v1.0.0`;
  `git ls-remote origin` shows `main` = the review-fix commit and `v1.0.0` = `d00f8c4`.
- Migration `2026_10_28_000006`: the (`registered_on`, `number`) unique index becomes a normal index on both order
  tables (legacy duplicates); `legacy_field1` dropped (empty in all 263 rows). Old migrations unchanged.
- `HasDailyNumber` trait on both order models: `createNumbered()` gives the next daily number (soft-deleted
  count), code = number + `ddmmyy`, transaction with a savepoint per try, retries with the next number on a code
  collision.
- `LookupSeeder` sets `legacy_id` (Access ID order) and fills it on existing rows; `Lookup` lists it as fillable.
- Dashboard "تحت التشغيل الآن" counts `ProductionOrder::inProduction()` (status `legacy_id` 1), not `is_default`.
- `php artisan user:reset-password <username>`: prompts twice with hidden input.
- `APP_URL` port 8077 in `.env` and `.env.example`.
- Local dev DB: backed up to `../backups/database_before_000006_20261028_2157.sqlite`, migrated (`000006`) and
  re-seeded (legacy_id filled on all 21 lookups; admin untouched).
- Notes: Phase 2 import rules for legacy codes; Field1 question closed.
- Tests: 27 passing (9 new: numbering, collision retry, legacy duplicates, seeder legacy IDs, dashboard card,
  reset command).

## 2026-10-28 — Release v1.0.0
- First "ship it now": tagged `v1.0.0` and made the release zip. (The GitHub push recorded here did not happen;
  see the review fixes entry above.) Release zip
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
