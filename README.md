# GMSAMS

Grade Management System and Academic Monitoring System — ATEC Technological College Apalit, Inc. (Senior High School).

## Requirements

- PHP **8.2+**
- Composer 2.x
- MySQL 8.0+
- Redis (cache, queues, Horizon)

## Quick start (backend)

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Configure `.env` for MySQL and Redis. For SPA auth, set `SANCTUM_STATEFUL_DOMAINS` and `FRONTEND_URL` (see `.env.example`).

## Project layout

| Path | Stack |
|------|--------|
| `backend/` | Laravel 11 API (Sanctum, Horizon) |
| `frontend/` | React 18 + Vite (added in later phases) |

## Phase 1 (current)

- 37-table schema migrations
- Seeders: roles, grade_levels, assessment_categories, strands
- Sanctum login, lockout, OTP password reset
- Role middleware and `/api` route groups

After `composer install`, run migrations. Frontend setup follows in a later phase.

Or run `.\backend\scripts\install.ps1` once PHP 8.2+ is on your PATH.

## Auth API (Phase 1)

| Method | Endpoint | Auth |
|--------|----------|------|
| POST | `/api/auth/login` | Public |
| POST | `/api/auth/forgot-password` | Public |
| POST | `/api/auth/reset-password` | Public |
| POST | `/api/auth/resend-otp` | Public |
| POST | `/api/auth/logout` | Sanctum + session |
| GET | `/api/auth/me` | Sanctum + session |

## Admin API (Phase 2)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/dashboard` | Stats + activity feed |
| GET | `/api/admin/reference` | Dropdown data (grade levels, strands, etc.) |
| GET | `/api/admin/users` | List accounts (paginated) |
| POST | `/api/admin/users/students` | Create student |
| POST | `/api/admin/users/teachers` | Create teacher |
| POST | `/api/admin/users/head-teachers` | Create head teacher |
| PUT | `/api/admin/users/{id}/student` | Update student |
| PUT | `/api/admin/users/{id}/teacher` | Update teacher |
| DELETE | `/api/admin/users/{id}` | Soft-delete account |
| GET | `/api/admin/subjects` | List subjects |
| POST | `/api/admin/subjects` | Create subject + auto-assign sections |
| PUT | `/api/admin/subjects/{id}` | Update subject |
| DELETE | `/api/admin/subjects/{id}` | Soft-delete subject |
| POST | `/api/admin/subjects/bulk` | Bulk hide/unhide/delete |

## Frontend

```bash
cd frontend
npm install
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
npm run dev

```

Admin routes: `/admin/dashboard`, `/admin/accounts`, `/admin/subjects`.

**Dev admin** (after `AdminUserSeeder`): username `admin`, password `Admin@123`.

## XAMPP note

XAMPP ships PHP 8.0; Laravel 11 requires **PHP 8.2+**. Upgrade PHP or use Laragon 8.2 before `composer install`.
