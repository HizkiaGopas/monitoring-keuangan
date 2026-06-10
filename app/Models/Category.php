<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Beritahu Laravel bahwa model ini memegang tabel 'categories'
    protected $table = 'categories';

    // Daftarkan kolom yang boleh diisi (mass assignable)
    protected $fillable = ['user_id', 'name', 'type'];
}