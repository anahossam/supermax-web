# SuperMax Work Orders

Production and maintenance work orders for SUPERMAX for Metal Works (شركة سوبر ماكس للصناعات المعدنية).
Laravel 13, Blade, Bootstrap 5 RTL. Arabic, right-to-left interface.

- Author: Hossam Ibrahim
- Version: 1.0.0

## Local setup
```powershell
$php = 'C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe'
& $php 'C:\laragon\bin\composer\composer.phar' install
copy .env.example .env; & $php artisan key:generate
& $php artisan migrate --seed
npm install; npm run build
& $php artisan serve --port=8077
```
Run tests with `& $php artisan test`. See `CLAUDE.md` and `PROJECT_NOTES.md` for details.