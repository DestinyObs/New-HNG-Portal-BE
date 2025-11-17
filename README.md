# HNG Portal Backend

A backend job recruitment portal built with Laravel.

## About

HNG Portal is a job recruitment platform designed to connect employers with qualified candidates. This repository contains the backend API and business logic for the portal.

## Design

View the project design: [Figma Design Link](https://www.figma.com/design/if8xL6EKZ3qvyYX1Sno0pX/HNG-Portal?node-id=376-5142&t=2tpGBukRmYe0GeTN-1)

## Prerequisites

- PHP 8.2+
- Composer
- Laravel 12
- MySQL

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Configure your database in `.env`
6. Run migrations: `php artisan migrate`
7. Start the development server: `php artisan serve`

## Project Structure

- `/app` - Application logic and models
    - `/Services` - Core business logic and service classes
    - `/Repositories` - Data access layer for interacting with the database
    - `/Http` - Controllers and request handling
- `/routes` - API routes
- `/database` - Migrations and seeders
- `/tests` - Test suite

### Architecture Design Pattern

This project organizes code into clear layers to keep responsibilities separated and make the codebase easier to maintain and test:

- Controllers: handle incoming requests (HTTP/CLI), map inputs to application calls, and return responses.
- Services: contain business rules and orchestration logic; they are the primary place for core use-cases.
- Repositories: encapsulate persistence (database, external APIs); services depend on repository interfaces, not implementations.
- Interfaces / Contracts: define the boundaries between layers so implementations can be swapped (useful for testing and incremental refactors).
- Models / DTOs: represent domain entities and data transfer objects used across layers.
