<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'gender',
        'country',
    ];
}
