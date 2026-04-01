<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;
    protected $table = 'pemasukan';
    // protected $guarded = [];
    protected $fillable = [
        'tanggal',
        'warga_id',
        'jenisbayar_id',
        'totalbayar',
        'keterangan',
        'carabayar',
        'aktifyn',
    ];
    
    // 🔥 RELASI KE WARGA
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    // 🔥 RELASI KE JENIS PEMBAYARAN (FIX BUG KAMU DI SINI)
    public function jenispembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'jenisbayar_id');
    }

    // 🔥 DETAIL BULAN
    public function detail()
    {
        return $this->hasMany(PemasukanDetail::class, 'terima_id');
    }
}
