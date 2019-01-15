# Multitenancy Nova Tool

This package is meant to integrate with the [Multitenancy Package](https://github.com/bradenkeith/Multitenancy) to bring multitenancy functionality and management to [Laravel's Nova](https://nova.laravel.com).

This package automatically includes the Multitenancy Package as a dependency. Please read the documentation on how to integrate it with your existing app.

- [Multitenancy Nova Tool](#multitenancy-nova-tool)
  - [Installation](#installation)
  - [Middleware](#middleware)
  - [Usage](#usage)
  - [To Do](#to-do)

![index](https://user-images.githubusercontent.com/10154100/51168828-aa2b9280-18aa-11e9-9ed8-611888042bb1.png)

![create](https://user-images.githubusercontent.com/10154100/51168820-a566de80-18aa-11e9-951d-b70c2553524b.png)

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

Finally, add a `BelongsToMany` fields to you `app/Nova/User` resource:

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

Also don't forget to add a `BelongsTo` field to all resources whose models do use the `BelongsToTenant` Trait:

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

## Usage

A new menu item called "Multitenancy" will appear in your Nova app after installing this package.


## To Do

- [x] add screenshots
- [ ] define adding permissions
- [ ] define adding BelongsTo to relational data
- [ ] extending the Nova Tenant resource to include relational data (reverse relationship definition)
- [ ] add vyuldashev/nova-permission as a dependency
- [ ] find a better sidebar navigation icon