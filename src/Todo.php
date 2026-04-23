<?php

namespace CharlesLightjarvis\Todo;

use CharlesLightjarvis\Todo\Contracts\TodoHandler;
use CharlesLightjarvis\Todo\Models\Todo as TodoModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Todo implements TodoHandler
{
    public function for(Model $model): Builder
    {
        return TodoModel::query()
            ->where('todoable_type', $model->getMorphClass())
            ->where('todoable_id', $model->getKey());
    }

    public function createFor(Model $model, array $attributes): TodoModel
    {
        return TodoModel::create(array_merge($attributes, [
            'todoable_type' => $model->getMorphClass(),
            'todoable_id' => $model->getKey(),
        ]));
    }
}
