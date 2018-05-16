<?php
/**
 * Copyright (c) Padosoft.com 2018.
 */

namespace Padosoft\Laravel\Validable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Trait Validable
 * @package Padosoft\Laravel\Validable
 * @property Array $rules Validation rules
 * @property Array $messages Validation messages
 */
trait Validable
{
    /**
     * Error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;
    /**
     * Validator instance
     *
     * @var Illuminate\Validation\Validators
     */
    protected $validator = null;

    protected static function bootValidable()
    {
        static::saving(function (Model $model) {
            if (!$model->hasValidator()) {
                $model->setValidator(App::make('validator'));
            }

            return $model->validate();
        });
    }

    public function setValidator(ValidatorFactory $validator)
    {
        $this->validator = $validator;
    }

    public function hasValidator()
    {
        return $this->validator !== null;
    }

    /**
     * Validates current attributes against rules
     */
    public function validate()
    {
        $v = $this->validator->make($this->attributes, static::getRules(), static::getMessages());
        if ($v->passes()) {
            return true;
        }
        $this->setErrors($v->messages());

        return false;
    }

    /**
     * Set error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors!==null?$this->errors:[];
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Return true if the validation is passed and the model was saved on db
     * @return bool
     */
    public function wasSaved(){
        return empty($this->errors);
    }


    public static function getRules()
    {
        if (isset(static::$rules)) {
            return static::$rules;
        }

        return [];
    }

    public static function getMessages()
    {
        if (isset(static::$messages)) {
            return static::$messages;
        }

        return [];
    }
}
