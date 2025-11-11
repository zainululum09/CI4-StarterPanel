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
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1"><i class="align-middle" data-feather="chevrons-left"></i></a></li>
            <li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '"><i class="align-middle" data-feather="chevron-left"></i></a></li>';
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
                $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }

        // Tombol Selanjutnya
        if ($currentPage < $totalPages) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '"><i class="align middle" data-feather="chevron-right"></i></a></li>
            <li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . '"><i class="align middle" data-feather="chevrons-right"></i></a></li>';
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
