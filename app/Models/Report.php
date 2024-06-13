<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $fillable = [
        'id',
        'nama_bts',
        'tingkat_kepentingan',
        'kategori',
        'deskripsi',
        'status',
        'catatan',
        'tanggal_selesai',
    ];

    public $incrementing = false;
}
