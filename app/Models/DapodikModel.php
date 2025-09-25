<?php

namespace App\Models;

use CodeIgniter\Model;

class DapodikModel extends Model
{
    protected $table = 'config_dapodik';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_config', 'api_url', 'api_token', 'npsn', 'aktif'];
    protected $useTimestamps = true;
    
    // ==================== CONFIG METHODS ====================
    
    public function getActiveConfig()
    {
        return $this->where('aktif', 1)->first();
    }
    
    public function updateConfig($id, $data)
    {
        return $this->update($id, $data);
    }
    
    public function saveConfig($data)
    {
        $this->set('aktif', 0)->update();
        $data['aktif'] = 1;
        return $this->insert($data);
    }
    
    public function getActiveNpsn()
    {
        $config = $this->getActiveConfig();
        return $config ? $config['npsn'] : null;
    }
    
    public function getAllConfigs()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }
    
    // ==================== SEKOLAH METHODS ====================
    
    public function saveSekolahFromDapodik($data)
    {
        $saved = 0;
        $updated = 0;
        
        $this->db->transStart();
        
        try {
            foreach ($data as $row) {
                $npsn = $row['npsn'] ?? '';
                if (empty($npsn)) continue;
                
                $existing = $this->db->table('sekolah')->where('npsn', $npsn)->get()->getRow();
                
                $saveData = [
                    'npsn' => $npsn,
                    'nama_sekolah' => $row['nama'] ?? '',
                    'alamat' => $row['alamat_jalan'] ?? '',
                    'kode_pos' => $row['kode_pos'] ?? '',
                    'telepon' => $row['nomor_telepon'] ?? '',
                    'email' => $row['email'] ?? '',
                    'website' => $row['website'] ?? '',
                    'status_sekolah' => $row['status_sekolah'] ?? '',
                    'bentuk_pendidikan' => $row['bentuk_pendidikan_id'] ?? '',
                    'kode_wilayah' => $row['kode_wilayah'] ?? '',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($existing) {
                    $this->db->table('sekolah')->where('npsn', $npsn)->update($saveData);
                    $updated++;
                } else {
                    $saveData['created_at'] = date('Y-m-d H:i:s');
                    $this->db->table('sekolah')->insert($saveData);
                    $saved++;
                }
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }
            
            return [
                'success' => true,
                'saved' => $saved,
                'updated' => $updated,
                'total' => $saved + $updated
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function truncateSekolah()
    {
        return $this->db->table('sekolah')->truncate();
    }
    
    public function getSekolahStatistics()
    {
        return [
            'total' => $this->db->table('sekolah')->countAll(),
            'aktif' => $this->db->table('sekolah')->where('status_sekolah', 'A')->countAllResults(),
            'non_aktif' => $this->db->table('sekolah')->where('status_sekolah', 'N')->countAllResults()
        ];
    }
    
    // ==================== PTK METHODS ====================
    
    public function savePtkFromDapodik($data)
    {
        $saved = 0;
        $updated = 0;
        
        $this->db->transStart();
        
        try {
            foreach ($data as $row) {
                $ptk_id = $row['ptk_id'] ?? '';
                if (empty($ptk_id)) continue;
                
                $existing = $this->db->table('ptk')->where('ptk_id', $ptk_id)->get()->getRow();
                
                $saveData = [
                    'ptk_id' => $ptk_id,
                    'nama' => $row['nama'] ?? '',
                    'nip' => $row['nip'] ?? '',
                    'nuptk' => $row['nuptk'] ?? '',
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'agama' => $row['agama_id'] ?? '',
                    'alamat' => $row['alamat_jalan'] ?? '',
                    'telepon' => $row['nomor_telepon'] ?? '',
                    'email' => $row['email'] ?? '',
                    'status_kepegawaian' => $row['status_kepegawaian_id'] ?? '',
                    'jenis_ptk' => $row['jenis_ptk_id'] ?? '',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($existing) {
                    $this->db->table('ptk')->where('ptk_id', $ptk_id)->update($saveData);
                    $updated++;
                } else {
                    $saveData['created_at'] = date('Y-m-d H:i:s');
                    $this->db->table('ptk')->insert($saveData);
                    $saved++;
                }
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }
            
            return [
                'success' => true,
                'saved' => $saved,
                'updated' => $updated,
                'total' => $saved + $updated
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function truncatePtk()
    {
        return $this->db->table('ptk')->truncate();
    }
    
    public function getPtkStatistics()
    {
        return [
            'total' => $this->db->table('ptk')->countAll(),
            'laki_laki' => $this->db->table('ptk')->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->db->table('ptk')->where('jenis_kelamin', 'P')->countAllResults()
        ];
    }
    
    // ==================== PESERTA DIDIK METHODS ====================
    
    public function savePesertaDidikFromDapodik($data)
    {
        $saved = 0;
        $updated = 0;
        
        $this->db->transStart();
        
        try {
            foreach ($data as $row) {
                $peserta_didik_id = $row['peserta_didik_id'] ?? '';
                if (empty($peserta_didik_id)) continue;
                
                $existing = $this->db->table('peserta_didik')->where('peserta_didik_id', $peserta_didik_id)->get()->getRow();
                
                $saveData = [
                    'peserta_didik_id' => $peserta_didik_id,
                    'nama' => $row['nama'] ?? '',
                    'nisn' => $row['nisn'] ?? '',
                    'nis' => $row['nis'] ?? '',
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'agama' => $row['agama_id'] ?? '',
                    'alamat' => $row['alamat_jalan'] ?? '',
                    'nama_ayah' => $row['nama_ayah'] ?? '',
                    'nama_ibu' => $row['nama_ibu'] ?? '',
                    'status_dalam_keluarga' => $row['status_dalam_keluarga_id'] ?? '',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($existing) {
                    $this->db->table('peserta_didik')->where('peserta_didik_id', $peserta_didik_id)->update($saveData);
                    $updated++;
                } else {
                    $saveData['created_at'] = date('Y-m-d H:i:s');
                    $this->db->table('peserta_didik')->insert($saveData);
                    $saved++;
                }
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }
            
            return [
                'success' => true,
                'saved' => $saved,
                'updated' => $updated,
                'total' => $saved + $updated
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function truncatePesertaDidik()
    {
        return $this->db->table('peserta_didik')->truncate();
    }
    
    public function getPesertaDidikStatistics()
    {
        return [
            'total' => $this->db->table('peserta_didik')->countAll(),
            'laki_laki' => $this->db->table('peserta_didik')->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->db->table('peserta_didik')->where('jenis_kelamin', 'P')->countAllResults()
        ];
    }
    
    // ==================== PENGGUNA METHODS ====================
    
    public function savePenggunaFromDapodik($data)
    {
        $saved = 0;
        $updated = 0;
        
        $this->db->transStart();
        
        try {
            foreach ($data as $row) {
                $pengguna_id = $row['pengguna_id'] ?? '';
                if (empty($pengguna_id)) continue;
                
                $existing = $this->db->table('pengguna')->where('pengguna_id', $pengguna_id)->get()->getRow();
                
                $saveData = [
                    'pengguna_id' => $pengguna_id,
                    'nama' => $row['nama'] ?? '',
                    'username' => $row['nama_pengguna'] ?? '',
                    'email' => $row['email'] ?? '',
                    'peran' => $row['peran_id'] ?? '',
                    'aktif' => $row['aktif'] ?? 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($existing) {
                    $this->db->table('pengguna')->where('pengguna_id', $pengguna_id)->update($saveData);
                    $updated++;
                } else {
                    $saveData['created_at'] = date('Y-m-d H:i:s');
                    $this->db->table('pengguna')->insert($saveData);
                    $saved++;
                }
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }
            
            return [
                'success' => true,
                'saved' => $saved,
                'updated' => $updated,
                'total' => $saved + $updated
            ];
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function truncatePengguna()
    {
        return $this->db->table('pengguna')->truncate();
    }
    
    public function getPenggunaStatistics()
    {
        return [
            'total' => $this->db->table('pengguna')->countAll(),
            'aktif' => $this->db->table('pengguna')->where('aktif', 1)->countAllResults(),
            'non_aktif' => $this->db->table('pengguna')->where('aktif', 0)->countAllResults()
        ];
    }
    
    // ==================== GENERIC METHODS ====================
    
    public function saveFromDapodik($type, $data)
    {
        switch ($type) {
            case 'sekolah':
                return $this->saveSekolahFromDapodik($data);
            case 'ptk':
                return $this->savePtkFromDapodik($data);
            case 'peserta_didik':
                return $this->savePesertaDidikFromDapodik($data);
            case 'pengguna':
                return $this->savePenggunaFromDapodik($data);
            default:
                return [
                    'success' => false,
                    'message' => 'Jenis data tidak valid'
                ];
        }
    }
    
    public function truncateData($type)
    {
        switch ($type) {
            case 'sekolah':
                return $this->truncateSekolah();
            case 'ptk':
                return $this->truncatePtk();
            case 'peserta_didik':
                return $this->truncatePesertaDidik();
            case 'pengguna':
                return $this->truncatePengguna();
            default:
                return false;
        }
    }
    
    public function getStatistics($type = null)
    {
        if ($type) {
            switch ($type) {
                case 'sekolah':
                    return $this->getSekolahStatistics();
                case 'ptk':
                    return $this->getPtkStatistics();
                case 'peserta_didik':
                    return $this->getPesertaDidikStatistics();
                case 'pengguna':
                    return $this->getPenggunaStatistics();
                default:
                    return [];
            }
        }
        
        // Return all statistics
        return [
            'sekolah' => $this->getSekolahStatistics(),
            'ptk' => $this->getPtkStatistics(),
            'peserta_didik' => $this->getPesertaDidikStatistics(),
            'pengguna' => $this->getPenggunaStatistics()
        ];
    }
}