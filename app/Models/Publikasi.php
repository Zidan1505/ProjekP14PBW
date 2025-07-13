<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_date', // Pastikan cocok dengan migrasi
        'cover_url',      // Pastikan cocok dengan migrasi
    ];
}