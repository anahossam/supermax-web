# Project notes

Version 1.0.0 · Author: Hossam Ibrahim

## Status
- Phase 0 (setup): done.
- Phase 1 (foundation): done 2026-10-28 — Laravel 13.39, migrations, models, username login, 3 roles, RTL shell,
  18 feature tests passing. Other screens are placeholders.
- v1.0.0 tagged locally on 2026-10-28. The GitHub push recorded that day did not happen (no remote, no repo);
  see the log. Only record a push after `git ls-remote origin` shows the commit and the tag.
- Phase 1 review fixes done 2026-10-28 (numbering index, Field1 dropped, lookup legacy IDs, dashboard card,
  `user:reset-password`, APP_URL port). 27 tests passing.
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
  ~~Unique index on (`registered_on`, `number`) per table~~ (superseded below), `code` unique. Soft deletes count
  toward numbering.
- 2026-10-28 — "ship it now" = commit, tag, push to GitHub (private), dated release zip in `../backups/`.
- 2026-10-28 — Port 8000 is taken by another app on this PC; run the dev server with `--port=8077`
  (`APP_URL=http://localhost:8077` in `.env` and `.env.example`).
- 2026-10-28 — (`registered_on`, `number`) is a normal index, not unique (migration `000006`): Access has
  duplicates (12/09/2017 has order 1 twice). New orders stay unique through the unique `code`
  (= number + `ddmmyy`). `createNumbered()` (trait `HasDailyNumber`) saves in a transaction and retries with the
  next number if the code is taken (up to 5 tries).
- 2026-10-28 — `Field1` is empty in all 263 Access rows: `legacy_field1` dropped (migration `000006`).
- 2026-10-28 — `lookups.legacy_id` = Access ID = position in the seeder list (1, 2, 3…). The seeder fills it on
  existing rows too and never overwrites admin edits.
- 2026-10-28 — The dashboard card "تحت التشغيل الآن" counts the status with Access ID 1 (`legacy_id`,
  `Lookup::STATUS_IN_PRODUCTION`), not whichever status is the default, so renaming or changing the default
  does not change its meaning.
- 2026-10-28 — Passwords are reset with `php artisan user:reset-password <username>` (hidden prompt, typed
  twice, at least 8 characters). Never pass a password on the command line.

## Phase 2 import rules (legacy codes)
Old codes were typed by hand; many do not follow number + `ddmmyy` (32 production orders, e.g. `0`, `1`, `2`,
`111022017`, `2030808016`, `12400116`; 10 maintenance orders, e.g. `6` and `3060417` whose number is 6).
- Import `code` exactly as the original string. Never recalculate it.
- Keep `number` and `registered_on` as they are, even when they do not match the code.
- List every row whose code does not equal number + `ddmmyy` in the import report.
- `code` is a string column. Never cast it to a number in PHP or SQL (leading characters matter).
- Duplicate (`registered_on`, `number`) pairs are allowed; duplicate codes are not (report them, do not alter).
- Set lookup foreign keys through `lookups.legacy_id` (type + Access ID).

## Open questions
- Does the Excel workbook have the same schema/quirks as Access? How does its VBA compute the daily number
  (max+1 or count+1, deleted rows)? Inspect `modApp` / `frmRecord` in phase 2–3.
- Composer/SSH on host: confirm once the SSH key is set up (phase 6).
- Arabic validation messages (`lang/ar`) needed for the forms in phase 3.
