# Multitenancy Nova Tool

[![Total Downloads](https://img.shields.io/packagist/dt/romegadigital/multitenancy-nova-tool.svg?style=flat-square)](https://packagist.org/packages/romegadigital/multitenancy-nova-tool)

This package is meant to integrate with the [Multitenancy Package](https://github.com/romegadigital/Multitenancy) to bring multitenancy functionality and management to [Laravel's Nova](https://nova.laravel.com).

This package automatically includes the Multitenancy Package as a dependency. Please read the documentation on how to integrate it with your existing app.

- [Multitenancy Nova Tool](#multitenancy-nova-tool)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Define Inverse Relationships](#define-inverse-relationships)
  - [Middleware](#middleware)
  - [Policies](#policies)
  - [To Do](#to-do)

![index](https://user-images.githubusercontent.com/10154100/51259066-b21f2b80-19ab-11e9-8fac-b3ee5c20c1c2.png)

![create](https://user-images.githubusercontent.com/10154100/51259176-e85cab00-19ab-11e9-89e4-3474d38504dd.png)

## Installation

Install the package via Composer:

``` bash
composer require romegadigital/multitenancy-nova-tool
```

Then follow the [Installation](https://github.com/romegadigital/Multitenancy#installation) instructions to set up the Multitenancy Package.

Next, you must register the tool with Nova. This is typically done in the `tools` method of the `NovaServiceProvider`.

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

This package requires `Super Administrator` or `access admin` permissions. This can be added either through the included permission management tool under "Roles & Permissions" or through our [assign super-admin command](https://github.com/romegadigital/Multitenancy#console-commands).

> **Hint**
> If you already executed `multitenancy:install`, a role with the name `Super Administrator` and a permission `access admin` attached was already created. Therefore you only need to add the role to a user.
> ```bash
> php artisan multitenancy:super-admin admin@example.com
> ```

## Usage

New menu items labelled "Multitenancy" and "Roles & Permissions" will appear in your Nova app after installing this package.

To see the Tenant relation in the user detail view, add a `BelongsToMany` field to your `app/Nova/User` resource:

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

On each Nova resource that is tenantable, a `BelongsTo` field is required in order to see the relation to the `Tenant` model:

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

In order to display all related data to the `Tenant` model, you need to first implement a `Tenant` model that extends the package's provided model.

```php
// in app/Tenant.php

namespace App;

use RomegaDigital\Multitenancy\Models\Tenant as TenantModel;

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

To scope Nova results to the Tenant being utilized, add the middleware to Nova:

```php
// in config/nova.php

// ...

'middleware' => [
    // ...
    \RomegaDigital\Multitenancy\Middleware\TenantMiddleware::class,
],
```

Accessing Nova at the `admin` subdomain will remove scopes and display all results. Only users given the correct permissions, such as `Super Administrator`, will be able to access this subdomain.

## Policies

By default, the Multitenancy resource will only be visible on the `admin` subdomain to users with appropriate access to this subdomain. You may override the policy to allow more access to the resource by defining a policy within your project. And then within your `AuthServiceProvider`, register the policy:

```php
// in app/Providers/AuthServiceProvider.php

// ...
protected $policies = [
  // ...
    \RomegaDigital\Multitenancy\Models\Tenant::class => \App\Policies\TenantPolicy::class,
];
```

You can override the Permission and Role model policies by setting the policy file up in you `config/multitenancy.php` file. Look for `policies.role` and `policies.permission`.