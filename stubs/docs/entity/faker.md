## {{ data.components.faker }}

The faker can be used for testing or seeding.

Create a new entity using the faker

```php
use {{ data.components.faker }};

$result = $manager->create({{ data.manager.getName() }}Faker::make()->parameters());
```

#### Extend the class

Create the new faker in `app/Fakers/{{ data.className }}Faker`
```php
namespace App\Fakers;

use {{ data.components.faker }} as Faker;

class {{ data.className }}Faker extends Faker {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'faker' => App\Fakers\{{ data.className}}Faker::class,
        ],
    ]
];
```


---
[Back](index.md)