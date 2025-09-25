<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DapodikModel;

class Tarik_dapo extends BaseController
{
    protected $dapodikApiUrl;
    protected $apiToken;
    protected $npsn;
    protected $dapodikModel;
    
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
        $this->loadDapodikConfig();
    }
    
    /**
     * Halaman utama
     */
    public function index()
    {
        $data = array_merge($this->data,[
            'title' => "Dapodik"
        ]);
        return view('pages/settings/tarik-dapo', $data);
    }
    
    /**
     * Load konfigurasi Dapodik dari database
     */
    private function loadDapodikConfig()
    {
        try {
            $config = $this->dapodikModel->getActiveConfig();
            
            if ($config) {
                $this->dapodikApiUrl = $config['api_url'];
                $this->apiToken = $config['api_token'];
                $this->npsn = $config['npsn'];
            } else {
                $this->dapodikApiUrl = 'http://localhost:5774/WebService';
                $this->apiToken = '';
                $this->npsn = '';
                log_message('warning', 'Konfigurasi Dapodik tidak ditemukan di database');
            }
        } catch (\Exception $e) {
            $this->dapodikApiUrl = 'http://localhost:5774/WebService';
            $this->apiToken = '';
            $this->npsn = '';
            log_message('error', 'Error loading Dapodik config: ' . $e->getMessage());
        }
    }
    
    /**
     * Test koneksi ke web service Dapodik
     */
    public function testConnection()
    {
        try {
            $startTime = microtime(true);
            
            $testEndpoint = $this->dapodikApiUrl . '/GetSekolah';
            if (!empty($this->npsn)) {
                $testEndpoint .= '?npsn=' . $this->npsn;
            }
            
            $client = \Config\Services::curlrequest();
            $response = $client->get($testEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 10
            ]);
            
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000, 2);
            
            if ($response->getStatusCode() == 200) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Koneksi berhasil',
                    'url' => $this->dapodikApiUrl,
                    'npsn' => $this->npsn,
                    'response_time' => $responseTime,
                    'server_info' => 'Dapodik Web Service',
                    'status_code' => $response->getStatusCode()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Server merespons dengan status: ' . $response->getStatusCode(),
                    'url' => $this->dapodikApiUrl,
                    'npsn' => $this->npsn,
                    'response_time' => $responseTime,
                    'status_code' => $response->getStatusCode()
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error testing Dapodik connection: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal terhubung: ' . $e->getMessage(),
                'url' => $this->dapodikApiUrl,
                'npsn' => $this->npsn
            ]);
        }
    }
    
    /**
     * Cek jumlah data dari web service Dapodik
     */
    public function checkData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $endpoint = $this->getEndpoint($type);
            $data = $this->fetchDataFromDapodik($endpoint);
            
            if ($data['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'count' => count($data['data']),
                    'message' => "Berhasil mengecek data {$type}"
                ]);
            } else {
                return $this->response->setJSON($data);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error checking Dapodik data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Simpan data dari web service ke database MySQL
     */
    public function saveData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $endpoint = $this->getEndpoint($type);
            $apiData = $this->fetchDataFromDapodik($endpoint);
            
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
                    'message' => "Berhasil menyimpan {$result['total']} data {$type} ({$result['saved']} baru, {$result['updated']} diperbarui)"
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
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'pengguna'];
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
                $this->loadDapodikConfig();
                
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
     * Fetch data dari web service Dapodik
     */
    private function fetchDataFromDapodik($endpoint)
    {
        try {
            $url = $this->dapodikApiUrl . $endpoint;
            
            if (!empty($this->npsn) && strpos($endpoint, '?') === false) {
                $url .= '?npsn=' . $this->npsn;
            } elseif (!empty($this->npsn)) {
                $url .= '&npsn=' . $this->npsn;
            }
            
            $client = \Config\Services::curlrequest();
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 60
            ]);
            
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $rows = is_array($data) ? $data : (isset($data['rows']) ? $data['rows'] : []);
                
                return [
                    'success' => true,
                    'data' => $rows
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal mengambil data dari web service Dapodik'
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get endpoint berdasarkan jenis data
     */
    private function getEndpoint($type)
    {
        $endpoints = [
            'sekolah' => '/GetSekolah',
            'ptk' => '/GetPtk',
            'peserta_didik' => '/GetPesertaDidik',
            'pengguna' => '/GetPengguna'
        ];
        
        return $endpoints[$type] ?? null;
    }
}