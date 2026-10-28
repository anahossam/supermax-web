# Project notes

Version 1.0.0 · Author: Hossam Ibrahim

## Status
- Phase 0 (setup): done.
- Phase 1 (foundation): done 2026-10-28 — Laravel 13.39, migrations, models, username login, 3 roles, RTL shell,
  18 feature tests passing. Other screens are placeholders.
- Released v1.0.0 on 2026-10-28 (GitHub `anahossam/supermax-web`, private).
- Next: Phase 2 (import).

## Decisions
- 2026-10-28 — Project lives under the OneDrive `Database` folder at the owner's request (not `D:\Projects`).
  Accepted risk: OneDrive syncs `vendor/` and `node_modules/` (many files, possible lock errors).
- 2026-10-28 — Hosting: InMotion reseller cPanel, PHP 8.3, MariaDB 10.11.19, `orders.supermax-eg.com`.
- 2026-10-28 — Users: 3–5 people. Exactly three roles (admin / editor / viewer) and a single admin account
  (enforce "only one admin" on the Users page in phase 5).
- 2026-10-28 — Hossam Ibrahim is credited as Author (composer.json, package.json, `<meta name="author">`, sidebar,
  footer, login page). App version starts at 1.0.0 (`APP_VERSION`).
- 2026-10-28 — Live data source: the Excel workbook (owner will supply a fresh copy before go-live). Access holds
  the 2016–2017 history.
- 2026-10-28 — Dev tools: Laragon (PHP 8.3.33, Composer 2.10.2, MySQL 8.4.3), Node 24, git 2.56. Created
  Laragon's missing `php.ini` from `php.ini-development` with the Laravel extensions enabled.
- 2026-10-28 — Local dev DB is SQLite (Laragon MySQL never initialised). Tests use SQLite in memory.
- 2026-10-28 — Login is by username (not email); no password reset by email (admin resets passwords).
- 2026-10-28 — All lookup lists in one `lookups` table; status default flagged with `is_default`, badge colour in
  `color`. Access lookup IDs kept in `legacy_id` for the import.
- 2026-10-28 — Orders keep `customer_id` (nullable, for the 4 orphan orders) plus a free-text `company` per order.
  Unique index on (`registered_on`, `number`) per table, `code` unique. Soft deletes count toward numbering.
- 2026-10-28 — "ship it now" = commit, tag, push to GitHub (private), dated release zip in `../backups/`.
- 2026-10-28 — Port 8000 is taken by another app on this PC; run the dev server with `--port=8077`.

## Open questions
- `Field1` in `PRODCTIOB PLANE`: inspect values, then ask (phase 2). Stored as `legacy_field1` for now.
- Does the Excel workbook have the same schema/quirks as Access? How does its VBA compute the daily number
  (max+1 or count+1, deleted rows)? Inspect `modApp` / `frmRecord` in phase 2–3.
- Composer/SSH on host: confirm once the SSH key is set up (phase 6).
- Arabic validation messages (`lang/ar`) needed for the forms in phase 3.
