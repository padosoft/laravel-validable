<?php
/**
 * Copyright (c) Padosoft.com 2018.
 */

namespace Padosoft\Laravel\Validable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\ValidationException;

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
     * @var Illuminate\Validation\Factory
     */
    protected $validator = null;

    protected static function bootValidable()
    {
        static::saving(function (Model $model) {
            if (!$model->validate()){
				return false;
			}
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
        if (!$this->hasValidator()) {
            $this->setValidator(App::make('validator'));
        }

        $v = $this->validator->make($this->attributes,
            $this->exists ? static::getUpdatingRules($this) : static::getCreatingRules(), static::getMessages());
        if ($v->passes()) {
            return true;
        }
        $this->setErrors($v->messages());
        if ($this->validateBase()){
            return true;
        }

        Log::debug('Errore durante la validazione del model \''.$this->getTable().'\' in \''.($this->id>0 ? 'update' : 'create').'\' lanciato su evento saving del model.');
        Log::debug('Attributi del model:');
        Log::debug($this->attributesToArray());
        Log::debug('Errori di validazione:');
        Log::debug($this->getErrors());

        //throw ValidationException::withMessages($this->getErrors());
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
        return $this->errors !== null ? $this->errors : [];
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
    public function wasSaved()
    {
        return empty($this->errors);
    }


    public static function getRules()
    {
        if (isset(static::$rules)) {
            return static::$rules;
        }

        return [];
    }

    public static function getCreatingRules()
    {
        if (isset(static::$rules)) {
            return static::$rules;
        }

        return [];
    }

    protected static function replacePlaceholders(Model $model, $rules)
    {
        $replaced = [];
        foreach ($rules as $key => $rule) {
            foreach ($model->attributes as $attr => $val) {
				if(strpos($rule,$attr)!==false && is_scalar($val)){
					$rule = str_replace('{' . $attr . '}', $val, $rule);
				}
            }
            $replaced[$key] = $rule;
        }

        return $replaced;
    }

    public static function getUpdatingRules(Model $model)
    {
        $rules = [];

        if (isset(static::$rules)) {
            $rules = static::$rules;
        }

        if (isset(static::$updating_rules)) {
            $rules = static::$updating_rules;
        }

        return static::replacePlaceholders($model, $rules);
    }

    public static function getMessages()
    {
        if (isset(static::$messages)) {
            return static::$messages;
        }

        return [];
    }
}
