## {{ data.components.serializer }}

The serializer is used to serialize an entity, you can retrieve it from the data.

```php
use {{ data.components.manager }};

$manager = new {{ data.manager.name }}();

$serializer = $manager->getSerializer();

```

And use it so serialize an entity
Retrieving an entity

```php
$entity = $repository->findOneById(1);
$serializer->serialize($entity)->toArray(); // Returns an array

```
#### Extend the class

Create the new serializer in `app/Serializers/{{ data.entityName }}Serializer`
```php
namespace App\Serializers;

use {{ data.components.serializer }} as Serializer;

class {{ data.entityName }}Serializer extends Serializer {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'serializer' => App\Serializers\{{ data.entityName}}Serializer::class,
        ],
    ]
];
```

---
[Back](index.md)