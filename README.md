# Payment Gateway Integration API

Test task implementation for multiple payment provider integrations using Laravel 13 and PHP 8.4.

## Stack

- PHP 8.4
- Laravel 13
- Docker / Docker Compose
- MySQL
- PHPUnit

---

# Features

- Unified payment creation API
- Multiple payment providers support
- Provider-specific callback handling
- Normalized internal payment statuses
- Extensible provider architecture
- Integration tests
- Fake external provider APIs

---

# Architecture

The application uses provider abstraction through `PaymentProviderContract`.

Each provider is responsible for:
- request mapping,
- callback validation,
- status normalization,
- external API contract handling.

Main orchestration logic is isolated in `PaymentService`.

Provider selection is implemented using `PaymentProviderResolver`.

This allows adding new providers without modifying the main payment flow.

---

# Installation

## Clone repository

```bash
git clone <repository-url>
```

---

## Start project

```bash
make install
```

---

# Available Commands

## Start containers

```bash
make up
```

## Stop containers

```bash
make down
```

## Run migrations

```bash
make migrate
```

## Run tests

```bash
make tests
```

## Run Pint

```bash
make psr
```

---

# Testing

The project uses:
- SQLite in-memory database for tests
- integration tests
- RefreshDatabase trait

Run tests:

```bash
make tests
```

---

# Notes

- External provider APIs are simulated using fake HTTP responses.
- PayGateB converts amount to cents before request.
- Payment statuses are normalized internally using enums.