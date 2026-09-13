# PrepareMe.com — Bangladesh Job-Preparation & Study Digitization Platform

**PrepareMe.com** is a modern, full-stack, production-grade examination preparation platform tailored specifically for Bangladeshi competitive job aspirants—including BCS (Bangladesh Civil Service), Government & Private Bank, Primary Assistant Teacher, NTRCA, and BPSC candidates.

The platform couples an open, structured curriculum with a strictly private **Personal Study Notebook** and a background **OCR Digitization Studio** that enables candidates to capture photos of physical study books, extract bilingual text (Bangla & English), review and refine the output, and convert it directly into self-test study questions.

---

## Architecture Overview

PrepareMe.com follows a decoupled RESTful architecture:

```
prepareme.com/
├── prepareme-api/         # Laravel 12 REST API (/api/v1)
│   ├── app/
│   │   ├── Enums/         # 9 Typed Domain Enums
│   │   ├── Http/          # Controllers, Requests, Resources, Middleware
│   │   ├── Jobs/          # Asynchronous OCR Queue Job
│   │   ├── Models/        # Eloquent Models & Relationships
│   │   ├── Policies/      # Authorization & Zero-IDOR Policies
│   │   └── Services/OCR/  # OCR Manager & Multi-driver Engines
│   ├── database/          # 15 Migrations & Comprehensive Seeders
│   └── tests/             # Automated Feature Test Suite (26 tests, 91 assertions)
├── prepareme-web/         # React 19 SPA (Vite + Bootstrap 5)
│   ├── src/
│   │   ├── api/           # Axios Client with Bearer Token Interceptors
│   │   ├── components/    # Reusable UI, Badges, Modals, Pagination
│   │   ├── context/       # AuthContext & Session Management
│   │   ├── layouts/       # Public/User (AppLayout) & AdminLayout
│   │   ├── pages/         # Public, Auth, User Notebook, and Admin Pages
│   │   ├── routes/        # Route Guards (AdminRoute, UserRoute, GuestRoute)
│   │   └── styles/        # Bengali typography & Custom Theme
└── docs/                  # In-depth System Specifications
    ├── architecture.md    # High-level architecture, flows & security
    ├── database.md        # Database schema, ER diagram & indexing
    ├── api.md             # Complete REST API reference & examples
    └── deployment.md      # Production deployment, Nginx, queue & supervisor
```

---

## Key Features

### 1. Public Learning & Curriculum
* **Subjects & Topics:** Hierarchical navigation across Bangla Language & Literature, English, Mathematics, Bangladesh Affairs, International Affairs, General Science, and Computer & ICT.
* **Structured Study Guides:** Composed of flexible, ordered sections (Text, Key Points, Examples, Warnings, Formulas, and FAQs).
* **Interactive Question Bank:** Multiple Choice Questions (MCQ) with instant answer checking, option highlighting, and detailed contextual explanations.
* **Bookmark & Progress Tracking:** Authenticated candidates can bookmark guides and mark their reading status (`started`, `completed`).

### 2. Private Personal Study Notebook
* **Zero-IDOR Security:** Personal questions and answers belong strictly to the authenticated user. Admins or other users have zero access to private study records.
* **Question Authoring:** Rich text inputs for questions, answers, explanations, tags, and study guide linkage.
* **Organization:** Tagging, keyword search, active vs. archived question filtering.

### 3. OCR Digitization Studio
* **Book Page Digitization:** Upload JPEG, PNG, or WebP images of physical textbook pages (up to 10MB).
* **Asynchronous Queue Processing:** Background job handling prevents HTTP timeouts on large image analysis.
* **Bangla & English Support:** Language models tuned for Bengali (`ben`), English (`eng`), and bilingual mixtures (`ben+eng`).
* **Dev Fallback Engine:** Built-in intelligent OCR fallback for local development without external dependencies or cloud credentials.
* **Review & Conversion Workflow:** Uploaded scans never auto-publish. Users inspect side-by-side raw text and confident score, make edits, and save snippets into their private notebook in one click.

### 4. Admin Management Console
* **User Management:** Audit registered accounts, toggle active/suspended statuses.
* **Curriculum Control:** Full CRUD over subjects, topics, and study guides with dynamic drag/drop reordering of content blocks.
* **Question Bank Management:** Author and manage public MCQs with 4-choice builder and difficulty tags.
* **OCR Monitoring:** Audit OCR jobs across the system, review processing times, confidence metrics, and failure diagnostics.

---

## Technology Stack

| Layer | Technologies |
|---|---|
| **Backend API** | PHP 8.2+, Laravel 12, Laravel Sanctum, Intervention Image |
| **Database** | MySQL 8.0+ (InnoDB, UTF8mb4) |
| **Queue & Cache** | Database / Redis queue worker |
| **OCR Pipeline** | Multi-driver architecture (Tesseract CLI / Google Vision ready / Dev Mock driver) |
| **Frontend Web** | React 19, Vite, Bootstrap 5.3, Bootstrap Icons, Axios, React Router v7 |
| **Typography** | Hind Siliguri, Kalpurush (Bengali typography) & Inter (English/Numerics) |

---

## Getting Started (Local Development)

### Prerequisites
* PHP 8.2 or higher with `pdo_mysql`, `mbstring`, `fileinfo`, `gd` or `imagick` extensions.
* Composer 2.x
* MySQL 8.0 or MariaDB 10.5+
* Node.js 18+ and npm

---

### 1. Backend Setup (`prepareme-api`)

1. Open terminal in `prepareme-api`:
   ```bash
   cd C:\laragon\www\prepareme.com\prepareme-api
   ```
2. Copy environment variables:
   ```bash
   cp .env.example .env
   ```
3. Configure MySQL credentials in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prepareme_db
   DB_USERNAME=root
   DB_PASSWORD=

   OCR_DRIVER=dev
   QUEUE_CONNECTION=database
   ```
4. Run migrations and database seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Start the backend development server:
   ```bash
   php artisan serve --port=8000
   ```
   *The API will be accessible at `http://127.0.0.1:8000/api/v1`.*

6. In a separate terminal, start the queue worker for OCR processing:
   ```bash
   php artisan queue:work --tries=3 --timeout=120
   ```

---

### 2. Frontend Setup (`prepareme-web`)

1. Open terminal in `prepareme-web`:
   ```bash
   cd C:\laragon\www\prepareme.com\prepareme-web
   ```
2. Install dependencies (if not already installed):
   ```bash
   npm install
   ```
3. Start the Vite development server:
   ```bash
   npm run dev
   ```
   *The web client will be available at `http://localhost:5173`.*

4. To create an optimized production build:
   ```bash
   npm run build
   ```

---

## Seed Accounts & Credentials

The database seeder provisions standard test accounts:

| Role | Email | Password | Access Area |
|---|---|---|---|
| **Administrator** | `admin@prepareme.com` | `Password123!` | Admin Back-Office & Public Learning |
| **Candidate 1** | `user1@prepareme.com` | `Password123!` | Personal Notebook, OCR Studio & Progress |
| **Candidate 2** | `user2@prepareme.com` | `Password123!` | Isolated User Sandbox (tests privacy) |

---

## Running Automated Tests

The test suite covers Authentication, Admin RBAC, Curriculum delivery, Personal Notebook privacy, and the end-to-end OCR pipeline.

```bash
cd C:\laragon\www\prepareme.com\prepareme-api
php artisan test
```

**Expected output:**
```
Tests:    26 passed (91 assertions)
Duration: 1.76s
```

---

## Documentation Index

Detailed engineering and deployment guides are available in the [`docs/`](./docs) folder:
* **[Architecture & Security](docs/architecture.md):** Detailed system design, data flows, and zero-IDOR policies.
* **[Database Design](docs/database.md):** Complete entity-relationship diagram, indexing strategies, and table definitions.
* **[REST API Specification](docs/api.md):** Full documentation for all 63 API endpoints with sample payloads.
* **[Deployment Guide](docs/deployment.md):** Instructions for production hosting on Ubuntu / Nginx with Supervisor and SSL.

---

## License & Copyright

Developed for Bangladeshi job aspirants. Open for educational and institutional deployment.
