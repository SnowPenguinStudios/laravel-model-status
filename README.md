# Snow Penguin Studios - Laravel Model Status

[![Latest Version on Packagist](https://img.shields.io/packagist/v/snowpenguinstudios/laravel-model-status.svg?style=flat-square)](https://packagist.org/packages/snowpenguinstudios/laravel-model-status)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/snowpenguinstudios/laravel-model-status/run-tests?label=tests)](https://github.com/snowpenguinstudios/laravel-model-status/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/snowpenguinstudios/laravel-model-status/Check%20&%20fix%20styling?label=code%20style)](https://github.com/snowpenguinstudios/laravel-model-status/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/snowpenguinstudios/laravel-model-status.svg?style=flat-square)](https://packagist.org/packages/snowpenguinstudios/laravel-model-status)

---

This Laravel package allows the ability to implement a status feature to any Laravel model. This utilizes
the polymorphic relationships. The package features includes:

- Setting and updating status on any model with a `status_id` field.
- Store status change history as status updates.
- Ability to assign statuses to be available to specific models. A status can be available for
  all models or one model.

---

## Installation

You can install the package via composer:

```bash
composer require snowpenguinstudios/laravel-model-status
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="SnowPenguinStudios\LaravelModelStatus\LaravelModelStatusServiceProvider" --tag="laravel-model-status-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="SnowPenguinStudios\LaravelModelStatus\LaravelModelStatusServiceProvider" --tag="laravel-model-status-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Model Setup

### Add status_id to the model table
```php
Schema::table('table-name', function (Blueprint $table) {
    $table->foreignId('status_id');
});
```

### Add HasStatus Trait to model
```php
use SnowPenguinStudios\LaravelModelStatus\Traits\HasStatus;

class ModelName extends Model {
    use HasStatus;
    
    ...
}
```

## Usage

### Working with the Status model

Creating a global status:
```php
use SnowPenguinStudios\LaravelModelStatus\Models\Status;
$status = Status::create([
    'name' => 'New',
    'is_active' => true
]);
```

Creating a new model-specific status:
```php
use SnowPenguinStudios\LaravelModelStatus\Models\Status;
$status = Status::create([
    'name' => 'New',
    'model' => ModelName::class,
    'is_active' => true
]);
```

### Assigning Status to model
```php
...
$model->status_id = $status->id;
... 
$model->save();
```
**OR**
```php
// This will work for create() as well...
$model->update([
    ...
    'status_id' => $status->id,
    ...
]);
```

### Getting Model status information

Getting the model's current status
```php
$model->status;
// Returns the status model object
```

Getting the model's status updates
```php
$model->status_updates;
// Returns an Collection of status update model 
```

Getting the model's latest status updates
```php
$model->latestStatusUpdate();
// Returns the latest, in sequence, status update model object.
```

## Future Feature Listing
- Status Ordering
- Assigning Status to multiple Models
- UI Interface to provide CRUD functionality for Status Models

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Peter R Stanley](https://github.com/peterrstanley)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
