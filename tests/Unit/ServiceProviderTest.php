<?php

use CharlesLightjarvis\Todo\Contracts\TodoHandler;
use CharlesLightjarvis\Todo\Todo as TodoClass;

it('binds TodoHandler to the Todo implementation', function () {
    expect(app(TodoHandler::class))->toBeInstanceOf(TodoClass::class);
});

it('registers todo as a singleton', function () {
    expect(app('todo'))->toBe(app('todo'));
});

it('resolves TodoHandler and the todo singleton to the same class', function () {
    expect(app(TodoHandler::class))->toBeInstanceOf(TodoClass::class)
        ->and(app('todo'))->toBeInstanceOf(TodoClass::class);
});
