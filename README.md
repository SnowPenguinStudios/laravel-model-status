# Snow Penguin Studios - Laravel Model Status

[![Latest Version on Packagist](https://badgen.net/packagist/v/snowpenguinstudios/laravel-model-status)](https://packagist.org/packages/snowpenguinstudios/laravel-model-status)
[![Total Downloads](https://badgen.net/packagist/dt/snowpenguinstudios/laravel-model-status?color=blue)](https://packagist.org/packages/snowpenguinstudios/laravel-model-status)
[![Package Contributors](https://img.shields.io/github/contributors/snowpenguinstudios/laravel-model-status?color=blue)](../../contributors)
[![Package License](https://badgen.net/github/license/snowpenguinstudios/laravel-model-status)](License.md)

[![GitHub Tests Action Status](https://badgen.net/github/checks/snowpenguinstudios/laravel-model-status/master/test?label=tests)](https://github.com/snowpenguinstudios/laravel-model-status/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://badgen.net/github/checks/snowpenguinstudios/laravel-model-status/master?label=code%20style)](https://github.com/snowpenguinstudios/laravel-model-status/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
![Package Last Commit](https://badgen.net/github/last-commit/snowpenguinstudios/laravel-model-status)

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

### Working With The Status Model
The following attributes are allowed to be set for mass assignment:
`model`, `name`, `description`, `is_default`, `is_active` and `order`.

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

### Assigning Status To Model
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

### Getting a Models Available Statuses

Provides an array of all statuses (including the statues not assigned to a model) available for a model:
```php
$availableStatuses = DataModdel::availableStatuses();
```

Provides the default status for a model (note that if a Model specific and non-model specific status has a default, the model specific will be default):
```php
$defaultStatus = DataModdel::defaultStatus();
```

### The ability to sort by the Status Order

Default Order will be in ascending order:
```php
$orderedStatuses = Status::order()->get();
```

Order by order field, ascending.
```php
$orderedStatuses = Status::order('asc')->get();
```

Order by order field, descending.
```php
$orderedStatuses = Status::order('desc')->get();
```

Order By a certain Model
```php
    $orderedStatuses = Status::where('model', DataModel::class)->order()->get();
```
**OR**
```php
    $orderedAscStatuses = DataModel::availableStatuses('asc');
    $orderedDescStatuses = DataModel::availableStatuses('desc');
```

### Getting Model Status Information

Getting the model's current status
```php
$model->status;
// Returns the status model object
```

Getting the model's status updates
```php
$model->status_updates;
// Returns a Collection of status update model 
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
