<?php

namespace Railken\Amethyst\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Railken\Amethyst\Schemas\FooSchema;
use Railken\Lem\Contracts\EntityContract;

/**
 * @property float $calories
 */
class Foo extends Model implements EntityContract
{
    use SoftDeletes;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('amethyst.foo.managers.foo.table');
        $this->fillable = (new FooSchema())->getNameFillableAttributes();
    }
}
