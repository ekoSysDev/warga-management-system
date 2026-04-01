<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasukanDetail extends Model
{
    use HasFactory;
    protected $table = 'pemasukandetail';
    // protected $guarded = [];
    protected $fillable = [
        'terima_id',
        'periode_bayar',
        'aktifyn',
    ];
}
