# Document Validator

A small PHP 8.3 CLI component for validating tenant-specific documents. Each
tenant can configure rules such as maximum document size, required metadata
fields, and prohibited words.

## Requirements

- Docker
- Docker Compose

## Installation

```bash
docker compose run --rm app composer install
```

## Run the Demo

```bash
docker compose run --rm app php demo.php
```

## Run the Tests

```bash
docker compose run --rm app vendor/bin/phpunit
```

## Project Structure

```text
.
├── src/
│   ├── Document.php
│   └── Validation/
│       ├── DocumentValidator.php
│       ├── TenantValidationRuleResolver.php
│       ├── ValidationResult.php
│       ├── ValidationRuleInterface.php
│       └── Rules/
├── tests/
│   └── Validation/
├── demo.php
├── Dockerfile
└── docker-compose.yml
```

## Design

`DocumentValidator` depends only on `ValidationRuleInterface`, so new rules can
be added without changing validator logic (Open/Closed Principle). It executes
all configured rules and returns a `ValidationResult` containing all collected
validation errors.

`TenantValidationRuleResolver` determines which rules apply to a tenant. This
keeps tenant configuration separate from the validation process and makes the
solution easy to extend.
