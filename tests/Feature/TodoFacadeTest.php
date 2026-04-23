<?php

use CharlesLightjarvis\Todo\Facades\Todo;
use CharlesLightjarvis\Todo\Models\Todo as TodoModel;
use CharlesLightjarvis\Todo\Tests\Fixtures\User;

it('for() returns a builder scoped to the given model', function () {
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);

    $alice->todos()->create(['title' => 'Alice task']);
    $bob->todos()->create(['title' => 'Bob task']);

    $results = Todo::for($alice)->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Alice task');
});

it('createFor() creates a todo with morph fields set automatically', function () {
    $user = User::create(['name' => 'Alice']);

    $todo = Todo::createFor($user, ['title' => 'Via facade']);

    expect($todo)->toBeInstanceOf(TodoModel::class)
        ->and($todo->title)->toBe('Via facade')
        ->and($todo->todoable_id)->toBe($user->id)
        ->and($todo->todoable_type)->toBe($user->getMorphClass());
});

it('supports scope chaining', function () {
    $user = User::create(['name' => 'Alice']);

    $user->todos()->create(['title' => 'High pending',   'priority' => 'high', 'status' => 'pending']);
    $user->todos()->create(['title' => 'Low pending',    'priority' => 'low',  'status' => 'pending']);
    $user->todos()->create(['title' => 'High completed', 'priority' => 'high', 'status' => 'completed', 'completed_at' => now()]);

    $results = Todo::for($user)->pending()->highPriority()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('High pending');
});

it('count() returns the correct number of todos per scope', function () {
    $user = User::create(['name' => 'Alice']);

    $user->todos()->create(['title' => 'Task 1', 'status' => 'completed', 'completed_at' => now()]);
    $user->todos()->create(['title' => 'Task 2', 'status' => 'completed', 'completed_at' => now()]);
    $user->todos()->create(['title' => 'Task 3', 'status' => 'pending']);

    expect(Todo::for($user)->completed()->count())->toBe(2)
        ->and(Todo::for($user)->pending()->count())->toBe(1);
});

it('is isolated between different models', function () {
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);

    $alice->todos()->create(['title' => 'A1']);
    $alice->todos()->create(['title' => 'A2']);
    $bob->todos()->create(['title' => 'B1']);

    expect(Todo::for($alice)->count())->toBe(2)
        ->and(Todo::for($bob)->count())->toBe(1);
});
