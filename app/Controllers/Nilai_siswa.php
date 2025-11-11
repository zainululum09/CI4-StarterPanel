<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DapodikModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Nilai_siswa extends BaseController
{
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }

    public function index()
    {
        $rombel = $this->dapodikModel->getRombel();
        $data = array_merge($this->data, [
            'title'         => 'Nilai Siswa',
            'rombel'        => $rombel
        ]);
        return view('pages/commons/nilai_siswa', $data);
    }

    public function daftar_nilai($id)
    {
        $rombel = $this->dapodikModel->getAnggotaRombel($id);
        $mapel = $this->dapodikModel->getMapel();
        $data = array_merge($this->data, [
            'title'         => 'Nilai Siswa Kelas',
            'rombel'        => $rombel,
            'mapel'         => $mapel
        ]);
        return view('pages/commons/daftar_nilai_kelas', $data);

    }
    
    public function upload()
    {
        // Pastikan ini adalah permintaan POST dan AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Akses ditolak.']);
        }
        
        $file = $this->request->getFile('file_excel');

        // Validasi dasar
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => '❌ Gagal mengunggah file. Pastikan file valid.'
            ]);
        }
        
        $filePath = $file->getTempName();
        $fileExt = $file->getClientExtension();
        
        try {
            // --- 1. MEMBACA FILE EXCEL ---
            $reader = IOFactory::createReaderForFile($filePath);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            
            // --- 2. EKSTRAKSI METADATA DARI SEL SPESIFIK ---
            
            // Berdasarkan gambar:
            $semester          = trim($sheet->getCell('B3')->getValue()); // Baris 6 Kolom B
            $mataPelajaranID   = trim($sheet->getCell('B4')->getValue()); // Baris 4 Kolom B
            
            if (empty($semester) || empty($mataPelajaranID)) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => '❌ Metadata (Semester/Mata Pelajaran ID) kosong atau tidak valid.'
                ]);
            }
            
            // --- 3. EKSTRAKSI DATA SISWA & FORMAT BATCH ---
            
            $highestRow = $sheet->getHighestRow();
            $dataToInsert = [];
            $currentDate = date('Y-m-d H:i:s');
            $processedCount = 0;
            
            // Mulai iterasi dari Baris 11 (data siswa pertama)
            for ($rowNum = 11; $rowNum <= $highestRow; $rowNum++) {
                
                $noKolomA = trim($sheet->getCell('A' . $rowNum)->getValue());
                
                // Hentikan jika kolom 'NO' kosong (akhir data)
                if (empty($noKolomA) || !is_numeric($noKolomA)) {
                    break;
                }

                $pesertaDidikId  = trim($sheet->getCell('B' . $rowNum)->getValue()); // Kolom B
                $anggotaRombelId = trim($sheet->getCell('C' . $rowNum)->getValue()); // Kolom C
                $nilaiRapor      = trim($sheet->getCell('H' . $rowNum)->getValue()); // Kolom H
                
                // Hanya masukkan jika ID peserta didik dan nilai tidak kosong
                if (!empty($pesertaDidikId) && is_numeric($nilaiRapor)) {
                    $dataToInsert[] = [
                        'peserta_didik_id'  => $pesertaDidikId,
                        'anggota_rombel_id' => $anggotaRombelId,
                        'semester_id'          => $semester,
                        'mata_pelajaran_id' => $mataPelajaranID,
                        'nilai'       => $nilaiRapor,
                        'tanggal_input'     => $currentDate
                    ];
                    $processedCount++;
                }
            }
            
            if ($processedCount === 0) {
                 return $this->response->setJSON(['success' => false, 'message' => '❌ Tidak ditemukan data siswa yang valid untuk diimpor.']);
            }

            // --- 4. MEMANGGIL MODEL UNTUK BATCH INSERT ---
            // ===============================================
            $insertedRows = $this->dapodikModel->saveRaporBatch($dataToInsert);
            // ===============================================

            // --- 5. RESPONSE AKHIR ---
            return $this->response->setJSON([
                'success' => true,
                'message' => "✅ Berhasil mengimpor **{$insertedRows}** baris data nilai rapor secara permanen."
            ]);

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => '❌ Gagal membaca file Excel: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            // Menangkap error database
            return $this->response->setJSON([
                'success' => false, 
                'message' => '❌ Terjadi kesalahan penyimpanan database: ' . $e->getMessage()
            ]);
        }
    }
    
}
		