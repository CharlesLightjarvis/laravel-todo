<?php

namespace CharlesLightjarvis\Todo\Tests\Fixtures;

use CharlesLightjarvis\Todo\Traits\HasTodos;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasTodos;

    protected $table = 'users';

    protected $guarded = [];
}
