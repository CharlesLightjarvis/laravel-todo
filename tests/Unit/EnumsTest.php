<?php

use CharlesLightjarvis\Todo\Enums\TodoPriorityEnum;
use CharlesLightjarvis\Todo\Enums\TodoStatusEnum;

describe('TodoStatusEnum', function () {
    it('has correct string values', function (TodoStatusEnum $case, string $value) {
        expect($case->value)->toBe($value);
    })->with([
        [TodoStatusEnum::PENDING,     'pending'],
        [TodoStatusEnum::IN_PROGRESS, 'in_progress'],
        [TodoStatusEnum::COMPLETED,   'completed'],
        [TodoStatusEnum::CANCELLED,   'cancelled'],
    ]);

    it('can be instantiated from a string', function (string $value, TodoStatusEnum $expected) {
        expect(TodoStatusEnum::from($value))->toBe($expected);
    })->with([
        ['pending',     TodoStatusEnum::PENDING],
        ['in_progress', TodoStatusEnum::IN_PROGRESS],
        ['completed',   TodoStatusEnum::COMPLETED],
        ['cancelled',   TodoStatusEnum::CANCELLED],
    ]);

    it('has exactly four cases', function () {
        expect(TodoStatusEnum::cases())->toHaveCount(4);
    });
});

describe('TodoPriorityEnum', function () {
    it('has correct string values', function (TodoPriorityEnum $case, string $value) {
        expect($case->value)->toBe($value);
    })->with([
        [TodoPriorityEnum::LOW,    'low'],
        [TodoPriorityEnum::MEDIUM, 'medium'],
        [TodoPriorityEnum::HIGH,   'high'],
    ]);

    it('can be instantiated from a string', function (string $value, TodoPriorityEnum $expected) {
        expect(TodoPriorityEnum::from($value))->toBe($expected);
    })->with([
        ['low',    TodoPriorityEnum::LOW],
        ['medium', TodoPriorityEnum::MEDIUM],
        ['high',   TodoPriorityEnum::HIGH],
    ]);

    it('has exactly three cases', function () {
        expect(TodoPriorityEnum::cases())->toHaveCount(3);
    });
});
