## {{ data.components.faker }}

The faker can be used for testing or seeding.

Create a new entity using the faker

```php
use {{ data.components.faker }};

$result = $manager->create({{ data.manager.entityName }}Faker::make()->parameters());
```

#### Extend the class

Create the new faker in `app/Fakers/{{ data.entityName }}Faker`
```php
namespace App\Fakers;

use {{ data.components.faker }} as Faker;

class {{ data.entityName }}Faker extends Faker {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'faker' => App\Fakers\{{ data.entityName}}Faker::class,
        ],
    ]
];
```


---
[Back](index.md)