<?php

use CharlesLightjarvis\Todo\Tests\Fixtures\User;

beforeEach(function () {
    $this->user = User::create(['name' => 'Alice']);
});

it('pending scope filters by pending status', function () {
    $this->user->todos()->create(['title' => 'Pending', 'status' => 'pending']);
    $this->user->todos()->create(['title' => 'Done',    'status' => 'completed', 'completed_at' => now()]);

    $results = $this->user->todos()->pending()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Pending');
});

it('inProgress scope filters by in_progress status', function () {
    $this->user->todos()->create(['title' => 'Working', 'status' => 'in_progress']);
    $this->user->todos()->create(['title' => 'Waiting', 'status' => 'pending']);

    $results = $this->user->todos()->inProgress()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Working');
});

it('completed scope filters by completed status', function () {
    $this->user->todos()->create(['title' => 'Done',    'status' => 'completed', 'completed_at' => now()]);
    $this->user->todos()->create(['title' => 'Pending', 'status' => 'pending']);

    $results = $this->user->todos()->completed()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Done');
});

it('cancelled scope filters by cancelled status', function () {
    $this->user->todos()->create(['title' => 'Cancelled', 'status' => 'cancelled']);
    $this->user->todos()->create(['title' => 'Pending',   'status' => 'pending']);

    $results = $this->user->todos()->cancelled()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Cancelled');
});

it('overdue scope returns non-completed todos with a past due_at', function () {
    $this->user->todos()->create(['title' => 'Overdue',      'status' => 'pending',   'due_at' => now()->subDay()]);
    $this->user->todos()->create(['title' => 'Future',       'status' => 'pending',   'due_at' => now()->addDay()]);
    $this->user->todos()->create(['title' => 'Done overdue', 'status' => 'completed', 'due_at' => now()->subDay(), 'completed_at' => now()]);

    $results = $this->user->todos()->overdue()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Overdue');
});

it('highPriority scope filters by high priority', function () {
    $this->user->todos()->create(['title' => 'High',   'priority' => 'high']);
    $this->user->todos()->create(['title' => 'Medium', 'priority' => 'medium']);
    $this->user->todos()->create(['title' => 'Low',    'priority' => 'low']);

    $results = $this->user->todos()->highPriority()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('High');
});

it('dueToday scope returns todos with due_at set to today', function () {
    $this->user->todos()->create(['title' => 'Today',     'due_at' => now()]);
    $this->user->todos()->create(['title' => 'Tomorrow',  'due_at' => now()->addDay()]);
    $this->user->todos()->create(['title' => 'Yesterday', 'due_at' => now()->subDay()]);

    $results = $this->user->todos()->dueToday()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('Today');
});

it('scopes can be chained together', function () {
    $this->user->todos()->create(['title' => 'High pending',   'priority' => 'high', 'status' => 'pending']);
    $this->user->todos()->create(['title' => 'High completed', 'priority' => 'high', 'status' => 'completed', 'completed_at' => now()]);
    $this->user->todos()->create(['title' => 'Low pending',    'priority' => 'low',  'status' => 'pending']);

    $results = $this->user->todos()->pending()->highPriority()->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->title)->toBe('High pending');
});
