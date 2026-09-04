# Mr. Baker E-commerce Platform

A modern e-commerce platform built with Laravel, featuring a robust API and admin panel.

## Features

- User authentication and authorization
- Product management
- Category management
- Order processing
- Admin dashboard
- RESTful API
- Caching system
- Error handling
- API documentation

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js >= 16
- NPM >= 8

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/mrbaker.git
cd mrbaker
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mrbaker
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Start the development server (Laravel and Vite together):
```bash
npm run dev
```
The application is available at `http://127.0.0.1:8000`. Stop both development
processes with `Ctrl+C`.

To build frontend assets for production:
```bash
npm run build
```

## API Documentation

The API documentation is available at `/api/documentation` after running:
```bash
php artisan l5-swagger:generate
```

## Testing

Run the test suite:
```bash
php artisan test
```

## Caching

The application uses Laravel's caching system. Configure your cache driver in `.env`:
```
CACHE_DRIVER=redis
```

## Error Handling

Custom error handling is implemented for both web and API responses. API errors follow a consistent format:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {} // Optional validation errors
}
```

## Performance Optimization

- Repository pattern with caching
- Query optimization with eager loading
- Database indexing
- Asset compilation and minification
- Cache invalidation strategies

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


# Refactoring Plan

## Phase 1: Core Infrastructure
1. Set up proper service providers
   - [x] RepositoryServiceProvider
   - [x] EventServiceProvider
   - [x] RouteServiceProvider
   - [x] AuthServiceProvider

2. Implement base classes and interfaces
   - [x] RepositoryInterface
   - [x] EloquentRepository
   - [x] BaseService
   - [x] ImageUploadService

## Phase 2: Category Module (Current Focus)
1. Models and Migrations
   - [x] Category model
   - [x] Categories migration
   - [x] Category factory
   - [x] Category seeder

2. Repositories
   - [x] CategoryRepositoryInterface
   - [x] CategoryRepository implementation

3. Services
   - [x] CategoryService
   - [x] ImageUploadService

4. Controllers and Requests
   - [x] CategoryController (Resource)
   - [x] CategoryRequest

5. Policies and Authorization
   - [x] CategoryPolicy
   - [x] Spatie permissions integration

6. Views
   - [x] Index view
   - [x] Create view
   - [x] Edit view

7. Tests
   - [x] Unit tests for CategoryService
   - [x] Feature tests for CategoryController

## Phase 3: Additional Modules (Next Steps)
1. Product Module
   - [ ] Product model and migration
   - [ ] Product repositories
   - [ ] Product service
   - [ ] Product controller
   - [ ] Product views
   - [ ] Product tests

2. User Management
   - [ ] User profile management
   - [ ] Role and permission management
   - [ ] User activity logging

3. API Endpoints
   - [ ] Category API endpoints
   - [ ] Product API endpoints
   - [ ] API authentication
   - [ ] API documentation

## Phase 4: Optimization and Enhancement
1. Caching Strategy
   - [ ] Repository caching
   - [ ] Query optimization
   - [ ] Cache invalidation

2. Error Handling
   - [ ] Custom exception handlers
   - [ ] Error logging
   - [ ] User-friendly error pages

3. Documentation
   - [ ] API documentation
   - [ ] Code documentation
   - [ ] Setup instructions

4. Performance
   - [ ] Query optimization
   - [ ] Eager loading
   - [ ] Index optimization
