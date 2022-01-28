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
class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
