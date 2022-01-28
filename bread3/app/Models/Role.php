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
class Role extends Model
{
    use HasFactory;

    /**
     * @var int|mixed
     */
    protected $fillable = [
        'name',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
