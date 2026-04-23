<?php

use CharlesLightjarvis\Todo\Tests\Fixtures\User;

it('tracks the creator via morph fields when set manually', function () {
    $owner = User::create(['name' => 'Owner']);
    $creator = User::create(['name' => 'Creator']);

    $todo = $owner->todos()->create([
        'title' => 'Review PR',
        'creator_type' => $creator->getMorphClass(),
        'creator_id' => $creator->id,
    ]);

    expect($todo->creator)->toBeInstanceOf(User::class)
        ->and($todo->creator->id)->toBe($creator->id);
});

it('creator is null when not provided', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'No creator']);

    expect($todo->creator)->toBeNull();
});

it('createdTodos counts todos across different owners', function () {
    $creator = User::create(['name' => 'Creator']);
    $alice = User::create(['name' => 'Alice']);
    $bob = User::create(['name' => 'Bob']);

    $alice->addTodo(['title' => 'Task for Alice'], $creator);
    $bob->addTodo(['title' => 'Task for Bob'], $creator);
    $alice->todos()->create(['title' => 'No creator task']);

    expect($creator->createdTodos)->toHaveCount(2);
});

it('a user can be both owner and creator of a todo', function () {
    $user = User::create(['name' => 'Self']);

    $todo = $user->addTodo(['title' => 'Self-assigned'], $user);

    expect($todo->todoable_id)->toBe($user->id)
        ->and($todo->creator_id)->toBe($user->id);
});
