<?php

use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;
use CharlesLightjarvis\Todo\Models\Todo;
use CharlesLightjarvis\Todo\Tests\Fixtures\User;

it('creates a todo via the relation', function () {
    $user = User::create(['name' => 'Alice']);

    $todo = $user->todos()->create(['title' => 'Buy groceries']);

    expect($todo)->toBeInstanceOf(Todo::class)
        ->and($todo->title)->toBe('Buy groceries')
        ->and($todo->todoable_id)->toBe($user->id);
});

it('creates a todo via addTodo', function () {
    $user = User::create(['name' => 'Alice']);

    $todo = $user->addTodo(['title' => 'Write tests', 'priority' => 'high']);

    expect($todo->title)->toBe('Write tests')
        ->and($todo->priority)->toBe(TodoPriorityEnum::HIGH);
});

it('addTodo assigns a creator when provided', function () {
    $owner = User::create(['name' => 'Alice']);
    $creator = User::create(['name' => 'Bob']);

    $todo = $owner->addTodo(['title' => 'Review PR'], $creator);

    expect($todo->creator_id)->toBe($creator->id)
        ->and($todo->creator_type)->toBe($creator->getMorphClass());
});

it('completes a todo owned by the model', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Finish report']);

    $result = $user->completeTodo($todo);

    expect($result)->toBeTrue()
        ->and($todo->fresh()->status)->toBe(TodoStatusEnum::COMPLETED)
        ->and($todo->fresh()->completed_at)->not->toBeNull();
});

it('does not complete a todo owned by another model', function () {
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);
    $todo = $alice->todos()->create(['title' => 'Alice task']);

    $result = $bob->completeTodo($todo);

    expect($result)->toBeFalse()
        ->and($todo->fresh()->status)->toBe(TodoStatusEnum::PENDING);
});

it('cancels a todo owned by the model', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Task to cancel']);

    $result = $user->cancelTodo($todo);

    expect($result)->toBeTrue()
        ->and($todo->fresh()->status)->toBe(TodoStatusEnum::CANCELLED);
});

it('does not cancel a todo owned by another model', function () {
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);
    $todo = $alice->todos()->create(['title' => 'Alice task']);

    $result = $bob->cancelTodo($todo);

    expect($result)->toBeFalse()
        ->and($todo->fresh()->status)->toBe(TodoStatusEnum::PENDING);
});

it('todos relation is scoped to the owning model', function () {
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);

    $alice->todos()->create(['title' => 'Alice task 1']);
    $alice->todos()->create(['title' => 'Alice task 2']);
    $bob->todos()->create(['title' => 'Bob task']);

    expect($alice->todos)->toHaveCount(2)
        ->and($bob->todos)->toHaveCount(1);
});

it('createdTodos relation returns todos where the model is the creator', function () {
    $creator = User::create(['name' => 'Creator']);
    $owner = User::create(['name' => 'Owner']);

    $owner->addTodo(['title' => 'Task A'], $creator);
    $owner->addTodo(['title' => 'Task B'], $creator);
    $owner->todos()->create(['title' => 'No creator']);

    expect($creator->createdTodos)->toHaveCount(2);
});
