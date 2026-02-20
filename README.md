# Online Examination System (PHP + JavaScript + MySQL)

A complete multi-role web application implementing:
- **Admin module**: secure login, student/teacher CRUD, exam creation, assignment, reporting, monitoring.
- **Teacher module**: secure login, create papers, add MCQ/True-False questions, set exam params, performance view.
- **Student module**: registration/login, available exams, timed attempts, submission, instant score.

## Team split into 4 branches
Use separate branches for a 4-person workflow:
1. `person-1-admin-module`
2. `person-2-teacher-module`
3. `person-3-student-module`
4. `person-4-auth-backend`

Suggested ownership:
- **Person 1**: admin views and CRUD/report/monitoring UX.
- **Person 2**: teacher question-bank + paper building features.
- **Person 3**: student dashboard, exam attempt, timer UX.
- **Person 4**: authentication, role middleware, repository/data-flow, DB schema.

## Setup
1. Import database schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Configure env vars if needed (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `APP_URL`).
3. Run app:
   ```bash
   php -S 0.0.0.0:8000 -t public
   ```
4. Open `http://localhost:8000/index.php?page=home`.

Admin seed:
- Email: `admin@exam.com`
- Password: `admin123`

## Architecture
- `public/index.php`: front controller + routing.
- `app/helpers.php`: config, db, auth guards, rendering.
- `app/models/repository.php`: backend data-flow functions.
- `app/views/*`: role-based UI modules.
- `assets/js/app.js`: timer + anti-cheat tab-switch event logging.
- `database/schema.sql`: MySQL tables and seed.

## Dedicated backend branch and structure
A dedicated backend branch has been created for API-first operations:
- Branch: `backend-separate-ops`

New backend structure:
- `backend/config`: DB and constants
- `backend/middleware`: auth + role checks
- `backend/services`: AuthService, ExamService, ResultService
- `backend/api`: auth/admin/staff/student operation handlers
- `backend/database/schema.sql`: backend schema bootstrap
