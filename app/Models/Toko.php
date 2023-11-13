<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'logo_toko',
        'nohp_toko',
        'ig_toko',
        'alamat_toko',
    ];
}
