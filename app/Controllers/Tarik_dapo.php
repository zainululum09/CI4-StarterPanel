<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DapodikModel;

class Tarik_dapo extends BaseController
{
    protected $dapodikModel;
    
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }
    
    /**
     * Halaman utama
     */
    public function index()
    {
        $data = array_merge($this->data, [
            'title'     =>  'Dapodik'
        ]);
        return view('pages/settings/tarik-dapo', $data);
    }
    
    /**
     * Call Dapodik Web Service (adapted from your previous script)
     */
    private function callDapodik($endpoint)
    {
        $config = $this->dapodikModel->getActiveConfig();
        
        if (!$config) {
            return [
                'success' => false,
                'message' => 'Konfigurasi Dapodik tidak ditemukan'
            ];
        }
        
        $token = $config['api_token'];
        $npsn = $config['npsn'];
        $url = $config['api_url'];
        
        $url = "http://$url:5774/WebService/$endpoint?npsn=$npsn";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            log_message('error', 'CURL Error: ' . $error);
            return [
                'success' => false,
                'message' => 'Error koneksi: ' . $error
            ];
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'message' => 'Server merespons dengan status: ' . $httpCode
            ];
        }
        
        $data = json_decode($response, true);
        
        if (!$response || !isset($data['rows'])) {
            return [
                'success' => false,
                'message' => 'Data tidak valid atau kosong'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data['rows']
        ];
    }
    
    /**
     * Test koneksi ke web service Dapodik
     */
    public function testConnection()
    {
        try {
            $startTime = microtime(true);
            
            $result = $this->callDapodik('getSekolah');
            
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);
            
            if ($result['success']) {
                $config = $this->dapodikModel->getActiveConfig();
                $url = $config['api_url'];
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Koneksi berhasil',
                    'url' => "http://$url:5774/WebService",
                    'npsn' => $config['npsn'] ?? '',
                    'response_time' => $responseTime,
                    'server_info' => 'Dapodik Web Service',
                    'data_count' => count($result['data'])
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $result['message'],
                    'response_time' => $responseTime
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error testing Dapodik connection: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cek jumlah data dari web service Dapodik
     */
    public function checkData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna', 'rombongan_belajar'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $endpoint = $this->getEndpoint($type);
            // Asumsi $this->callDapodik() mengembalikan array: 
            // ['success' => bool, 'data' => array/null, 'message' => string]
            $data = $this->callDapodik($endpoint);
            
            if ($data['success']) {
                // MENGIRIM SELURUH DATA KE RESPON JSON
                return $this->response->setJSON([
                    'success' => true,
                    'count'   => count($data['data']),
                    'message' => "Berhasil mengecek data {$type}. Siap untuk disinkronkan."
                ]);
            } else {
                // Jika callDapodik gagal (misalnya karena token/server down), kirim respons error yang ada
                return $this->response->setJSON($data);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error checking Dapodik data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan internal server: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Simpan data dari web service ke database MySQL
     */
    public function saveData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna', 'rombongan_belajar'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $endpoint = $this->getEndpoint($type);
            $apiData = $this->callDapodik($endpoint);
            
            if (!$apiData['success']) {
                return $this->response->setJSON($apiData);
            }
            
            if (empty($apiData['data'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak ada data untuk disimpan'
                ]);
            }
            
            $result = $this->dapodikModel->saveFromDapodik($type, $apiData['data']);
            
            if ($result['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'saved' => $result['saved'],
                    'updated' => $result['updated'],
                    'total' => $result['total'],
                    // 'message' => "Berhasil menyimpan {$result['total']} data {$type} ({$result['saved']} baru, {$result['updated']} diperbarui)"
                    'message' => $result['debug']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving Dapodik data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Hapus semua data berdasarkan jenis
     */
    public function deleteData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna', 'rombongan_belajar'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $result = $this->dapodikModel->truncateData($type);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => "Semua data {$type} berhasil dihapus"
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Simpan atau update konfigurasi Dapodik
     */
    public function saveConfig()
    {
        try {
            $data = [
                'nama_config' => $this->request->getPost('nama_config'),
                'api_url' => $this->request->getPost('api_url'),
                'api_token' => $this->request->getPost('api_token'),
                'npsn' => $this->request->getPost('npsn')
            ];
            
            $result = $this->dapodikModel->saveConfig($data);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Konfigurasi berhasil disimpan'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan konfigurasi'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving config: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get konfigurasi aktif
     */
    public function getConfig()
    {
        try {
            $config = $this->dapodikModel->getActiveConfig();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $config
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengambil konfigurasi'
            ]);
        }
    }
    
    /**
     * Get statistik semua data
     */
    public function getStatistics()
    {
        try {
            $stats = $this->dapodikModel->getStatistics();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengambil statistik'
            ]);
        }
    }
    
    /**
     * Get endpoint berdasarkan jenis data
     */
    private function getEndpoint($type)
    {
        $endpoints = [
            'sekolah' => 'getSekolah',
            'ptk' => 'getGtk',
            'peserta_didik' => 'getPesertaDidik',
            'pengguna' => 'getPengguna',
            'rombongan_belajar' => 'getRombonganBelajar'
        ];
        
        return $endpoints[$type] ?? null;
    }
}