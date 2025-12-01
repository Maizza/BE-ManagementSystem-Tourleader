<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamaah extends Model
{
    use HasFactory;

    // kalau nama tabel "jamaahs", ini sebenarnya boleh di-skip
    protected $table = 'jamaahs';

    protected $fillable = [
        'nama_jamaah',
        'no_paspor',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'kode_kloter',
        'nomor_bus',
        'keterangan',
    ];

    // Relasi: satu jamaah punya banyak record absensi
    public function attendanceJamaah()
    {
        return $this->hasMany(AttendanceJamaah::class, 'jamaah_id');
    }
}
