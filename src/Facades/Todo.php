<?php

namespace CharlesLightjarvis\Todo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<\CharlesLightjarvis\Todo\Models\Todo> for(\Illuminate\Database\Eloquent\Model $model)
 * @method static \CharlesLightjarvis\Todo\Models\Todo createFor(\Illuminate\Database\Eloquent\Model $model, array $attributes)
 *
 * @see \CharlesLightjarvis\Todo\Todo
 */
class Todo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'todo';
    }
}
