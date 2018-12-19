# Laravel GDPR Nullify

If you are affected by GDPR, you may find yourself in situation, when someone ask you to delete his/her data. You don't need to delete their records, just hide the sensible fields. This way your database remains consistent (eg. for history, statistics) but you finally satisfy the request.

With this package you can easily "nullify" specific fields for any Eloqent Model.

## Requirements

Laravel 5.5

## Installation

Install with composer
```bash
composer require subdesign/laravel-gdpr-nullify:^1.0.0
```

## Setup

Add the following trait to you Eloquent model

```php
use Subdesign\LaravelGdprNullify\GdprNullifyTrait;

class YourModel extends Model {

    use GdprNullifyTrait;

    ...
}
```

Next, add a property to this model which field(s) you want to nullify

```php
protected $gdprFields = ['name', 'email'];
```

## Usage 

Use the `nullify()` method on a model instance

```php

$user = App\User::find(1);

$user->nullify();
```

In the example, the `name` and `email` fields will be filled with random characters in the length of the database field.

## Dependency

The package has a dependency which is automatically installed: [https://github.com/doctrine/dbal/tree/2.9](https://github.com/doctrine/dbal/tree/2.9)

## Credits

- [Barna Szalai](https://github.com/subdesign)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
