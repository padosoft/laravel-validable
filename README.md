# Trait to activate validation when saving Eloquent Model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/laravel-validable.svg?style=flat-square)](https://packagist.org/packages/padosoft/laravel-validable)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/padosoft/laravel-validable/master.svg?style=flat-square)](https://travis-ci.org/padosoft/laravel-validable)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/laravel-validable.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/laravel-validable)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/laravel-validable.svg?style=flat-square)](https://packagist.org/packages/padosoft/laravel-validable)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/4570b2c7-71c6-4b11-9214-da078fb51a98.svg?style=flat-square)](https://insight.sensiolabs.com/projects/4570b2c7-71c6-4b11-9214-da078fb51a98)

This package provides a trait that will automatic handlind upload when saving/updating/deleting any Eloquent model with upload form request.

##Requires
  
- php: >=7.0.0
- illuminate/database: ^5.0
- illuminate/support: ^5.0
- illuminate/validation: ^5.0
  
## Installation

You can install the package via composer:
``` bash
$ composer require padosoft/laravel-validable
```

## Usage

Your Eloquent models should use the `Padosoft\Laravel\Validable\Validable` trait.

You must define `protected static $rules`  array of rules in your model. 
You can define `protected static $messages`  array of custom messages in your model. 

Here's an example of how to implement the trait;

```php
<?php

namespace App;

use Padosoft\Laravel\Validable\Validable;
use Illuminate\Database\Eloquent\Model;

class YourEloquentModel extends Model
{
    use Validable;
    protected static $rules = [
            'name'=>'required|max:10',
            'order'=>'sometimes|integer|max:10',
        ];
    
        protected static $messages = [
            'name.required'=>'obbligatorio'
        ];
}
```
You can write specific validation for only update method
```php
class YourEloquentModel extends Model
{
    use Validable;
    protected static $rules = [
            'name'=>'required|max:10|unique:table,field',
            'order'=>'sometimes|integer|max:10',
        ];
    protected static $updating_rules = [
                'name'=>'required|max:10|unique:table,field,[id]',
                'order'=>'sometimes|integer|max:10',
            ];
        protected static $messages = [
            'name.required'=>'obbligatorio'
        ];
}
```
**Note:** [id] will be overwritten at runtime with the model property.

You can check if your model is saved like this:

```php
$model = new YourEloquentModel;
$model->name='test';
if (!$model->save()){
    $erros=$model->getErrors();
}
```
You can get a model validation rules:

```php
$rules=YourEloquentModel::getRules();
```

For all method available see the Validable Trait.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## Credits
Inspired by https://github.com/JeffreyWay/Laravel-Model-Validation
- [Lorenzo Padovani](https://github.com/lopadova)
- [Leonardo Padovani](https://github.com/leopado)
- [All Contributors](../../contributors)

## About Padosoft
Padosoft (https://www.padosoft.com) is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
