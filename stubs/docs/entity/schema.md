## {{ data.components.schema }}

The schema is used to define the structure of the attributes. All the $attributes in the [model](model.md) and in the [manager](manager.md) are initialized by the schema.

#### Extend the class

Create the new schema in `app/Schemas/{{ data.entityName }}Schema`
```php
namespace App\Schemas;

use {{ data.components.schema }} as Schema;

class {{ data.entityName }}Schema extends Schema {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'schema' => App\Schemas\{{ data.entityName}}Schema::class,
        ],
    ]
];
```

---
[Back](index.md)