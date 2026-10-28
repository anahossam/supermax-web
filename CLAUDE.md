# SuperMax Work Orders — project guide

Web app replacing the Access database of SUPERMAX for Metal Works (شركة سوبر ماكس للصناعات المعدنية):
production orders, maintenance orders, customers, A4 printouts. Full brief:
`../VSCode Prompt - SuperMax Web App.md`. Approved UI: `../Web Mockups/*.jpg`.
Author: Hossam Ibrahim. Version in `.env` `APP_VERSION` (started at 1.0.0).

## Layout (all under `D:\OneDrive\Smart-IT\1-IT Solutions\SuperMax\Database`)
- `supermax-web/` — this Laravel app (the git repo).
- `backups/` — database dumps and release zips. Never in git (they contain customer data).
- `data-exports/` — CSV/JSON exported from Access/Excel for the import. Never in git.
- `أوامر تشغيل الصيانة.accdb`, `SuperMax Work Orders.xlsm` — sources. READ-ONLY: copy before reading.
  The Excel workbook holds the live data; Access holds the 2016–2017 history.

## Stack
- PHP 8.3 + Laravel 13; MariaDB 10.11 in production (utf8mb4). Local dev DB: SQLite for now (Laragon MySQL 8.4
  not initialised yet), so stick to features that work on SQLite, MySQL and MariaDB.
- Blade + Bootstrap 5 RTL + Bootstrap Icons bundled with Vite (no CDN, no web fonts), minimal JS.
  Built assets (`public/build`) are uploaded: no Node on the host.
- Printing: HTML A4 + print CSS. No server-side PDF libraries.
- Auth: custom username/password login (`LoginController`), no registration. Roles (`App\Enums\Role`):
  `admin` (one account), `editor`, `viewer`. Gates: `admin`, `edit`.
- Lookup lists live in one table `lookups` (`type` = status, mould_material, sample_material, cavity, position).

## Hosting
- InMotion Hosting reseller (cPanel), PHP 8.3, MariaDB 10.11.19, domain `orders.supermax-eg.com`.
- SSH via a key generated in cPanel "Manage SSH Keys". Composer on host not confirmed: build `vendor/` locally.

## Commands (PHP and Composer are not on PATH; use Laragon)
```powershell
$php = 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe'
& $php artisan ...                                   # artisan
& $php 'C:\laragon\bin\composer\composer.phar' ...   # composer
& $php artisan test                                  # tests (SQLite in memory)
& $php artisan migrate:fresh --seed                  # local dev DB only — ask first on any real DB
npm run build                                        # assets (Node 24 on PATH)
```

## Rules
- Code, comments, commits and docs in English; UI text in Arabic, RTL.
- Business rules (daily numbering, order code `N`+`ddmmyy`, etc.) are in brief §2 and must match Access/Excel.
- Never modify the .accdb/.xlsm. Never commit `.env`, passwords or dumps with customer data.
- Ask before destructive commands (deleting data, dropping tables, `migrate:fresh` on a real DB).
- Keep it simple: ~500 records, 3–5 users.
- After each phase: show the result, update `PROJECT_NOTES.md` and `DEVELOPMENT_LOG.md`, commit.
- "ship it now" = commit, tag `vX.Y.Z`, push to GitHub, dated release zip in `../backups/`.
- Access needs 32-bit PowerShell (`%WINDIR%\SysWOW64\WindowsPowerShell\v1.0\powershell.exe`); scripts with Arabic
  must be UTF-8 with BOM.
- OneDrive: pause syncing while running `composer install` / `npm install` if file-lock errors appear.
