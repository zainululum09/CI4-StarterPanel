<?php
if (!function_exists('paginate_manual')) {
    /**
     * Generate pagination HTML secara manual.
     *
     * @param int $totalRows Jumlah total data
     * @param int $perPage   Jumlah data per halaman
     * @param int $currentPage Halaman aktif saat ini
     * @param string $baseUrl URL dasar (tanpa parameter ?page=)
     * @param int $numLinks  Jumlah nomor halaman di sekitar halaman aktif
     * @return string HTML pagination
     */
    
    function paginate_manual($totalRows, $perPage, $currentPage, $baseUrl, $numLinks = 2)
    {
        $totalPages = ceil($totalRows / $perPage);
        if ($totalPages <= 1) return '';

        $html = '<nav class="pagination-container">';
        $html .= '<ul class="pagination">';

        // Tombol Sebelumnya
        if ($currentPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '/siswa?page=1"><i class="align-middle" data-feather="chevrons-left"></i></a></li>
            <li class="page-item"><a class="page-link" href="' . $baseUrl . '/siswa?page=' . ($currentPage - 1) . '"><i class="align-middle" data-feather="chevron-left"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="disabled page-link"><i class="align-middle" data-feather="chevrons-left"></i></a></li>';
        }

        // Nomor halaman di sekitar halaman aktif
        $start = max(1, $currentPage - $numLinks);
        $end   = min($totalPages, $currentPage + $numLinks);

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active"><a class="page-link" disabled>' . $i . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '/siswa?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // Tombol Selanjutnya
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '/siswa?page=' . ($currentPage + 1) . '"><i class="align middle" data-feather="chevron-right"></i></a></li>
            <li class="page-item"><a class="page-link" href="' . $baseUrl . '/siswa?page=' . $totalPages . '"><i class="align middle" data-feather="chevrons-right"></i></a></li>';
        } else {
            $html .= '<li class="page-item disabled"><a class="page-link disabled"><i class="align middle" data-feather="chevrons-right"></i></a></li>';
        }

        $html .= '</ul></nav>';
        return $html;
    }

}

if (!function_exists('job')) {
    function job($kode)
    {
        switch ($kode) {
            case 1: return "Tidak Bekerja";
            case 2: return "Nelayan";
            case 3: return "Petani";
            case 4: return "Peternak";
            case 5: return "PNS/TNI/Polri";
            case 6: return "Karyawan Swasta";
            case 7: return "Pedagang Kecil";
            case 8: return "Pedagang Besar";
            case 9: return "Wiraswasta";
            case 10: return "Wirausaha";
            case 11: return "Buruh";
            case 12: return "Pensiunan";
            case 13: return "Tenaga Kerja Indonesia";
            case 14: return "Karyawan BUMN";
            case 15: return "Tidak dapat diterapkan";
            case 16: return "Sudah Meninggal";
            case 99: return "Lainnya";
            default: return "-";
        }
    }
}

if (!function_exists('pendidikan')) {
    function pendidikan()
    {
        $list_pendidikan ="
            <option value='-'> - </option>
            <option value='D1'> D1 </option>
            <option value='D2'> D2 </option>
            <option value='D3'> D3 </option>
            <option value='D4'> D4 </option>
            <option value='Informal'> Informal </option>
            <option value='Lainnya'> Lainnya </option>
            <option value='Non Formal'> Non Formal </option>
            <option value='Paket A'> Paket A </option>
            <option value='Paket B'> Paket B </option>
            <option value='Paket C'> Paket C </option>
            <option value='PAUD'> PAUD </option>
            <option value='Profesi'> Profesi </option>
            <option value='Putus SD'> Putus SD </option>
            <option value='S1'> S1 </option>
            <option value='S2'> S2 </option>
            <option value='S2 Terapan'> S2 Terapan </option>
            <option value='S3'> S3 </option>
            <option value='S3 Terapan'> S3 Terapan </option>
            <option value='SD / Sederajat'> SD / Sederajat </option>
            <option value='SMP / Sederajat'> SMP / Sederajat </option>
            <option value='SMA / Sederajat'> SMA / Sederajat </option>
            <option value='Sp-1'> Sp-1 </option>
            <option value='Sp-2'> Sp-2 </option>
            <option value='Tidak Sekolah'> Tidak Sekolah </option>
            <option value='TK / Sederajat'> TK / Sederajat </option>";
        return $list_pendidikan;
    }
}

if (!function_exists('penghasilan')) {
    function penghasilan()
    {
        $list_penghasilan = "
            <option value='-'>-</option>
            <option value='Kurang dari Rp. 500.000'>Kurang dari Rp. 500.000</option>
            <option value='Rp. 500.000 - Rp. 999.999'>Rp. 500.000 - Rp. 999.999</option>
            <option value='Rp. 1.000.000 - Rp. 1.999.999'>Rp. 1.000.000 - Rp. 1.999.999</option>
            <option value='Rp. 2.000.000 - Rp. 4.999.999'>Rp. 2.000.000 - Rp. 4.999.999</option>
            <option value='Rp. 5.000.000 - Rp. 20.000.000'>Rp. 5.000.000 - Rp. 20.000.000</option>
            <option value='Lebih Dari Rp. 20.000.000'>Lebih Dari Rp. 20.000.000</option>
            <option value='Tidak Berpenghasilan'>Tidak Berpenghasilan</option>";

        return $list_penghasilan;
    }

}

if (!function_exists('rupiah')) {
    function rupiah($angka, int $desimal = 0): string
    {
        $angka = preg_replace('/[^0-9.]/', '', str_replace(',', '.', $angka));
        $hasil_rupiah = "Rp " . number_format($angka, $desimal, ',', '.');
        return $hasil_rupiah;
    }
}

function clean_and_format_date($input) {
    $tanggal_lahir_bersih = trim($input ?? '');

    if (empty($tanggal_lahir_bersih)) {
        return null;
    } 

    $date_parts = explode('/', $tanggal_lahir_bersih);
    
    if (count($date_parts) === 3) {
        $safe_date_string = "{$date_parts[1]}/{$date_parts[0]}/{$date_parts[2]}";

        $timestamp = strtotime($safe_date_string);
    } else {
        $timestamp = strtotime($tanggal_lahir_bersih);
    }

    if ($timestamp) {
        return date('Y-m-d', $timestamp);
    } else {
        return null; 
    }
}

if (!function_exists('compressImage')) {
    function compressImage($sourcePath, $destinationPath, $quality = 75) {
        
        $imageInfo = getimagesize($sourcePath);
        $mime = $imageInfo['mime'];
        
        $image = null;
        
        // Buat sumber gambar
        if ($mime == 'image/jpeg' || $mime == 'image/jpg') {
            $image = imagecreatefromjpeg($sourcePath);
        } elseif ($mime == 'image/png') {
            $image = imagecreatefrompng($sourcePath);
            // Nonaktifkan blending dan simpan transparansi (penting untuk PNG)
            imagealphablending($image, false);
            imagesavealpha($image, true);
        } elseif ($mime == 'image/gif') {
            $image = imagecreatefromgif($sourcePath);
        } else {
            return false;
        }

        if ($image !== null) {
            // Jika formatnya PNG dan Anda ingin mempertahankan transparansi/kualitas PNG
            if ($mime == 'image/png') {
                // Kualitas PNG berkisar 0 (tanpa kompresi) hingga 9 (kompresi maksimum)
                // Kita balik kualitasnya karena 75% kualitas JPEG ~ 5-6 kualitas kompresi PNG
                $compressionLevel = 9 - round($quality / 100 * 9); 
                $success = imagepng($image, $destinationPath, $compressionLevel);
            } else {
                // Simpan sebagai JPEG dengan kualitas yang ditentukan
                $success = imagejpeg($image, $destinationPath, $quality);
            }
            
            imagedestroy($image);
            return $success;
        }
        
        return false;
    }
}

function tinggal_bersama()
{
    $list_tinggal = "
        <option value='Bersama Orangtua'> Bersama Orangtua </option>
        <option value='Wali'> Wali </option>
        <option value='Kost'> Kost </option>
        <option value='Asrama'> Asrama </option>
        <option value='Panti Asuhan'> Panti Asuhan </option>
        <option value='Pesantren'> Pesantren </option>
        <option value='Lainnya'> Lainnya </option>
    ";
    return $list_tinggal;
}

function jenis_transportasi()
{
    $list_transport = "
        <option value='Jalan Kaki'> Jalan Kaki </option>
        <option value='Angkutan Umum/bus/pete-pete'> Angkutan Umum/bus/pete-pete </option>
        <option value='Mobil/bus antar jemput'> Mobil/bus antar jemput </option>
        <option value='Kereta Api'> Kereta Api </option>
        <option value='Ojek'> Ojek </option>
        <option value='Andong/bendi/sado/dokar/delman/becak'> Andong/bendi/sado/dokar/delman/becak </option>
        <option value='Perahu penyeberangan/rakit/getek'> Perahu penyeberangan/rakit/getek </option>
        <option value='Kuda'> Kuda </option>
        <option value='Sepeda'> Sepeda </option>
        <option value='Sepeda motor'> Sepeda motor </option>
        <option value='Mobil pribadi'> Mobil pribadi </option>
        <option value='Lainnya'> Lainnya </option>
    ";

    return $list_transport;
}

if (!function_exists('tanggal_indo')) {
    /**
     * Format tanggal dan waktu ke bahasa Indonesia
     * @param string $tanggal Tanggal dalam format YYYY-MM-DD atau datetime YYYY-MM-DD HH:MM:SS
     * @param bool $cetak_hari Jika true, sertakan nama hari di depan
     * @return string Tanggal dalam format Indonesia
     */
    function tanggal_indo(string $tanggal, bool $cetak_hari = false): string
    {
        // 1. Array Nama Hari dan Bulan
        $hari = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // 2. Konversi ke Timestamp
        $timestamp = strtotime($tanggal);
        if ($timestamp === false) {
            return $tanggal; // Kembalikan nilai asli jika format tidak valid
        }

        // 3. Ekstraksi Komponen Tanggal
        $tgl_indo = date('d', $timestamp) . ' ' . $bulan[(int)date('m', $timestamp)] . ' ' . date('Y', $timestamp);
        
        // 4. Tambahkan Nama Hari (Opsional)
        if ($cetak_hari) {
            $nama_hari = date('w', $timestamp);
            $tgl_indo = $hari[$nama_hari] . ', ' . $tgl_indo;
        }

        return $tgl_indo;
    }
}

if (!function_exists('waktu_indo')) {
    /**
     * Format tanggal dan waktu lengkap (termasuk jam) ke bahasa Indonesia
     * @param string $datetime Tanggal dan waktu dalam format YYYY-MM-DD HH:MM:SS
     * @param bool $cetak_hari Jika true, sertakan nama hari di depan
     * @return string Tanggal dan waktu dalam format Indonesia
     */
    function waktu_indo(string $datetime, bool $cetak_hari = false): string
    {
        $tanggal_saja = tanggal_indo($datetime, $cetak_hari);
        
        // Ambil Jam dan Menit
        $jam_menit = date('H:i', strtotime($datetime));
        
        return $tanggal_saja . ' Pukul ' . $jam_menit . ' WIB';
    }
}
