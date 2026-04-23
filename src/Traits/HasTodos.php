<?php

namespace CharlesLightjarvis\Todo\Traits;

use CharlesLightjarvis\Todo\Models\Todo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTodos
{
    public function todos(): MorphMany
    {
        return $this->morphMany(Todo::class, 'todoable');
    }

    public function createdTodos(): MorphMany
    {
        return $this->morphMany(Todo::class, 'creator');
    }

    public function addTodo(array $attributes, ?Model $creator = null): Todo
    {
        /** @var Todo $todo */
        $todo = $this->todos()->create($attributes);

        if ($creator) {
            $todo->creator()->associate($creator)->save();
        }

        return $todo;
    }

    public function completeTodo(Todo $todo): bool
    {
        if (! $this->ownsTodo($todo)) {
            return false;
        }

        return $todo->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function cancelTodo(Todo $todo): bool
    {
        if (! $this->ownsTodo($todo)) {
            return false;
        }

        return $todo->update(['status' => 'cancelled']);
    }

    /**
     * Determine if the given todo belongs to the model.
     */
    protected function ownsTodo(Todo $todo): bool
    {
        return $todo->todoable_id === $this->id
            && $todo->todoable_type === static::class;
    }
}
