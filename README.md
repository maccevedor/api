# Enterprise Subscription API

This project is a Laravel-based REST API for managing companies (tenants), subscription plans, enterprise users (clients), and subscription history, following Domain-Driven Design (DDD) and Hexagonal Architecture principles.

## Features
- Companies (tenants) with single active plan and subscription history
- Subscription plans with features and user limits
- Enterprise users (clients) per company, managed by plan restrictions
- Full CRUD for companies, plans, and clients
- Subscription management (subscribe, cancel, list history)
- OpenAPI (Swagger) documentation
- Pest tests for all endpoints

## Project Structure
- **Domain Layer**: Entities, Value Objects, Repositories
- **Application Layer**: Services, DTOs, Interfaces
- **Infrastructure Layer**: Eloquent Repositories, Models
- **Presentation Layer**: Controllers, Requests, API routes

## Setup & Usage

### 1. Install dependencies
```bash
composer install
```

### 2. Configure environment
Copy `.env.example` to `.env` and set your database credentials.

### 3. Run database migrations
```bash
php artisan migrate
```

### 4. (Optional) Seed the database
```bash
php artisan db:seed
```

### 5. Run the development server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
The API will be available at `http://localhost:8000/api`.

### 6. Generate OpenAPI documentation
```bash
php artisan l5-swagger:generate
```
View the docs at: `http://localhost:8000/api/documentation`

### 7. Run tests
```bash
./vendor/bin/pest
```

## API Endpoints

### Plans
- `GET /api/plans` — List all plans
- `POST /api/plans` — Create a plan
- `GET /api/plans/{id}` — Get plan details
- `PUT /api/plans/{id}` — Update a plan
- `DELETE /api/plans/{id}` — Delete a plan

### Companies
- `GET /api/companies` — List all companies
- `POST /api/companies` — Create a company
- `GET /api/companies/{id}` — Get company details
- `PUT /api/companies/{id}` — Update a company
- `DELETE /api/companies/{id}` — Delete a company
- `POST /api/companies/{id}/subscribe` — Subscribe to a plan
- `POST /api/companies/{id}/cancel-subscription` — Cancel subscription
- `GET /api/companies/{id}/subscriptions` — List all subscriptions (history)

### Clients (Enterprise Users)
- `GET /api/clients` — List all clients
- `POST /api/clients` — Create a client
- `GET /api/clients/{id}` — Get client details
- `PUT /api/clients/{id}` — Update a client
- `DELETE /api/clients/{id}` — Delete a client
- `POST /api/clients/login` — Client login

## Documentation
- OpenAPI docs: `/api/documentation`
- All endpoints are documented with request/response schemas and examples.

## Development Steps Recap
1. **Set up DDD structure**: Domain, Application, Infrastructure, Presentation layers
2. **Created entities, value objects, repositories, DTOs, and services**
3. **Implemented Eloquent models and repositories**
4. **Created controllers and request validation classes**
5. **Added OpenAPI (Swagger) documentation and configuration**
6. **Added Pest tests and model factories**
7. **Configured and generated API documentation**
8. **Tested endpoints and fixed linter errors**
9. **Documented all endpoints and usage in this README**

---

For any questions or contributions, please open an issue or submit a pull request.
