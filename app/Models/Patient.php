<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'register_id';

    protected $fillable = [
        'x_ray_id',
        'name',
        'father_name',
        'sex',
        'age',
        'diagnosis',
        'comment',
        'images',
        'treatment',
        'doctor_name',
    ];

    protected $casts = [
        'images' => 'array',
        'age' => 'integer',
    ];
}


