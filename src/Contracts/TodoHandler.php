<?php

namespace CharlesLightjarvis\Todo\Contracts;

use CharlesLightjarvis\Todo\Models\Todo as TodoModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface TodoHandler
{
    public function for(Model $model): Builder;

    public function createFor(Model $model, array $attributes): TodoModel;
}
