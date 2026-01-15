# Book Library (Web2)

A simple PHP + MySQL library storefront demo using Bootstrap 5.

## Requirements
- PHP 8.0+ (works with 7.4+, recommended 8+)
- MySQL 8 (or compatible)

## Quick start
1. Create database and tables:
   - Import database/db_quanlythuvien.sql into MySQL.
2. Configure DB credentials:
   - Copy `.env.example` to `.env` and adjust values.
   - Or set environment variables: DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS.
3. Run the site locally:
   - Windows (PowerShell): `php -S localhost:8000 -t d:/qlthuvien/Web2`
   - Then open http://localhost:8000/

## Environment variables
- APP_ENV=development (show errors) | production (hide errors)
- APP_TIMEZONE=Asia/Ho_Chi_Minh
- DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS, DB_CHARSET

## Structure
- index.php: App bootstrap + router
- layout/*.php: Page partials (included by index.php)
- assets/css/site.css: Global styles
- assets/js/site.js: Small UI helpers
- database/DBConnection.php: PDO singleton + helpers

## Routes (query param `page`)
- /index.php: Home
- /index.php?page=search: Search books by title/author
- /index.php?page=sanpham&phanloai=<category_id>: Books in a category
- /index.php?page=sanpham&id=<book_id>: Book detail
- /index.php?page=giohang: Cart (placeholder)
- /index.php?page=pay: Checkout (placeholder)

## Notes
- Partials guard against direct access and should only be included via index.php.
- If DB is unavailable, pages fall back gracefully with placeholder data/messages.