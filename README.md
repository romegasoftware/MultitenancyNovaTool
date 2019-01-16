# Multitenancy Nova Tool

This package is meant to integrate with the [Multitenancy Package](https://github.com/bradenkeith/Multitenancy) to bring multitenancy functionality and management to [Laravel's Nova](https://nova.laravel.com).

This package automatically includes the Multitenancy Package as a dependency. Please read the documentation on how to integrate it with your existing app.

- [Multitenancy Nova Tool](#multitenancy-nova-tool)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Define Reverse Relationships](#define-reverse-relationships)
  - [Middleware](#middleware)
  - [To Do](#to-do)

![index](https://user-images.githubusercontent.com/10154100/51259066-b21f2b80-19ab-11e9-8fac-b3ee5c20c1c2.png)

![create](https://user-images.githubusercontent.com/10154100/51259176-e85cab00-19ab-11e9-89e4-3474d38504dd.png)

## Installation

You can install the package via composer:

``` bash
composer require romegadigital/multitenancy-nova-tool
```

Go through the [Installation](https://github.com/bradenkeith/Multitenancy#installation) section in order to setup the [Multitenancy Package](https://packagist.org/packages/spatie/laravel-permission).

Next up, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ...
        new \RomegaDigital\MultitenancyNovaTool\MultitenancyNovaTool,
    ];
}
```

Our package requires `Super Administrator` or `access admin` permissions. This can be added either through the included permission management tool under "Roles & Permissions" or through our assign super-admin command.

**hint**
If you already executed `multitenancy:install` a role with the name `Super Administrator` who has a permission `access admin` attached was already created. Therefore you only need to add the role to a user.

```bash
php artisan multitenancy:super-admin admin@email.com
```

**warning**
If you don't add the required role to a user you won't be able to use the Multitenancy-Tool within nova.

## Usage

A new menu item called "Multitenancy" && "Roles & Permissions" will appear in your Nova app after installing this package.

To see the tenant relation in the user detail view, add a `BelongsToMany` fields to you `app/Nova/User` resource:

```php
// in app/Nova/User.php

use Laravel\Nova\Fields\BelongsToMany;

public function fields(Request $request)
{
    return [
        // ...
        BelongsToMany::make('Tenants', 'tenants', \RomegaDigital\MultitenancyNovaTool\Tenant::class),
    ];
}
```

On each nova resource that is tenantable a `BelongsTo`-Field is required in order to see the relation to our `Tenant`-Model:

```php
use Laravel\Nova\Fields\BelongsTo;

public function fields(Request $request)
{
    return [
        // ...
        BelongsTo::make('Tenants', 'tenant', \RomegaDigital\MultitenancyNovaTool\Tenant::class),
    ];
}
```

## Define Reverse Relationships

In order to display all related data to our `Tenant`-Model, you need to define the reverse relationships in the `multitenancy` config.

```php
'tenant_has_many_relations' => [
    'products' => \App\Product::class
],
```

The key is used to identify the name for the relationship. If you add `products` like in the example above it will result in adding a `HasMany`-field to the `Tenant`-resource:

```php
use Laravel\Nova\Fields\HasMany;

public function fields(Request $request)
{
    return [
        // ...
        HasMany::make('Products', 'products', \App\Nova\Product::class),
    ];
}
```

## Middleware

To scope Nova results to the tenant being utilized, add the middleware to Nova:

```php
// in config/nova.php

// ...

'middleware' => [
    // ...
    \RomegaDigital\Multitenancy\Middlewares\TenantMiddleware::class,
],
```

Accessing Nova at the `admin` subdomain will remove scopes and display all results. Only users given the correct permissions will be able to access this subdomain.


## To Do

- [x] add screenshots
- [ ] define adding permissions
- [ ] define adding BelongsTo to relational data
- [ ] extending the Nova Tenant resource to include relational data (reverse relationship definition)
- [ ] add vyuldashev/nova-permission as a dependency
- [ ] find a better sidebar navigation icon