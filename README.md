# Laravel Todo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/charleslightjarvis/laravel-todo.svg?style=flat-square)](https://packagist.org/packages/charleslightjarvis/laravel-todo)
[![Total Downloads](https://img.shields.io/packagist/dt/charleslightjarvis/laravel-todo.svg?style=flat-square)](https://packagist.org/packages/charleslightjarvis/laravel-todo)
[![License](https://img.shields.io/packagist/l/charleslightjarvis/laravel-todo.svg?style=flat-square)](LICENSE.md)

Attach todo lists to any Eloquent model in your Laravel application.

## Features

- ✅ Polymorphic relationship – attach todos to any model (`User`, `Project`, `Team`, etc.)
- ✅ Fluent API via Facade or Trait
- ✅ Built-in scopes: `pending()`, `completed()`, `overdue()`, `highPriority()`, `dueToday()`
- ✅ Status management: `pending`, `in_progress`, `completed`, `cancelled`
- ✅ Priority levels: `low`, `medium`, `high`
- ✅ Tracks who created each todo (polymorphic `creator` relation)
- ✅ Zero UI – backend only, integrate however you want

## Requirements

- PHP 8.2 or higher
- Laravel 11.0 or higher

## Installation

Install the package via Composer:

```bash
composer require charleslightjarvis/laravel-todo
```

Publish the migration file:

```bash
php artisan vendor:publish --tag="todo-migrations"
```

Run the migrations:

```bash
php artisan migrate
```

Publish the configuration file (optional):

```bash
php artisan vendor:publish --tag="todo-config"
```

## Configuration

The config file `config/todo.php` allows you to customize:

```php
return [
    'prune_after_days' => 30,

    'models' => [
        'todo' => CharlesLightjarvis\Todo\Models\Todo::class,
    ],

    'todo_morph_key' => 'todoable_id',
];
```

## Usage

### 1. Add the trait to your model

```php
use CharlesLightjarvis\Todo\Traits\HasTodos;

class User extends Model
{
    use HasTodos;
}

class Project extends Model
{
    use HasTodos;
}
```

### 2. Basic operations

```php
// Create a todo on a model
$user->todos()->create([
    'title' => 'Finish the report',
    'priority' => 'high',
    'due_at' => now()->addDays(3),
]);

// Retrieve todos
$pendingTodos = $user->todos()->pending()->get();
$urgentTodos  = $user->todos()->highPriority()->get();
$overdueTodos = $user->todos()->overdue()->get();
$todayTodos   = $user->todos()->dueToday()->get();

// Complete or cancel
$user->completeTodo($todo);
$user->cancelTodo($todo);
```

### 3. Using the Facade

```php
use Todo;

// Create a todo for any model
$todo = Todo::for($project)->create([
    'title'    => 'Fix navigation bug',
    'priority' => 'high',
]);

// Query todos for a model
$pending = Todo::for($team)->pending()->get();
$count   = Todo::for($user)->completed()->count();
```

### 4. Tracking who created a todo

```php
// The trait also provides a `createdTodos` relation
$user = auth()->user();
$todosCreatedByMe = $user->createdTodos;

// Set creator manually when needed
$todo = $project->todos()->create([
    'title'        => 'Review PR',
    'creator_type' => $user->getMorphClass(),
    'creator_id'   => $user->id,
]);
```

## Available Scopes

| Scope            | Description                          |
| ---------------- | ------------------------------------ |
| `pending()`      | Status = `pending`                   |
| `inProgress()`   | Status = `in_progress`               |
| `completed()`    | Status = `completed`                 |
| `cancelled()`    | Status = `cancelled`                 |
| `overdue()`      | Not completed + `due_at` in the past |
| `highPriority()` | Priority = `high`                    |
| `dueToday()`     | `due_at` is today                    |

## Enums

The package provides two enums for type safety:

```php
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;
use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;

// Status values
TodoStatusEnum::PENDING->value;      // 'pending'
TodoStatusEnum::IN_PROGRESS->value;  // 'in_progress'
TodoStatusEnum::COMPLETED->value;    // 'completed'
TodoStatusEnum::CANCELLED->value;    // 'cancelled'

// Priority values
TodoPriorityEnum::LOW->value;    // 'low'
TodoPriorityEnum::MEDIUM->value; // 'medium'
TodoPriorityEnum::HIGH->value;   // 'high'
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email charlestagne55@gmail.com instead of using the issue tracker.

## Credits

- [Charles Lightjarvis](https://github.com/CharlesLightjarvis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
