## General

- Use Eloquent relationships
- Prefer readability over query optimization (until needed)
- Use Form Requests for validation
- Use Policies for authorization

## Business Logic

- Always use Service classes
- Never place business logic in controllers
- Avoid static helpers for core logic

## Database

- Use migrations properly
- Keep naming consistent
- Prefer simple schemas over premature optimization
- Always use uuids as primary keys
- Set cascade on delete when needed

## API

- Return consistent JSON structures
- Use resources/transformers when needed