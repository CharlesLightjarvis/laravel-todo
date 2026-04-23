<?php

use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;
use CharlesLightjarvis\Todo\Tests\Fixtures\User;
use Illuminate\Support\Carbon;

it('casts status attribute to TodoStatusEnum', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Test', 'status' => 'in_progress']);

    expect($todo->status)->toBeInstanceOf(TodoStatusEnum::class)
        ->and($todo->status)->toBe(TodoStatusEnum::IN_PROGRESS);
});

it('casts priority attribute to TodoPriorityEnum', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Test', 'priority' => 'high']);

    expect($todo->priority)->toBeInstanceOf(TodoPriorityEnum::class)
        ->and($todo->priority)->toBe(TodoPriorityEnum::HIGH);
});

it('casts due_at to a Carbon instance', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Test', 'due_at' => now()->addDays(3)]);

    expect($todo->due_at)->toBeInstanceOf(Carbon::class);
});

it('casts completed_at to a Carbon instance', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Test', 'completed_at' => now()]);

    expect($todo->completed_at)->toBeInstanceOf(Carbon::class);
});

it('resolves the todoable polymorphic relation', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'Test']);

    expect($todo->todoable)->toBeInstanceOf(User::class)
        ->and($todo->todoable->id)->toBe($user->id);
});

it('stores pending as the default status', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'No status given']);

    expect($todo->fresh()->status)->toBe(TodoStatusEnum::PENDING);
});

it('stores low as the default priority', function () {
    $user = User::create(['name' => 'Alice']);
    $todo = $user->todos()->create(['title' => 'No priority given']);

    expect($todo->fresh()->priority)->toBe(TodoPriorityEnum::LOW);
});
