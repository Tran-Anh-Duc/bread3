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
class Log_products extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'view',
        'product_code',
    ];
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const PRICE = 'price';
    const VIEW = 'view';
    const PRODUCT_CODE = 'product_code';
}
