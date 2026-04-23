<?php

namespace CharlesLightjarvis\Todo;

use CharlesLightjarvis\Todo\Contracts\TodoHandler;
use Illuminate\Support\ServiceProvider;

class TodoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TodoHandler::class, Todo::class);

        $this->app->singleton('todo', function ($app) {
            return $app->make(TodoHandler::class);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/todo.php' => config_path('todo.php'),
        ], 'todo-config');

        // Publier la migration
        if (! class_exists('CreateTodosTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_todos_table.php.stub' => database_path('migrations/'.date('Y_m_d_His').'_create_todos_table.php'),
            ], 'todo-migrations');
        }
    }
}
