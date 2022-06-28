<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
       'bread/products/',
       'bread/products/create',
       'bread/products/show/{id}',
       'bread/products/update/{id}',
       'bread/products/delete/{id}',
       'bread/categories/',
    ];
}
