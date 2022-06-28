<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 * @method static findOrFail($id)
 * @method static where(string $string)
 * * @method static find(mixed $input)
 */
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'category_id',
        'store_id',
        'number',
        'view',
        'product_code',
    ];

    const NAME ='name';
    const DESCRIPTION ='description';
    const IMAGE = 'image';
    const PRICE = 'price';
    const VIEW = 'view';
    const STATUS = 'status';
    const CATEGORY_ID = 'category_id';
    const STORE_ID = 'store_id';
    const PRODUCT_CODE = 'product_code';

    const ACTIVE = 'active';
    const BLOCK = 'block';

    const VIEW_NUMBER = '1';

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
