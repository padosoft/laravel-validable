<?php

namespace Padosoft\Laravel\Validable\Test\Integration;

use Illuminate\Database\Eloquent\Model;
use Padosoft\Laravel\Validable\Validable;

class TestModelWithDates extends Model
{
    use Validable;

    protected $table = 'test_models_with_dates';

    protected $dates = ["date_test","datetime_test"];
    protected $casts = [
        "date_test" => 'datetime:Y-m-d',
        "datetime_test" => 'datetime:Y-m-d H:i:s',
    ];

    protected static $rules = [
        'name'=>'required|max:10',
        'date_test'=>'required|date_format:Y-m-d',
        'datetime_test'=>'required|date_format:Y-m-d H:i:s',
    ];

    protected static $messages = [
        'name.required'=>'obbligatorio'
    ];

    public $timestamps = false;

}
