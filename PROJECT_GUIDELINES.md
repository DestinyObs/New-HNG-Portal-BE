# HNG Portal Backend - Project Guidelines & Rules

**This document contains ALL rules, conventions, and patterns that MUST be followed when working on this codebase. Always reference this file before making any changes.**

---

## Table of Contents

1. [Architecture & Design Patterns](#architecture--design-patterns)
2. [Naming Conventions](#naming-conventions)
3. [API Response Format](#api-response-format)
4. [Request Validation Pattern](#request-validation-pattern)
5. [Dependency Injection Pattern](#dependency-injection-pattern)
6. [Routes Organization](#routes-organization)
7. [Models Pattern](#models-pattern)
8. [Services Pattern](#services-pattern)
9. [Repositories Pattern](#repositories-pattern)
10. [Code Quality Standards](#code-quality-standards)
11. [File Structure & Organization](#file-structure--organization)
12. [Key Rules Checklist](#key-rules-checklist)
13. [Examples & Templates](#examples--templates)

---

## Architecture & Design Patterns

### Layered Architecture (Repository-Service-Controller)

This project follows a strict layered architecture pattern:

```
HTTP Request
    ↓
Controller (app/Http/Controllers/)
    ↓
Service (app/Services/)
    ↓
Repository (app/Repositories/)
    ↓
Model (app/Models/)
    ↓
Database
```

**Responsibilities:**

1. **Controllers** - Handle HTTP requests/responses, route to services
2. **Services** - Business logic and orchestration
3. **Repositories** - Data access layer (database operations)
4. **Interfaces** - Contracts for Services and Repositories (dependency injection)
5. **Models** - Domain entities and relationships

**CRITICAL RULES:**
- ❌ **NEVER** put business logic in Controllers
- ❌ **NEVER** query models directly from Services (use Repositories)
- ❌ **NEVER** put database queries in Controllers
- ✅ **ALWAYS** use Services for business logic
- ✅ **ALWAYS** use Repositories for data access
- ✅ **ALWAYS** use Interfaces for dependency injection

---

## Naming Conventions

### Controllers

**Pattern:** `{Resource}Controller`

**Examples:**
- `UserController`
- `WaitlistController`
- `JobListingController`

**Grouped Controllers:**
- `Auth/LoginController`
- `Auth/ForgotPasswordController`
- `Admin/TestController`
- `Employer/TestController`
- `Talent/TestController`

**Single-Action Controllers:**
- Use `__invoke()` method (e.g., `LoginController`)

**Location:** `app/Http/Controllers/`

**Base Controller:**
- All controllers extend `App\Http\Controllers\Controller`
- Base controller uses `ApiResponse` trait

### Services

**Pattern:** `{Resource}Service`

**Examples:**
- `UserService`
- `WaitlistService`
- `LoginService` (in `Auth/` subdirectory)

**Location:** `app/Services/`

**Grouped Services:**
- `Auth/LoginService`
- `Auth/PasswordResetService`
- `Auth/GoogleAuthService`

**Interface Requirement:**
- Must implement corresponding interface: `{Resource}Interface`
- Interface location: `app/Services/Interfaces/`

**Examples:**
- `UserService` implements `UserInterface`
- `LoginService` implements `LoginInterface` (in `Auth/` subdirectory)

### Repositories

**Pattern:** `{Resource}Repository`

**Examples:**
- `UserRepository`
- `CompanyRepository`

**Location:** `app/Repositories/`

**Requirements:**
- Must extend `BaseRepository`
- Must implement `{Resource}RepositoryInterface`
- Interface location: `app/Repositories/Interfaces/`

### Requests (Form Validation)

**Pattern:** `{Action}{Resource}Request` or `{Resource}Request`

**Examples:**
- `LoginRequest`
- `ForgotPasswordRequest`
- `ResetPasswordRequest`
- `UserRequest`
- `WaitlistRequest`

**Grouped Requests:**
- `Auth/LoginRequest`
- `Auth/ForgotPasswordRequest`
- `Auth/ResetPasswordRequest`

**Location:** `app/Http/Requests/`

### Models

**Pattern:** Singular PascalCase

**Examples:**
- `User`
- `Company`
- `JobListing`
- `TalentWorkExperience`

**Location:** `app/Models/`

**Traits Location:** `app/Models/Concerns/Traits/`

### Enums

**Pattern:** PascalCase

**Examples:**
- `Http`
- `Status`
- `RoleEnum`
- `PermissionEnum`

**Location:** `app/Enums/`

**Usage:**
- Always use enum cases (e.g., `Http::OK`, `Status::ACTIVE`)
- Never hardcode enum values

---

## API Response Format

### ApiResponse Trait

**Location:** `app/Http/Controllers/Concerns/ApiResponse.php`

**Usage:**
- All controllers extend `Controller` which uses `ApiResponse` trait
- All responses MUST use these methods

### Available Methods

#### Success Responses

```php
// Simple success
$this->success('Operation successful', Http::OK);

// Success with data
$this->successWithData($data, 'Data retrieved successfully');

// Created (201)
$this->created('Resource created successfully', $data);

// Paginated response
$this->paginated($paginator, 'Data retrieved successfully');
```

#### Error Responses

```php
// Generic error
$this->error('Error message', Http::BAD_REQUEST);

// Not found (404)
$this->notFound('Resource not found');

// Unauthorized (401)
$this->unauthorized('Unauthorized access');

// Forbidden (403)
$this->forbidden('Access forbidden');

// Validation error (422)
$this->unprocessable('Validation failed', $errors);

// No content (204)
$this->noContent();
```

### Response Structure

**Success Response:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {...},  // optional
  "status": 200
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "status": 400,
  "errors": {...}  // optional, for validation errors
}
```

**Paginated Response:**
```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "last_page": 7,
    "from": 1,
    "to": 15,
    "has_more_pages": true
  },
  "status": 200
}
```

### HTTP Status Codes

**CRITICAL:** Always use `App\Enums\Http` enum

**Examples:**
- `Http::OK` (200)
- `Http::CREATED` (201)
- `Http::NO_CONTENT` (204)
- `Http::BAD_REQUEST` (400)
- `Http::UNAUTHORIZED` (401)
- `Http::FORBIDDEN` (403)
- `Http::NOT_FOUND` (404)
- `Http::UNPROCESSABLE_ENTITY` (422)

**❌ NEVER hardcode status codes like `200`, `404`, etc.**
**✅ ALWAYS use `Http::OK`, `Http::NOT_FOUND`, etc.**

---

## Request Validation Pattern

### FormRequest Structure

Every FormRequest MUST have:

1. **`authorize()` method** - Returns boolean
2. **`rules()` method** - Returns validation rules array
3. **`messages()` method** - Optional custom error messages

### Example Structure

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
        ];
    }
}
```

### Validation Rules Best Practices

- Use Laravel validation rules
- Use `Rule::in()` for enum-like values
- Use `exists:Model::class` for foreign key checks
- Use `required_if:field,value` for conditional validation
- Use array syntax for complex rules: `['required', 'string', 'max:255']`

### Controller Usage

```php
public function store(UserRequest $request)
{
    // Validation is automatic
    $data = $request->validated();
    // Use $data...
}
```

**CRITICAL RULES:**
- ❌ **NEVER** validate in controllers using `$request->validate()`
- ✅ **ALWAYS** create FormRequest classes
- ✅ **ALWAYS** use `$request->validated()` to get validated data

---

## Dependency Injection Pattern

### Service Provider Binding

#### InterfaceServiceProvider

**Location:** `app/Providers/InterfaceServiceProvider.php`

**Purpose:** Binds service interfaces to implementations

**Pattern:**
```php
public $bindings = [
    UserInterface::class => UserService::class,
    LoginInterface::class => \App\Services\Auth\LoginService::class,
];
```

**When to add:**
- When creating a new Service, add binding here
- Interface must exist in `app/Services/Interfaces/`
- Service must implement the interface

#### RepositoryServiceProvider

**Location:** `app/Providers/RepositoryServiceProvider.php`

**Purpose:** Binds repository interfaces and model dependencies

**Pattern:**
```php
public $bindings = [
    UserRepositoryInterface::class => UserRepository::class,
];

public function boot(): void
{
    $this->app->when(UserRepository::class)
        ->needs(Model::class)
        ->give(User::class);
}
```

**When to add:**
- When creating a new Repository, add binding here
- Interface must exist in `app/Repositories/Interfaces/`
- Repository must extend `BaseRepository`

### Constructor Injection

**Pattern:**
```php
public function __construct(
    private readonly UserInterface $userService
) {}
```

**Rules:**
- ✅ **ALWAYS** use `private readonly` for injected dependencies
- ✅ **ALWAYS** type-hint with interface, not implementation
- ✅ **ALWAYS** inject in constructor, not methods

**Examples:**

**Controller:**
```php
class UserController extends Controller
{
    public function __construct(
        private readonly UserInterface $userService
    ) {}
}
```

**Service:**
```php
class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}
}
```

---

## Routes Organization

### Route Files

1. **`routes/api.php`** - Main API routes (auth, public endpoints)
2. **`routes/api/admin.php`** - Admin routes (prefix: `api/admin`)
3. **`routes/api/employer.php`** - Employer routes (prefix: `api/employer`)
4. **`routes/api/talent.php`** - Talent routes (prefix: `api/talent`)

### Route Patterns

**Main API Routes:**
```php
Route::prefix('auth')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store']);
});
```

**Grouped Routes:**
```php
// routes/api/admin.php
Route::prefix('api/admin')->group(function () {
    // Admin routes here
});

// routes/api/employer.php
Route::prefix('api/employer')->group(function () {
    // Employer routes here
});

// routes/api/talent.php
Route::prefix('api/talent')->group(function () {
    // Talent routes here
});
```

### Route Naming

- Use `Route::prefix()` for grouping
- Use `->name()` for named routes
- Use `->middleware()` for auth/authorization
- Use `->middleware('auth:sanctum')` for authenticated routes

**CRITICAL RULES:**
- ✅ **ALWAYS** group routes by domain (admin, employer, talent)
- ✅ **ALWAYS** use appropriate prefixes
- ✅ **ALWAYS** add middleware for protected routes

---

## Models Pattern

### Common Traits

Models typically use:
- `HasUuids` - UUID primary keys
- `SoftDeletes` - Soft delete functionality
- `HasFactory` - Model factories
- `HasRoles` (Spatie) - Role-based permissions
- Custom traits in `app/Models/Concerns/Traits/`

### Model Structure

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
    ];

    protected $hidden = ['password'];

    protected $with = ['roles', 'permissions'];

    protected $casts = [
        'dob' => 'date',
        'password' => 'hashed'
    ];

    // Relationships
    public function company()
    {
        return $this->hasOne(Company::class);
    }
}
```

### Model Properties

- **`$fillable`** - Mass assignable attributes
- **`$hidden`** - Hidden from JSON (e.g., passwords)
- **`$with`** - Eager loaded relationships
- **`$casts`** - Attribute casting

### Relationships

- Use standard Eloquent relationships
- `belongsTo`, `hasMany`, `hasOne`, `belongsToMany`, etc.
- Always define relationships in models

### Custom Traits

**Location:** `app/Models/Concerns/Traits/`

**Available Traits:**
- `HasUuid` - Auto-generates UUID on creation
- `HasFormattedName` - Formats name attributes

---

## Services Pattern

### Service Structure

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Interfaces\UserInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Business logic here
            return $this->userRepository->create($data);
        });
    }
}
```

### Service Requirements

1. **Strict Types:** `declare(strict_types=1);` at top
2. **Interface Implementation:** Must implement corresponding interface
3. **Repository Usage:** Use repositories, not direct model queries
4. **Transactions:** Use `DB::transaction()` for multi-step operations
5. **Return Types:** Always specify return types

### Service Methods

**Return Types:**
- `User` - Single model
- `User|Exception` - Model or exception
- `void` - No return value
- `Collection` - Collection of models

**Business Logic:**
- Handle validation logic
- Handle business rules
- Orchestrate multiple operations
- Send emails, trigger events

**CRITICAL RULES:**
- ❌ **NEVER** query models directly (use repositories)
- ❌ **NEVER** put HTTP logic in services
- ✅ **ALWAYS** use repositories for data access
- ✅ **ALWAYS** use transactions for multi-step operations
- ✅ **ALWAYS** implement corresponding interface

---

## Repositories Pattern

### Repository Structure

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return $this->query()->create($data);
    }

    public function findBy(string $column, mixed $value): User
    {
        return $this->query()
            ->where($column, $value)
            ->firstOrFail();
    }
}
```

### Repository Requirements

1. **Extend BaseRepository:** Must extend `BaseRepository`
2. **Interface Implementation:** Must implement corresponding interface
3. **Use `query()` Method:** Always use `$this->query()` from BaseRepository
4. **Return Types:** Return models or collections

### BaseRepository

**Location:** `app/Repositories/BaseRepository.php`

**Provides:**
- `query()` method that returns Eloquent Builder
- Constructor accepts `Model` instance

**Usage:**
```php
// In repository
$this->query()->where('email', $email)->first();
```

### Repository Methods

Common patterns:
- `create(array $data): Model`
- `findBy(string $column, mixed $value): Model`
- `update(Model $model, array $data): Model`
- `delete(Model $model): bool`
- `getAll(): Collection`

**CRITICAL RULES:**
- ❌ **NEVER** put business logic in repositories
- ✅ **ALWAYS** extend BaseRepository
- ✅ **ALWAYS** use `$this->query()` method
- ✅ **ALWAYS** implement corresponding interface

---

## Code Quality Standards

### Type Declarations

**Required:**
- `declare(strict_types=1);` in Services
- Type hints for all parameters
- Return type declarations for all methods

**Examples:**
```php
declare(strict_types=1);

public function create(array $data): User
{
    // ...
}
```

### Error Handling

**Validation Errors:**
```php
use Illuminate\Validation\ValidationException;

throw ValidationException::withMessages([
    'email' => ['Invalid email address']
]);
```

**HTTP Status Codes:**
- Always use `Http` enum
- Never hardcode status codes

### Database Transactions

**Pattern:**
```php
use Illuminate\Support\Facades\DB;

return DB::transaction(function () use ($data) {
    // Multiple database operations
    $user = $this->userRepository->create($data);
    $company = Company::create([...]);
    return $user;
});
```

**When to use:**
- Multiple related database operations
- Operations that must succeed or fail together

### Comments & Documentation

**PHPDoc Blocks:**
```php
/**
 * Create a new user.
 *
 * @param array $data
 * @return User
 * @throws ValidationException
 */
public function create(array $data): User
```

**Inline Comments:**
- Use for complex logic
- Explain "why", not "what"

### Code Organization

- Group related methods together
- Keep methods focused and small
- Extract complex logic into private methods

---

## File Structure & Organization

### Directory Structure

```
app/
├── Enums/              # Enums (Http, Status, RoleEnum, etc.)
├── Events/             # Event classes
├── Exceptions/         # Exception handlers
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Admin controllers
│   │   ├── Auth/       # Auth controllers
│   │   ├── Employer/   # Employer controllers
│   │   ├── Talent/     # Talent controllers
│   │   ├── Concerns/   # Controller traits (ApiResponse)
│   │   └── Controller.php
│   └── Requests/
│       ├── Auth/       # Auth requests
│       └── *.php       # Other requests
├── Listeners/          # Event listeners
├── Mail/               # Mail classes
├── Models/
│   ├── Concerns/
│   │   └── Traits/     # Model traits
│   └── *.php           # Model classes
├── Providers/          # Service providers
├── Repositories/
│   ├── Interfaces/     # Repository interfaces
│   └── *.php           # Repository classes
├── Services/
│   ├── Auth/           # Auth services
│   ├── Interfaces/     # Service interfaces
│   │   └── Auth/       # Auth service interfaces
│   └── *.php           # Service classes
└── Traits/             # Global traits

routes/
├── api.php             # Main API routes
└── api/
    ├── admin.php       # Admin routes
    ├── employer.php    # Employer routes
    └── talent.php      # Talent routes
```

---

## Key Rules Checklist

Before making any change, verify:

### Architecture
- [ ] Controller only handles HTTP request/response
- [ ] Business logic is in Service
- [ ] Data access is in Repository
- [ ] Service uses Repository, not direct model queries
- [ ] All layers use Interfaces for dependency injection

### Naming
- [ ] Controller named `{Resource}Controller`
- [ ] Service named `{Resource}Service` and implements `{Resource}Interface`
- [ ] Repository named `{Resource}Repository` and implements `{Resource}RepositoryInterface`
- [ ] Request named `{Action}{Resource}Request` or `{Resource}Request`
- [ ] Model named singular PascalCase

### API Responses
- [ ] Using `ApiResponse` trait methods (never raw JSON)
- [ ] Using `Http` enum for status codes (never hardcoded)
- [ ] Response structure matches standard format
- [ ] Appropriate method used (`success`, `successWithData`, `created`, `error`, etc.)

### Validation
- [ ] FormRequest class created (never validate in controller)
- [ ] `authorize()` method returns boolean
- [ ] `rules()` method returns validation rules
- [ ] Using `$request->validated()` in controller

### Dependency Injection
- [ ] Service interface bound in `InterfaceServiceProvider`
- [ ] Repository interface bound in `RepositoryServiceProvider`
- [ ] Using `private readonly` for constructor injection
- [ ] Type-hinting with interface, not implementation

### Services
- [ ] `declare(strict_types=1);` at top
- [ ] Implements corresponding interface
- [ ] Uses repositories for data access
- [ ] Uses `DB::transaction()` for multi-step operations
- [ ] Return types specified

### Repositories
- [ ] Extends `BaseRepository`
- [ ] Implements corresponding interface
- [ ] Uses `$this->query()` method
- [ ] No business logic in repository

### Code Quality
- [ ] Type hints on all parameters
- [ ] Return types on all methods
- [ ] Using `Http` enum for status codes
- [ ] PHPDoc blocks for methods
- [ ] Proper error handling

### Routes
- [ ] Routes grouped by domain (admin, employer, talent)
- [ ] Appropriate prefixes used
- [ ] Middleware added for protected routes
- [ ] Named routes where appropriate

---

## Examples & Templates

### Complete Controller Example

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Interfaces\UserInterface;

class UserController extends Controller
{
    public function __construct(
        private readonly UserInterface $userService
    ) {}

    public function show(User $user)
    {
        return $this->successWithData($user, 'User retrieved successfully');
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return $this->created('User created successfully', $user);
    }
}
```

### Complete Service Example

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserInterface;
use Illuminate\Support\Facades\DB;

class UserService implements UserInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return $this->userRepository->create($data);
        });
    }
}
```

### Complete Repository Example

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return $this->query()->create($data);
    }

    public function findBy(string $column, mixed $value): User
    {
        return $this->query()
            ->where($column, $value)
            ->firstOrFail();
    }
}
```

### Complete FormRequest Example

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['talent', 'company'])],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address field is required.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}
```

### Service Provider Binding Example

**InterfaceServiceProvider:**
```php
public $bindings = [
    UserInterface::class => UserService::class,
];
```

**RepositoryServiceProvider:**
```php
public $bindings = [
    UserRepositoryInterface::class => UserRepository::class,
];

public function boot(): void
{
    $this->app->when(UserRepository::class)
        ->needs(Model::class)
        ->give(User::class);
}
```

---

## Final Reminders

**ALWAYS:**
1. ✅ Check existing implementations before creating new ones
2. ✅ Follow the layered architecture (Controller → Service → Repository)
3. ✅ Use the ApiResponse trait methods
4. ✅ Use FormRequest classes for validation
5. ✅ Use Interfaces for dependency injection
6. ✅ Use Http enum for status codes
7. ✅ Use `declare(strict_types=1)` in Services
8. ✅ Use `private readonly` for constructor injection
9. ✅ Use `DB::transaction()` for multi-step operations
10. ✅ Follow naming conventions exactly

**NEVER:**
1. ❌ Put business logic in Controllers
2. ❌ Query models directly from Services
3. ❌ Validate in Controllers
4. ❌ Hardcode HTTP status codes
5. ❌ Return raw JSON responses
6. ❌ Skip creating Interfaces
7. ❌ Skip Service Provider bindings
8. ❌ Use implementation classes in type hints (use interfaces)
9. ❌ Bypass the architecture layers
10. ❌ Create files without following naming conventions

---

**Last Updated:** This document reflects the current state of the codebase. Always refer to existing implementations for the most up-to-date patterns.

