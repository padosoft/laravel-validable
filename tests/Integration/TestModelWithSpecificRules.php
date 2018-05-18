<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

use Illuminate\Database\Eloquent\Model;
use Padosoft\Laravel\Validable\Validable;

class TestModelWithSpecificRules extends Model
{
    use Validable;

    protected $table = 'test_models';

    protected static $rules = [
        'name'=>'required|max:10|unique:test_models,name',
        'order'=>'sometimes|integer|max:10',
    ];

    protected static $updating_rules = [
        'name'=>'required|max:10|unique:test_models,name,{id}',
    ];

    protected static $messages = [
        'name.required'=>'obbligatorio'
    ];

    public $timestamps = false;

}
