<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Models\Jamaah;
use PhpOffice\PhpSpreadsheet\IOFactory;   // ⬅️ pakai ini

class JamaahController extends Controller
{
    // Halaman list jamaah
    public function index()
    {
        $jamaah = Jamaah::orderBy('nama_jamaah')->paginate(50);
        return view('admin.jamaah.index', compact('jamaah'));
    }

    // Halaman upload Excel
    public function importForm()
    {
        return view('admin.jamaah.import');
    }

    // Proses upload Excel (pakai PhpSpreadsheet)
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    $file = $request->file('file');
    $path = $file->getRealPath();

    // Load file Excel
    $spreadsheet = IOFactory::load($path);
    $sheet       = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestDataRow();

    $inserted = 0;
    $errors   = [];

    // Mulai dari baris 2 karena baris 1 = header
    for ($row = 2; $row <= $highestRow; $row++) {

        // Baca semua kolom yang dibutuhkan
        $nama          = trim((string) $sheet->getCell('A' . $row)->getValue());
        $noPaspor      = trim((string) $sheet->getCell('B' . $row)->getValue());
        $noHp          = trim((string) $sheet->getCell('C' . $row)->getValue());
        $jenisKelRaw   = trim((string) $sheet->getCell('D' . $row)->getValue());
        $tglLahirRaw   = $sheet->getCell('E' . $row)->getValue();
        $kodeKloter    = trim((string) $sheet->getCell('F' . $row)->getValue());
        $nomorBus      = trim((string) $sheet->getCell('G' . $row)->getValue());
        $keterangan    = trim((string) $sheet->getCell('H' . $row)->getValue());

        // Kalau baris kosong total, skip
        if ($nama === '' && $noPaspor === '' && $noHp === '' && $jenisKelRaw === '' && empty($tglLahirRaw)) {
            continue;
        }

        // --------------------------
        // 1. Validasi & normalisasi nama
        // --------------------------
        if ($nama === '') {
            $errors[] = "Baris {$row}: nama_jamaah kosong, dilewati.";
            continue;
        }

        // --------------------------
        // 2. Normalisasi jenis kelamin -> L / P / null
        // --------------------------
        $jkLower = strtolower($jenisKelRaw);
        $jenisKelamin = null;

        if (in_array($jkLower, ['l', 'laki', 'laki-laki', 'lk'], true)) {
            $jenisKelamin = 'L';
        } elseif (in_array($jkLower, ['p', 'perempuan', 'pr'], true)) {
            $jenisKelamin = 'P';
        } elseif ($jkLower !== '') {
            // Ada isi tapi tidak dikenali
            $errors[] = "Baris {$row}: jenis_kelamin '{$jenisKelRaw}' tidak dikenal, dilewati.";
            continue;
        }

        // --------------------------
        // 3. Konversi tanggal lahir ke Y-m-d
        // --------------------------
        $tanggalLahir = null;

        if (!empty($tglLahirRaw)) {
            try {
                if (is_numeric($tglLahirRaw)) {
                    // Format angka Excel
                    $dt = ExcelDate::excelToDateTimeObject($tglLahirRaw);
                    $tanggalLahir = $dt->format('Y-m-d');
                } else {
                    $tglStr = trim((string) $tglLahirRaw);

                    // Coba beberapa format umum
                    $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y'];
                    $parsed  = null;

                    foreach ($formats as $fmt) {
                        $dt = \DateTime::createFromFormat($fmt, $tglStr);
                        if ($dt && $dt->format($fmt) === $tglStr) {
                            $parsed = $dt;
                            break;
                        }
                    }

                    if ($parsed) {
                        $tanggalLahir = $parsed->format('Y-m-d');
                    } else {
                        $errors[] = "Baris {$row}: format tanggal_lahir '{$tglStr}' tidak dikenali, di-set NULL.";
                    }
                }
            } catch (\Throwable $e) {
                $errors[] = "Baris {$row}: gagal mengonversi tanggal_lahir, di-set NULL.";
            }
        }

        // --------------------------
        // 4. Simpan ke database
        // --------------------------
        try {
            Jamaah::create([
                'nama_jamaah'   => $nama,
                'no_paspor'     => $noPaspor ?: null,
                'no_hp'         => $noHp ?: null,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => $tanggalLahir,
                'kode_kloter'   => $kodeKloter ?: null,
                'nomor_bus'     => $nomorBus ?: null,
                'keterangan'    => $keterangan ?: null,
            ]);

            $inserted++;
        } catch (\Throwable $e) {
            $errors[] = "Baris {$row}: gagal insert ke database ({$e->getMessage()}).";
        }
    }

    // Buat pesan sukses komplit
    $message = "Berhasil import {$inserted} baris jamaah.";
    if (!empty($errors)) {
        $message .= " {$this->shortenErrorsForFlash($errors)}";
        // atau kalau mau sederhana: $message .= " Beberapa baris dilewati, cek detail di bawah.";
    }

    return redirect()
        ->route('jamaah.index')
        ->with('success', $message)
        ->with('import_errors', $errors);
}

// Opsional: helper private untuk merapikan message (boleh dihapus kalau nggak mau)
private function shortenErrorsForFlash(array $errors): string
{
    // Maksimal 3 error yang ditampilkan di flash message singkat
    $sample = array_slice($errors, 0, 3);
    $text   = 'Catatan: ' . implode(' | ', $sample);
    if (count($errors) > 3) {
        $text .= ' (+' . (count($errors) - 3) . ' baris lain)';
    }
    return $text;
}

}
