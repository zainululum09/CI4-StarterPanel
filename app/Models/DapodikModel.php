<?php

namespace App\Models;

use CodeIgniter\Model;

class DapodikModel extends Model
{
    protected $table = 'config_dapodik';
    // protected $db;
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
        // Cek input data tunggal
        if (empty($data['npsn'])) {
            return ['success' => false, 'message' => 'NPSN data kosong.'];
        }
        
        $saved = 0;
        $updated = 0;
        $now = date('Y-m-d H:i:s');
        $tableName = 'data_sekolah';
        $npsn = $data['npsn'];

        $this->db->transStart(); // <<< WAJIB ADA

        try {
            $existing = $this->db->table($tableName)->where('npsn', $npsn)->get()->getRow();

            if ($existing) {
                // UPDATE
                if (!$this->db->table($tableName)->where('npsn', $npsn)->update($data)) {
                    log_message('error', "Gagal UPDATE NPSN {$npsn}. Error DB: " . $this->db->error()['message']);
                    throw new \Exception('Gagal melakukan update ke database.');
                }
                $updated++;
            } else {
                // INSERT
                $data['created_at'] = $now;
                if (!$this->db->table($tableName)->insert($data)) {
                    log_message('error', "Gagal INSERT NPSN {$npsn}. Error DB: " . $this->db->error()['message']);
                    throw new \Exception('Gagal memasukkan data baru ke database.');
                }
                $saved++;
            }
            
            $this->db->transComplete(); // <<< WAJIB ADA

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
            $this->db->transRollback(); // <<< WAJIB ADA JIKA GAGAL
            log_message('alert', 'EXCEPTION SAAT SAVING: ' . $e->getMessage());
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
        $now = date('Y-m-d H:i:s');
        
        // Asumsikan ada helper function untuk menyimpan riwayat (Lihat bagian 2)
        // $this->load->model('PtkRiwayatModel'); 

        $this->db->transStart();

        try {
            foreach ($data as $row) {
                $ptk_id = trim($row['ptk_id'] ?? '');
                
                // Periksa kunci utama ptk_id
                if (empty($ptk_id)) continue;
                
                $existing = $this->db->table('ptk')->where('ptk_id', $ptk_id)->get()->getRow();
                
                // PENYESUAIAN MAPPING KOLOM PTK
                $saveData = [
                    'ptk_id' => $ptk_id,
                    'ptk_terdaftar_id' => $row['ptk_terdaftar_id'] ?? null,
                    'tahun_ajaran_id' => $row['tahun_ajaran_id'] ?? null,
                    'ptk_induk' => (int)($row['ptk_induk'] ?? 0), // Konversi ke INT/BOOLEAN
                    'tanggal_surat_tugas' => $row['tanggal_surat_tugas'] ?? null, // Harus DATE
                    'nama' => $row['nama'] ?? '',
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null, // Harus DATE
                    'agama_id' => (int)($row['agama_id'] ?? 0), // Konversi ke INT
                    'agama_id_str' => $row['agama_id_str'] ?? '',
                    'nuptk' => $row['nuptk'] ?? null,
                    'nik' => $row['nik'] ?? null,
                    'jenis_ptk_id' => $row['jenis_ptk_id'] ?? null,
                    'jenis_ptk_id_str' => $row['jenis_ptk_id_str'] ?? '',
                    'jabatan_ptk_id' => $row['jabatan_ptk_id'] ?? null,
                    'jabatan_ptk_id_str' => $row['jabatan_ptk_id_str'] ?? '',
                    'status_kepegawaian_id' => (int)($row['status_kepegawaian_id'] ?? 0), // Konversi ke INT
                    'status_kepegawaian_id_str' => $row['status_kepegawaian_id_str'] ?? '',
                    'nip' => $row['nip'] ?? null,
                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                    'bidang_studi_terakhir' => $row['bidang_studi_terakhir'] ?? null,
                    'pangkat_golongan_terakhir' => $row['pangkat_golongan_terakhir'] ?? null,
                    'updated_at' => $now
                ];
                
                if ($existing) {
                    // UPDATE
                    $this->db->table('ptk')->where('ptk_id', $ptk_id)->update($saveData);
                    $updated++;
                } else {
                    // INSERT
                    $saveData['created_at'] = $now;
                    $this->db->table('ptk')->insert($saveData);
                    $saved++;
                }
                
                // --- PENANGANAN DATA NESTED (RIWAYAT PENDIDIKAN) ---
                if (!empty($row['rwy_pend_formal'])) {
                    // Panggil fungsi untuk menyimpan riwayat
                    $this->_savePtkRiwayatFormal($ptk_id, $row['rwy_pend_formal'], $now);
                }
                
                // Abaikan rwy_kepangkatan karena data contoh kosong, tapi logikanya sama
                // if (!empty($row['rwy_kepangkatan'])) { ... }
                
            } // End Foreach
            
            $this->db->transComplete();
            
            // ... (blok transStatus dan return)
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
            // Disarankan untuk menambahkan logging di sini (log_message('error', $e->getMessage()))
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function _savePtkRiwayatFormal(string $ptk_id, array $riwayat_data, string $now)
    {
        $tableName = 'ptk_rwy_pend_formal';
        $batchData = [];

        // 1. Hapus semua riwayat lama untuk PTK ini
        // Ini adalah cara paling aman untuk sinkronisasi data relasional/nested
        $this->db->table($tableName)->where('ptk_id', $ptk_id)->delete();

        // 2. Siapkan data baru untuk insert batch
        foreach ($riwayat_data as $rwy) {
            $batchData[] = [
                'riwayat_pendidikan_formal_id' => $rwy['riwayat_pendidikan_formal_id'] ?? uniqid(),
                'ptk_id' => $ptk_id, // Kunci asing ke PTK utama
                'satuan_pendidikan_formal' => $rwy['satuan_pendidikan_formal'] ?? null,
                'fakultas' => $rwy['fakultas'] ?? null,
                'kependidikan' => (int)($rwy['kependidikan'] ?? 0),
                'tahun_masuk' => $rwy['tahun_masuk'] ?? null,
                'tahun_lulus' => $rwy['tahun_lulus'] ?? null,
                'nim' => $rwy['nim'] ?? null,
                'status_kuliah' => $rwy['status_kuliah'] ?? null,
                'semester' => $rwy['semester'] ?? null,
                'ipk' => (float)($rwy['ipk'] ?? 0.00), // Konversi ke FLOAT
                'prodi' => $rwy['prodi'] ?? null,
                'bidang_studi_id_str' => $rwy['bidang_studi_id_str'] ?? null,
                'jenjang_pendidikan_id_str' => $rwy['jenjang_pendidikan_id_str'] ?? null,
                'gelar_akademik_id_str' => $rwy['gelar_akademik_id_str'] ?? null,
            ];
        }

        // 3. Masukkan semua data baru
        if (!empty($batchData)) {
            $this->db->table($tableName)->insertBatch($batchData);
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
    
    public function savePesertaDidikFromDapodik($dataPD)
    {
        $saved = 0;
        $updated = 0;
        $now = date('Y-m-d H:i:s');
        $tableName = 'pd'; 

        if (empty($dataPD)) {
            return ['success' => false, 'message' => 'Data Peserta Didik kosong.'];
        }

        $this->db->transStart();

        try {
            foreach ($dataPD as $row) {
                $pd_id = trim($row['peserta_didik_id'] ?? '');
                
                if (empty($pd_id)) continue; 

                $existing = $this->db->table($tableName)
                    ->join('alamat_siswa','alamat_siswa.peserta_didik_id = '.$tableName.'.peserta_didik_id', 'inner')
                    ->join('orang_tua','orang_tua.peserta_didik_id = '.$tableName.'.peserta_didik_id','inner')
                    ->join('kesehatan_siswa','kesehatan_siswa.peserta_didik_id = '.$tableName.'.peserta_didik_id','inner')
                    ->where($tableName.'.peserta_didik_id', $pd_id)
                    ->get()
                    ->getRow();
                
                // Mapping dan Konversi Tipe Data
                $saveData = [
                    'peserta_didik_id' => $pd_id,
                    'registrasi_id' => $row['registrasi_id'] ?? null,
                    'nipd' => $row['nipd'] ?? null,
                    'tanggal_masuk_sekolah' => $row['tanggal_masuk_sekolah'] ?? null, // DATE
                    'sekolah_asal' => $row['sekolah_asal'] ?? null,
                    'nama' => $row['nama'] ?? '',
                    'nisn' => $row['nisn'] ?? null,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'nik' => $row['nik'] ?? null,
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null, // DATE
                    'agama_id' => (int)($row['agama_id'] ?? 0),
                    'anak_keberapa' => $row['anak_keberapa'] ?? null,
                    'updated_at' => $now
                ];
                
                $contact = [
                    'peserta_didik_id' => $pd_id,
                    'telepon_rumah' => $row['nomor_telepon_rumah'] ?? null,
                    'telepon_seluler' => $row['nomor_telepon_seluler'] ?? null,
                    'email' => $row['email'] ?? null,
                ];
                
                $dataOrtu = [
                    'peserta_didik_id' => $pd_id,
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'pekerjaan_ayah_id' => (int)($row['pekerjaan_ayah_id'] ?? 0),
                    'nama_ibu' => $row['nama_ibu'] ?? null,
                    'pekerjaan_ibu_id' => (int)($row['pekerjaan_ibu_id'] ?? 0),
                    'nama_wali' => $row['nama_wali'] ?? null,
                    'pekerjaan_wali_id' => (int)($row['pekerjaan_wali_id'] ?? 0),
                ];
                
                $kesehatan = [
                    'peserta_didik_id' => $pd_id,
                    'tinggi_badan' => (int)($row['tinggi_badan'] ?? 0),
                    'berat_badan' => (int)($row['berat_badan'] ?? 0),
                    'kebutuhan_khusus' => $row['kebutuhan_khusus'] ?? null,
                ];
                
                if ($existing) {
                    $this->db->table($tableName)->where('peserta_didik_id', $pd_id)->update($saveData);
                    $this->db->table('orang_tua')->where('peserta_didik_id', $pd_id)->update($dataOrtu);
                    $this->db->table('kesehatan_siswa')->where('peserta_didik_id', $pd_id)->update($kesehatan);
                    if($row['nomor_telepon_rumah'] || $row['nomor_telepon_seluler'] || $row['email']){
                        $this->db->table('alamat_siswa')->where('peserta_didik_id', $pd_id)->update($contact);
                    }
                    $updated++;

                } else {
                    $saveData['created_at'] = $now;
                    $this->db->table($tableName)->insert($saveData);
                    $this->db->table('orang_tua')->insert($dataOrtu);
                    $this->db->table('kesehatan_siswa')->insert($kesehatan);
                    if($row['nomor_telepon_rumah'] || $row['nomor_telepon_seluler'] || $row['email']){
                        $this->db->table('alamat_siswa')->insert($contact);
                    }
                    $saved++;
                }
            } // End Foreach

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal saat menyimpan Peserta Didik.');
            }

            return [
                'success' => true,
                'saved' => $saved,
                'updated' => $updated,
                'total' => $saved + $updated
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'PD Save Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function truncatePesertaDidik()
    {
        return $this->db->table('pd')->truncate();
    }
    public function getPesertaDidikStatistics()
    {
        return [
            'total' => $this->db->table('pd')->countAll(),
            'laki_laki' => $this->db->table('pd')->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->db->table('pd')->where('jenis_kelamin', 'P')->countAllResults()
        ];
    }

    public function saveRombelFromDapodik($dataRombel)
    {
        $rombel_saved = 0;
        $rombel_updated = 0;
        $anggota_saved = 0;
        $now = date('Y-m-d H:i:s');
        $rombelTable = 'rombel';
        $anggotaTable = 'anggota_rombel';

        if (empty($dataRombel)) {
            return ['success' => false, 'message' => 'Data Rombongan Belajar kosong.'];
        }
        
        $this->db->transStart();

        try {
            foreach ($dataRombel as $row) {
                $rombel_id = trim($row['rombongan_belajar_id'] ?? '');
                
                if (empty($rombel_id)) continue; 

                $existingRombel = $this->db->table($rombelTable)->where('rombongan_belajar_id', $rombel_id)->get()->getRow();
                
                // Mapping Data Rombel
                $saveRombel = [
                    'rombongan_belajar_id' => $rombel_id,
                    'nama' => $row['nama'] ?? null,
                    'tingkat_pendidikan_id' => $row['tingkat_pendidikan_id'] ?? null,
                    'tingkat_pendidikan_id_str' => $row['tingkat_pendidikan_id_str'] ?? null,
                    'semester_id' => $row['semester_id'] ?? null,
                    'jenis_rombel' => $row['jenis_rombel'] ?? null,
                    'jenis_rombel_str' => $row['jenis_rombel_str'] ?? null,
                    'kurikulum_id' => (int)($row['kurikulum_id'] ?? 0),
                    'kurikulum_id_str' => $row['kurikulum_id_str'] ?? null,
                    'id_ruang' => $row['id_ruang'] ?? null,
                    'id_ruang_str' => $row['id_ruang_str'] ?? null,
                    'moving_class' => $row['moving_class'] ?? null,
                    'ptk_id' => $row['ptk_id'] ?? null,
                    'ptk_id_str' => $row['ptk_id_str'] ?? null,
                    'updated_at' => $now
                ];

                // 1. Upsert Data Rombel
                if ($existingRombel) {
                    $this->db->table($rombelTable)->where('rombongan_belajar_id', $rombel_id)->update($saveRombel);
                    $rombel_updated++;
                } else {
                    $saveRombel['created_at'] = $now;
                    $this->db->table($rombelTable)->insert($saveRombel);
                    $rombel_saved++;
                }
                
            } // End Foreach

            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal saat menyimpan Rombel.');
            }
            
            return [
                'success' => true,
                'saved' => $rombel_saved,
                'updated' => $rombel_updated,
                'total' => $rombel_saved + $rombel_updated
            ];

        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Rombel Save Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function truncateRombel()
    {
        return $this->db->table('rombel')->truncate();
    }

    public function saveRombelAnggotaFromDapodik(array $dataRombel)
    {
        $saved   = 0;
        $updated = 0;
        $now     = date('Y-m-d H:i:s');
        $table   = 'anggota_rombel';
        $debug = '';
        // $anggotaList = 0;

        foreach ($dataRombel as $row) {
            $rombel_id   = $row['rombongan_belajar_id'];
            $anggotaList = $row['anggota_rombel'];

                foreach ($anggotaList as $anggota) {
                    $anggota_id = $anggota['anggota_rombel_id'];
                    $pd_id      = $anggota['peserta_didik_id'];

                    // Cek apakah data sudah ada
                    $existing = $this->db->table($table)
                        ->where('peserta_didik_id', $pd_id)
                        ->get()
                        ->getRow();
                    
                    $cekPd = $this->db->table('pd')->where('peserta_didik_id', $pd_id)->get()->getRow();

                    $saveData = [
                        'anggota_rombel_id'        => $anggota_id,
                        'peserta_didik_id'         => $pd_id,
                        'rombongan_belajar_id'     => $rombel_id,
                        'jenis_pendaftaran_id'     => $anggota['jenis_pendaftaran_id'],
                        'jenis_pendaftaran_id_str' => $anggota['jenis_pendaftaran_id_str'],
                        'updated_at'               => $now
                    ];

                    if ($existing) {
                        $this->db->table($table)
                        ->where('anggota_rombel_id', $anggota_id)
                        ->update($saveData);
                        $updated++;
                    } else if($cekPd){
                        $saveData['created_at'] = $now;
                        $this->db->table($table)->insert($saveData);
                        $saved++;
                    } else {
                        $updated++;
                    }
                }
        }

        return [
            'success'    => true,
            'saved'      => $saved,
            'updated'    => $updated,
            'total'      => $saved + $updated,
            // 'total'   => count($anggotaList),
            'message'    => count($anggotaList)
        ];
    }


    public function getRombelStatistics()
    {
        return [
            'total' => $this->db->table('rombel')->countAll(),
            '7' => $this->db->table('rombel')->where('tingkat_pendidikan_id', '7')->countAllResults(),
            '8' => $this->db->table('rombel')->where('tingkat_pendidikan_id', '8')->countAllResults(),
            '9' => $this->db->table('rombel')->where('tingkat_pendidikan_id', '9')->countAllResults()
        ];
    }

    /**
     * Helper function untuk menghapus dan memasukkan ulang anggota rombel (sinkronisasi penuh).
     */
        
    
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
            case 'rombongan_belajar':
                return $this->saveRombelFromDapodik($data);
            case 'anggota_rombongan_belajar':
                return $this->saveRombelAnggotaFromDapodik($data);
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
            case 'rombongan_belajar':
                return $this->truncateRombel();
            case 'anggota_rombongan_belajar':
                return $this->truncateAnggotaRombel();
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
                case 'rombongan_belajar':
                    return $this->getRombelStatistics();
                default:
                    return [];
            }
        }
        
        // Return all statistics
        return [
            'sekolah' => $this->getSekolahStatistics(),
            'ptk' => $this->getPtkStatistics(),
            'peserta_didik' => $this->getPesertaDidikStatistics(),
            'rombongan_belajar' => $this->getRombelStatistics()
        ];
    }

    // getAllPd
    public function getAllPd()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('anggota_rombel ar');
        $builder->select('
            pd.nama AS nama, 
            r.nama AS kelas, 
            pd.nipd AS nipd, 
            pd.nisn AS nisn, 
            pd.jenis_kelamin AS jenis_kelamin
        ');
        $builder->join(
            'rombel r',  // Tabel target dan alias 'r'
            'ar.rombongan_belajar_id = r.rombongan_belajar_id', // Kondisi ON
            'inner' // Tipe JOIN (opsional, defaultnya LEFT)
        );
        $builder->join(
            'pd', // Tabel target dan alias 'pd'
            'ar.peserta_didik_id = pd.peserta_didik_id', // Kondisi ON
            'inner'
        );
        $builder->orderBy('kelas ASC, nama ASC');
        $data = $builder->get()->getResult();

        $tr = '';
        if(empty($data)){
            $tr .= "<tr><td colspan='8' class='text-white bg-danger text-center h4'> DATA SISWA KOSONG, SILAHKAN TARIK DATA </td></tr>";
        }
        $i = 1;
        foreach ($data as $row) {
            $jk = $row->jenis_kelamin==="L"?"Laki-laki":"Perempuan";
            $tr .= "<tr>
                            <th scope='row'>".$i++."</th>
                            <td>".$row->nipd."</td>
                            <td>".$row->nisn."</td>
                            <td>".strtoupper($row->nama)."</td>
                            <td>".$jk."</td>
                            <td>".$row->kelas."</td>
                            <td><span class='badge bg-success'>Aktif</span></td>
                            <td>
                                <a href='".base_url('dapodik/detail_siswa/'.$row->nisn)."' class='btn btn-sm btn-info text-white me-1' title='Lihat Detail'><i class='fas fa-eye'></i></a>
                                <button class='btn btn-sm btn-danger' title='Hapus Data'><i class='fas fa-trash-alt'></i></button>
                            </td>
                        </tr>    ";
        }

        return [
            'data' => $tr,
            'total' => count($data)
        ];
    }

    public function getSiswa($nisn)
    {
        $pd_id = $this->db->table('pd')->where('nisn',$nisn)->get()->getRow();
        $siswa = $this->db->table('pd')
                    ->join('alamat_siswa','alamat_siswa.peserta_didik_id = pd.peserta_didik_id', 'left')
                    ->join('orang_tua','orang_tua.peserta_didik_id = pd.peserta_didik_id','left')
                    ->join('kesehatan_siswa','kesehatan_siswa.peserta_didik_id = pd.peserta_didik_id','left')
                    ->join('hobi_cita','hobi_cita.peserta_didik_id = pd.peserta_didik_id','left')
                    ->where('pd.peserta_didik_id', $pd_id->peserta_didik_id)
                    ->get()
                    ->getRow();
        $kasus = $this->db->table('kasus_siswa')->where('peserta_didik_id', $pd_id->peserta_didik_id)->get()->getResult();
        return [
          'siswa' => $siswa,
          'kasus' => $kasus  
        ];
    }
}