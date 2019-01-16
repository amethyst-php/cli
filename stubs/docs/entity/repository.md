## {{ data.components.repository }}

The repository is the class to perform queries.

```php
use {{ data.components.manager }};

$manager = new {{ data.manager.name }}();

$repository = $manager->getRepository();

```

Retrieving an entity

```php
$repository->findOneBy(['id' => 1]);
$repository->findOneById(1);

```

Retrieving all entities

```php
$repository->findAll();
```

Performing a query using \Illuminate\DataBase\Eloquent\Builder

```php
$repository->newQuery()->where('id', 1)->first();

```

#### Extend the class

Create the new repository in `app/Repositories/{{ data.entityName }}Repository`
```php
namespace App\Repositories;

use {{ data.components.repository }} as Repository;

class {{ data.entityName }}Repository extends Repository {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'repository' => App\Repositories\{{ data.entityName}}Repository::class,
        ],
    ]
];
```

---
[Back](index.md)