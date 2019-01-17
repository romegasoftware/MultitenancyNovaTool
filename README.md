# Multitenancy Nova Tool

This package is meant to integrate with the [Multitenancy Package](https://github.com/bradenkeith/Multitenancy) to bring multitenancy functionality and management to [Laravel's Nova](https://nova.laravel.com).

This package automatically includes the Multitenancy Package as a dependency. Please read the documentation on how to integrate it with your existing app.

- [Multitenancy Nova Tool](#multitenancy-nova-tool)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Define Inverse Relationships](#define-inverse-relationships)
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

Our package requires `Super Administrator` or `access admin` permissions. This can be added either through the included permission management tool under "Roles & Permissions" or through our [assign super-admin command](https://github.com/bradenkeith/Multitenancy#console-commands).

> **hint**
> If you already executed `multitenancy:install`, a role with the name `Super Administrator` who has a permission `access admin` attached was already created. Therefore you only need to add the role to a user.
> ```bash
> php artisan multitenancy:super-admin admin@example.com
> ```

## Usage

A new menu item called "Multitenancy" & "Roles & Permissions" will appear in your Nova app after installing this package.

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

On each nova resource that is tenantable, a `BelongsTo` field is required in order to see the relation to our `Tenant` model:

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

## Define Inverse Relationships

In order to display all related data to our `Tenant` model, you need to first implement a `Tenant` model that extends the package's provided model.

```php
// in app/Tenant.php

namespace App\Nova;

use RomegaDigital\Multitenancy\Models\Tenant as TenantModel;
use RomegaDigital\Multitenancy\Traits\BelongsToTenant;

class Tenant extends TenantModel
{
  // ... define relationships
  public function products()
  {
    return $this->hasMany(\App\Product::class);
  }
}
```

Next, update your config file to point to your new model.

```php
// in config/multitenancy.php

// ...
'tenant_model' => \App\Tenant::class,
```

Then create a Tenant Nova resource that extends the package's resource.

```php
// in app/Nova/Tenant.php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use RomegaDigital\MultitenancyNovaTool\Tenant as TenantResource;

class Tenant extends TenantResource
{
  public static $model = \App\Tenant::class;

  /**
   * Get the fields displayed by the resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function fields(Request $request)
  {
    return array_merge(parent::fields($request),
    [
      // ... define relationships
      HasMany::make('Products'),
    ]);
  }

}
```

## Middleware

To scope Nova results to the tenant being utilized, add the middleware to Nova:

```php
// in config/nova.php

// ...

'middleware' => [
    // ...
    \RomegaDigital\Multitenancy\Middleware\TenantMiddleware::class,
],
```

Accessing Nova at the `admin` subdomain will remove scopes and display all results. Only users given the correct permissions will be able to access this subdomain.


## To Do

- [x] add screenshots
- [x] define adding permissions
- [x] define adding BelongsTo to relational data
- [x] extending the Nova Tenant resource to include relational data (inverse relationship definition)
- [X] add vyuldashev/nova-permission as a dependency
- [x] find a better sidebar navigation icon
- [ ] add romegadigital/multitenancy as a dependency
- [ ] add documentation around defining access policies (revisit current definition of allowing all CRUD operations)