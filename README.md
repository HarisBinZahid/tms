
# 🈂️ Translation Management System API — Laravel (Code Test for DigitalTolk)

This is a Laravel-based Translation Management System API to store, manage, and retrieve translation strings for multiple locales. It supports context-based tagging, JSON export, and scalable querying, following best practices in performance, security, and code quality.

---

## 🚀 Tech Stack

- Laravel 12
- Sanctum (API Authentication)
- MySQL (Testing)
- L5-Swagger (OpenAPI Docs)
- PHPUnit (Testing)
- PSR-12 / SOLID compliant

---

## 🛠️ Setup Instructions

### 1. Clone & Install Dependencies

```bash
git clone https://github.com/HarisBinZahid/tms.git
cd tms
composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` database credentials:
```env
DB_CONNECTION=mysql
DB_DATABASE=tms
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Run Migrations & Seeder (for 100k+ test records)

```bash
php artisan migrate --seed
```

### 4. Serve the Application

```bash
php artisan serve
```

---

## 🔐 Authentication (Sanctum)

Use `POST /api/login` to receive a token:

```json
{
  "email": "test@example.com",
  "password": "password"
}
```

Use this token in the Authorization header:

```http
Authorization: Bearer {token}
```

---

## 📦 API Endpoints

| Method | Endpoint                      | Description                         |
|--------|-------------------------------|-------------------------------------|
| POST   | /api/login                    | Authenticate and get token          |
| GET    | /api/translations             | Paginated list of translations      |
| POST   | /api/translations             | Create new translation              |
| GET    | /api/translations/search      | Search by key, tag, or content      |
| GET    | /api/translations/export      | Export translations by locale (JSON)|
| GET    | /api/translations/{id}        | Show specific translation           |
| PUT    | /api/translations/{id}        | Update translation content/tag      |
| DELETE | /api/translations/{id}        | Delete translation                  |

---

## 📚 API Documentation

Swagger UI available at:

```
http://localhost:8000/api/documentation
```

OpenAPI File:
- [openapi_translation_api.yaml](openapi_translation_api.yaml)
---

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

Includes:
- Feature tests for API CRUD, search, export
- Performance test (< 500ms export for 10k+ records)
- Unit tests for model logic

---

## 🧠 Design Decisions

- **PSR-12 + SOLID Principles**: Controllers are lean, business logic handled via model.
- **Sanctum**: Simple and secure token-based auth.
- **Scalability**:
  - Indexed DB columns
  - JSON export response cached via Laravel Cache
  - Seeder to test with 100k+ rows
- **Swagger/OpenAPI**: Annotated endpoints for frontend/dev team usage.
- **Performance**: JSON export benchmarked with real-time stopwatch and caching for <500ms delivery.

---

## 🧩 Future Improvements

- Redis cache support for horizontal scaling
- CDN integration for export payloads
- Admin panel for manual translation editing
- Rate limiting and IP throttling

---

## 👤 Author

**Haris Bin Zahid**  
Senior Software Engineer | Laravel Expert  
