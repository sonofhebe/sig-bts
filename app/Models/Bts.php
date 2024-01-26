<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bts extends Model
{
    use HasFactory;

    protected $table = 'bts';
    protected $fillable = [
        'id',
        'title',
        'alamat',
        'longitude',
        'latitude',
        'jumlah_antena',
        'frekuensi',
        'teknologi_jaringan',
        'luas_jaringan',
        'kapasitas_user',
        'informasi_lain',
    ];

    public $incrementing = false;

    // public function tproduk()
    // {
    //     return $this->hasMany(TProduk::class, 'id_produk', 'id');
    // }
}
