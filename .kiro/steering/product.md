# Product Overview

This is a small MVP of an ERP e-commerce system. The application is built in Brazilian Portuguese (UI text, validation messages, and flash messages are in pt-BR).

## Core Domain

- **Products**: Catalog management with name, SKU (auto-generated), price, description, sizes (S/M/L/XL), and stock level. Products support soft deletes.
- **Product Images**: Each product can have multiple images, with one designated as primary. Description images are stored separately for rich content.
- **Stock Movements**: Audited stock adjustments tracked per product and user, with a reason and before/after balance.
- **Users**: Role-based access with four roles — Admin, Operator, Seller, and Accountant.

## Access Control

- **Admin**: Full access to everything (bypasses all policy checks via `before()`).
- **Operator**: Can create, update, and delete products and manage stock.
- **Seller**: Can create,update, and delete sells, it can generate payments.
- **Accountant**: Can generate NF-e receives, review sales statistics and accounting data.

## Key Business Rules

- SKU is auto-generated from the first 3 characters of the product name (uppercased, ASCII-normalized) plus a random 6-character suffix (e.g., `CAF-12345`).
- Stock changes are always recorded as `StockMovement` entries — stock is never updated directly without an audit record.
- Product images are deleted asynchronously via a queued job when a product is force-deleted.
