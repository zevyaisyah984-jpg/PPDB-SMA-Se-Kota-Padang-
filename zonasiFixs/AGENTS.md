# AGENTS.md

Sistem PPDB (student admission) for SMA Negeri Kota Padang. Plain PHP 8 + MySQL, **no framework, no build step, no Composer dependencies, no test suite, no CI, no linter/formatter**. All UI text, comments, and status values are in Indonesian — keep it that way.

## Run it

- Dev server is **Apache/Laragon at `http://localhost/<folder>/public/`**, not the built-in server. `composer start` (`php -S localhost:8000 -t public`) is effectively broken: PHP's built-in server ignores `.htaccess`, so every route except `/` 404s.
- **The repo currently lives in `C:\laragon\www\zonasiFixs`, not `.../zonasi`.** The base path used to be hardcoded as `/zonasi/public` in 5 places, which 404'd every route. It is now **auto-detected**: `config/app.php` derives `BASE_PATH` from `$_SERVER['SCRIPT_NAME']` (`dirname()`), falling back to `/zonasi/public` on CLI. `Router::dispatch()` and the two nav views read `BASE_PATH`. If you add code that needs the base path, use `BASE_PATH`/`BASE_URL` — **never hardcode the folder name again**.
- `auto-refresh.js` still hardcodes `origin + '/api/check-updates'` with no prefix, so the 30s update poller only works if the app is served at the domain root. Known broken; not a routing blocker.
- Only verification available: `php -l <file>` for syntax, then click through the page. `WALKTHROUGH_JUKNIS_2025.md` records manual verification steps for registration/selection flows — read it before changing those.

## Setup gotchas

- **`.env.example` is a decoy.** Nothing reads `.env` (no `getenv`/`parse_ini` anywhere in the codebase). Credentials are hardcoded in `config/database.php:5-8`. Edit there.
- `config/app.php` ships `APP_ENV=development`, which turns on `display_errors=1` + `error_reporting(E_ALL)`. Change it to `production` before deploying.
- **There is no base schema dump in the repo.** The `smapadang` database and its core tables (`users`, `siswa`, `sekolah`, `pendaftaran`, `admin`, `jadwal`, `settings`, `publikasi_hasil`, `audit_logs`, `activity_logs`) pre-exist outside git.
- **There is no migration runner.** Schema changes are ad-hoc SQL in `database/migrations/` and `tools/`, or one-off PHP scripts at the repo root, applied manually in phpMyAdmin. Keep new migrations idempotent (`ADD COLUMN IF NOT EXISTS` or the `INFORMATION_SCHEMA` + `PREPARE` guarded style already used in `database/migrations/`).
- Because the schema drifts, code defensively probes for optional columns (`Sekolah::update()` core/optional split at `app/Models/Sekolah.php:73-77`, `SelectionEngine::checkColumns()`). **When you add a column, also add it to the relevant list** or it will be silently skipped at runtime.
- **Verified schema mismatches** (the DB is the source of truth; these are live bugs, not hypotheticals):
  - `pendaftaran.status` is an `enum` that **has no `'cadangan'`**, but `SelectionEngine::updateStatus(..., 'cadangan')` writes it.
  - `pendaftaran.verifikasi_fisik` is `enum('belum','sudah')`, but `SelectionEngine::getRankedList()` filters `verifikasi_fisik = 1` — that filter never matches.
  - `pendaftaran` has `jarak` and `jarak_meter`; there is **no `jarak_km` column**, yet `DistanceCalculator::calculateForRegistration()` / `batchCalculate()` write to it.
  - `Sekolah::delete()` deactivates `users.role = 'school_admin'`, but school admins actually live in the `admin` table.
- `/register` is gated by `isRegistrationClosed()` (setting `tgl_selesai_pendaftaran`). To test the student signup flow you must temporarily push that date forward, then restore it.

## Architecture

- Entry: `public/index.php` → configs → hand-rolled `Router` → `routes/web.php` → `'Controller@method'` strings, resolved by `require_once` in `Router::call()` (`app/Helpers/Router.php:67-71`).
- **No autoloader is wired up.** The PSR-4 `App\` mapping in `composer.json` is dead (no `vendor/`). Every controller `require_once`s its own models at the top. New class → add the require yourself.
- `app/Middleware/*` and `app/Helpers/{auth,redirect,response}.php` are **dead code** — never `require`d anywhere. Don't assume new auth flows through them.
- Views are plain PHP resolved by `view('a.b.c')` → `app/Views/a/b/c.php` (dots become slashes, `functions.php:3`). Portal pages emit `view('layouts.header')` + `view('portal.x')` + `view('layouts.footer')` manually; admin/siswa pages are single self-contained files with their own layout.
- `admin/layouts/` and `admin/partials/` exist separately from `layouts/` and `partials/`.

## Auth: two disjoint systems

- **Students** — tables `users` + `siswa`, session key `user_id` (helpers `isLoggedIn()` / `userId()`). `SiswaController` guards in its constructor; `AuthController` guards per-method.
- **Admins** — table `admin` (not `users`), session keys `admin_id`, `admin_role` (`super_admin` | `school_admin`), `admin_sekolah_id`.
- **Nothing enforces admin auth globally.** `AdminController::checkAdmin()` / `checkSuperAdmin()` must be called manually at the top of every new admin action.
- **Tenant scoping is manual too.** Each admin action repeats `$sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];` plus an explicit ownership re-check before reading/writing a record. Copy that pattern; skipping it leaks cross-school applicant data.
- `csrf_token()` / `csrf_field()` exist in `functions.php` but are never emitted or verified on any form.

## Post-write cache invalidation

`writable/cache/last_update.txt` holds a timestamp that the browser polls every 30s. After any admin write, call `AdminController::invalidateCache()` (`app/Controllers/AdminController.php:36`) or the UI won't notify. It is served by `HomeController::checkUpdates()`.

## Domain rules (Juknis 2025) — don't change silently

- Age eligibility is validated against a **fixed reference date of 1 Juli 2025**, not `date('Y')`.
- Zonasi ranking priority: **average rapor (`nilai_sem1..5`) DESC → distance ASC → oldest first**. Prestasi: skor DESC → distance → age. Afirmasi/mutasi: distance → age. `app/Services/SelectionEngine.php::getRankedList()` is the ranking authority; `Pendaftaran::getRankedList()` is a separate display copy that has drifted.
- Leftover quota from afirmasi/mutasi/prestasi overflows into zonasi (`SelectionEngine::processSchool()`).
- Students may submit **one registration per jalur** across 4 jalur (zonasi, afirmasi, prestasi, mutasi); re-submitting a jalur updates the existing `siswa` row.

## Repo hygiene

- The working tree is **not clean** — dozens of modified + untracked files on branch `master`. Check `git status` before assuming anything; don't try to "clean up" unrelated changes.
- Root is littered with ad-hoc debug scripts (`check_*.php`, `debug_*.php`, `update_db*.php`, `migrate_*.php`). `public/` contains **web-reachable, unauthenticated scratch pages** that hit the DB (`demo_*.php`, `test_*.php`, `force_diterima.php`). They are not part of the app — never add new ones under `public/`, and treat these as a pre-existing exposure.
- `check_sekolah_columns.php` is broken: it requires `app/Config/Database.php`, which does not exist (the real file is `config/database.php`).
- `daftar_akun_sekolah.txt` at the repo root is a **plaintext school-admin credential dump**. Never commit or paste it.
- `public/uploads/{sekolah,foto,foto_siswa,dokumen,documents}/` holds user-uploaded files. `.gitignore` ignores `uploads/*` but *not* `public/uploads/*`, so real uploads are staged-able — check `git status` before committing.
- `debug_log.txt` and `dashboard_notification_snippet.php` are scratch output/paste-in code, not application files.

## Conventions

- All DB access uses PDO prepared statements with `?` placeholders; escape output with `e()`.
- Flash messages: `$_SESSION['success'] | ['error'] | ['warning'] | ['info']` (note `auth.php`'s `setFlash`/`getFlash` are unused dead code).
- Tables/columns are snake_case; models are PascalCase files holding one class with no namespace.
- In views, never reach into `global` scope for a variable local to the included file — `view()` uses `extract()` + `require`, so `$currentPath` is file-local and `global $currentPath` silently yields `null` (this bug shipped in `layouts/header.php` and broke every nav active-state).
