# SimpleAclBundle

> The easiest way to dynamic Access Control List

This bundle is a wrapper of this [ACL library](https://github.com/AlexDpy/Acl).
You want some dynamic ACL ?
You think that the symfony/acl component is overkill and really hard to work on it ?
You want an "easy to use" bundle ? This bundle is the answer !


## Install
```sh
$ composer require alexdpy/simple-acl-bundle
```

## Enable the bundle

*app/AppKernel.php*:
```php
<?php
// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AlexDpy\AclBundle\AlexDpyAclBundle(),
        );
        // ...
    }
    // ...
}
```

## Update your database schema

@see https://github.com/AlexDpy/Acl#update-your-database-schema


## Configuration

#### Create a DatabaseProvider service
Choose an existing [DatabaseProvider](https://github.com/AlexDpy/Acl/tree/master/src/Database/Provider) or create your own, and make it as a service.

*app/config/services.yml*:
```yml
services:
    app.acl.database_provider:
        class: AlexDpy\Acl\Database\Provider\DoctrineDbalProvider
        arguments:
            - @doctrine.dbal.default_connection
```

#### Add a little configuration
*app/config/config.yml*:
```yml
alex_dpy_simple_acl:
    database_provider: app.acl.database_provider
```

#### Let's go !

All is OK.
```php
<?php

$acl = $this->container->get('alex_dpy_simple_acl.acl');
```
@see https://github.com/AlexDpy/Acl#usage for usage.


#### Use a CacheProvider

The ACL library uses DoctrineCache.
The easiest way to create a CacheProvider service is to use DoctrineCacheBundle.
```sh
$ composer require doctrine/doctrine-cache-bundle
```
*app/config/config.yml*:
```yml
doctrine_cache:
    providers:
        acl_cache:
            type: apc
            namespace: simple_acl

alex_dpy_simple_acl:
    database_provider: app.acl.database_provider
    cache_provider: doctrine_cache.providers.acl_cache
```

@see https://github.com/AlexDpy/Acl#cache


#### Schema options

*app/config/config.yml*:
```yml
alex_dpy_simple_acl:
    database_provider: app.acl.database_provider
    schema:
        permissions_table_name: acl_perm
        requester_column_length: 100
        resource_column_length: 100
```


#### Custom MaskBuilder

*app/config/config.yml*:
```yml
alex_dpy_simple_acl:
    database_provider: app.acl.database_provider
    mask_builder_class: My\Custom\MaskBuilder
```

@see https://github.com/AlexDpy/Acl#the-maskbuilder

## Usage

@see https://github.com/AlexDpy/Acl#usage

## License

[MIT](./LICENSE)
