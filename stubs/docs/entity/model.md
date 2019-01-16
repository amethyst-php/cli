## {{ data.components.model }}

The model extends ```Illuminate\Database\Eloquent\Model``` class.

#### Update the table name
You have to go in the file `configs/amethyst.{{data.package}}` in order to change the table name.

#### Extend the class

Create the new model in `app/Models/{{ data.entityName }}`
```php
namespace App\Models;

use {{ data.components.model }} as Model;

class {{ data.entityName }} extends Model {
	// ...
}
```
Update the file `configs/amethyst.{{data.package}}` with the new class
```php
return [
    'data' => [
        '{{ data.name }}' => [
            'model' => App\Models\{{ data.entityName}}::class,
        ],
    ]
];
```

---
[Back](index.md)