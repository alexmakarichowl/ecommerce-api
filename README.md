# E-commerce API

Simple REST API for e-commerce platform built with PHP and MySQL.

## Stack
- PHP 8+
- MySQL
- JWT authentication
- JSON API

## Setup

1. Copy `.env.example` to `.env`
2. Run `composer install`
3. Import database: `mysql -u root -p < database.sql`
4. Configure `.env` with your database credentials
5. Start server: `php -S localhost:8000 -t public`

## API Endpoints

### Public
- `GET /` - API info
- `GET /products` - List all products
- `POST /register` - Register user
- `POST /login` - Login user

### Protected (need Bearer token)
- `POST /products` - Create product

## Example requests

Register:
```bash
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"pass123"}'
```

Login:
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"pass123"}'
```

Create product (with token):
```bash
curl -X POST http://localhost:8000/products \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"name":"Product","price":99.99}'
```
