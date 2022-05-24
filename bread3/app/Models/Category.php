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
class Category extends Model
{
    use HasFactory;

    const NAME = 'name';
    const IMAGE = 'image';
    const DESCRIPTION = "description";
    const STATUS = "status";
    const CREATED_AT = "created_at";
    const CREATED_BY = "created_by";
    const UPDATED_AT  = "updated_at";
    const UPDATED_BY  = "updated_by";

    const ACTIVE = 'active';
    const BLOCK = 'block';

    protected $fillable = [
        'name',
        'image',
        'description',
        'status',
        'created_at',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
