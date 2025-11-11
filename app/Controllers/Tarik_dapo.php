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
    
    public function index()
    {
        $data = array_merge($this->data, [
            'title'     =>  'Dapodik'
        ]);
        return view('pages/settings/tarik-dapo', $data);
    }
    
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
                    'server_info' => $config['nama_config'],
                    'data_count' => count($result['data']),
                    'ip'    => $url,
                    'my_ip' => gethostbyname(gethostname())
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
    
    public function checkData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'rombongan_belajar','anggota_rombongan_belajar'];
            if (!in_array($type, $validTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ]);
            }
            
            $endpoint = $this->getEndpoint($type);
            $data = $this->callDapodik($endpoint);

            if($type == 'anggota_rombongan_belajar'){
                $totalAnggota = 0;

                foreach ($data['data'] as $rombel) {
                    if (!empty($rombel['anggota_rombel'])) {
                        $totalAnggota += count($rombel['anggota_rombel']);
                    }
                }

                $count = "{$totalAnggota}";

            } else {
                $count = count($data['data']);
            }
            
            if ($data['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'count'   => $count,
                    'message' => "Berhasil mengecek data {$type}. Siap untuk disinkronkan."
                ]);
            } else {
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
    
    public function saveData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'rombongan_belajar','anggota_rombongan_belajar'];
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
    
    public function deleteData($type)
    {
        try {
            $validTypes = ['sekolah', 'ptk', 'peserta_didik', 'rombongan_belajar'];
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
    
    public function saveConfig()
    {
        try {
            $data = [
                'id' => $this->request->getPost('id'),
                'nama_config' => $this->request->getPost('nama_config'),
                'api_url' => $this->request->getPost('api_url'),
                'api_token' => $this->request->getPost('api_token'),
                'npsn' => $this->request->getPost('npsn')
            ];
            
            $result = $this->dapodikModel->updateConfig($data);
            
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
    
    private function getEndpoint($type)
    {
        $endpoints = [
            'sekolah' => 'getSekolah',
            'ptk' => 'getGtk',
            'peserta_didik' => 'getPesertaDidik',
            'rombongan_belajar' => 'getRombonganBelajar',
            'anggota_rombongan_belajar' => 'getRombonganBelajar'
        ];
        
        return $endpoints[$type] ?? null;
    }
}