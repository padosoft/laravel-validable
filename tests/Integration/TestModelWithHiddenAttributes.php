<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

use Illuminate\Database\Eloquent\Model;
use Padosoft\Laravel\Validable\Validable;

class TestModelWithHiddenAttributes extends Model
{
    use Validable;

    protected $table = 'test_models';

    protected $hidden=['name'];

    protected static $rules = [
        'name'=>'required|max:10',
        'order'=>'sometimes|integer|max:10',
    ];

    protected static $messages = [
        'name.required'=>'obbligatorio'
    ];

    public $timestamps = false;

}
