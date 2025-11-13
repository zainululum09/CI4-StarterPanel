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
    
    public function updateConfig($data)
    {
        unset($data['id']);
        $this->db->table('config_dapodik')->where('id', 1)->update($data);
        if ($this->db->affectedRows() === 0) {
         $data['id'] = 1;
         return $this->db->table('config_dapodik')->insert($data);
        }
        
        return true;
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
        if (empty($data['npsn'])) {
            return ['success' => false, 'message' => 'NPSN data kosong.'];
        }
        
        $saved = 0;
        $updated = 0;
        $now = date('Y-m-d H:i:s');
        $tableName = 'data_sekolah';
        $npsn = $data['npsn'];

        $this->db->transStart();
        try {
            $existing = $this->db->table($tableName)->where('npsn', $npsn)->get()->getRow();

            if ($existing) {
                if (!$this->db->table($tableName)->where('npsn', $npsn)->update($data)) {
                    log_message('error', "Gagal UPDATE NPSN {$npsn}. Error DB: " . $this->db->error()['message']);
                    throw new \Exception('Gagal melakukan update ke database.');
                }
                $updated++;
            } else {
                $data['created_at'] = $now;
                if (!$this->db->table($tableName)->insert($data)) {
                    log_message('error', "Gagal INSERT NPSN {$npsn}. Error DB: " . $this->db->error()['message']);
                    throw new \Exception('Gagal memasukkan data baru ke database.');
                }
                $saved++;
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
        
        $this->db->transStart();

        try {
            foreach ($data as $row) {
                $ptk_id = trim($row['ptk_id'] ?? '');
                
                if (empty($ptk_id)) continue;
                
                $existing = $this->db->table('ptk')->where('ptk_id', $ptk_id)->get()->getRow();
                
                $saveData = [
                    'ptk_id' => $ptk_id,
                    'ptk_terdaftar_id' => $row['ptk_terdaftar_id'] ?? null,
                    'tahun_ajaran_id' => $row['tahun_ajaran_id'] ?? null,
                    'ptk_induk' => (int)($row['ptk_induk'] ?? 0),
                    'tanggal_surat_tugas' => $row['tanggal_surat_tugas'] ?? null,
                    'nama' => $row['nama'] ?? '',
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'agama_id' => (int)($row['agama_id'] ?? 0),
                    'agama_id_str' => $row['agama_id_str'] ?? '',
                    'nuptk' => $row['nuptk'] ?? null,
                    'nik' => $row['nik'] ?? null,
                    'jenis_ptk_id' => $row['jenis_ptk_id'] ?? null,
                    'jenis_ptk_id_str' => $row['jenis_ptk_id_str'] ?? '',
                    'jabatan_ptk_id' => $row['jabatan_ptk_id'] ?? null,
                    'jabatan_ptk_id_str' => $row['jabatan_ptk_id_str'] ?? '',
                    'status_kepegawaian_id' => (int)($row['status_kepegawaian_id'] ?? 0),
                    'status_kepegawaian_id_str' => $row['status_kepegawaian_id_str'] ?? '',
                    'nip' => $row['nip'] ?? null,
                    'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? null,
                    'bidang_studi_terakhir' => $row['bidang_studi_terakhir'] ?? null,
                    'pangkat_golongan_terakhir' => $row['pangkat_golongan_terakhir'] ?? null,
                    'updated_at' => $now
                ];
                
                if ($existing) {
                    $this->db->table('ptk')->where('ptk_id', $ptk_id)->update($saveData);
                    $updated++;
                } else {
                    $saveData['created_at'] = $now;
                    $this->db->table('ptk')->insert($saveData);
                    $saved++;
                }                
                
                if (!empty($row['rwy_pend_formal'])) {
                    $this->_savePtkRiwayatFormal($ptk_id, $row['rwy_pend_formal'], $now);
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

    private function _savePtkRiwayatFormal(string $ptk_id, array $riwayat_data, string $now)
    {
        $tableName = 'ptk_rwy_pend_formal';
        $batchData = [];

        $this->db->table($tableName)->where('ptk_id', $ptk_id)->delete();

        foreach ($riwayat_data as $rwy) {
            $batchData[] = [
                'riwayat_pendidikan_formal_id' => $rwy['riwayat_pendidikan_formal_id'] ?? uniqid(),
                'ptk_id' => $ptk_id,
                'satuan_pendidikan_formal' => $rwy['satuan_pendidikan_formal'] ?? null,
                'fakultas' => $rwy['fakultas'] ?? null,
                'kependidikan' => (int)($rwy['kependidikan'] ?? 0),
                'tahun_masuk' => $rwy['tahun_masuk'] ?? null,
                'tahun_lulus' => $rwy['tahun_lulus'] ?? null,
                'nim' => $rwy['nim'] ?? null,
                'status_kuliah' => $rwy['status_kuliah'] ?? null,
                'semester' => $rwy['semester'] ?? null,
                'ipk' => (float)($rwy['ipk'] ?? 0.00),
                'prodi' => $rwy['prodi'] ?? null,
                'bidang_studi_id_str' => $rwy['bidang_studi_id_str'] ?? null,
                'jenjang_pendidikan_id_str' => $rwy['jenjang_pendidikan_id_str'] ?? null,
                'gelar_akademik_id_str' => $rwy['gelar_akademik_id_str'] ?? null,
            ];
        }

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
                
                $saveData = [
                    'peserta_didik_id' => $pd_id,
                    'registrasi_id' => $row['registrasi_id'] ?? null,
                    'nipd' => $row['nipd'] ?? null,
                    'nama' => $row['nama'] ?? '',
                    'nisn' => $row['nisn'] ?? null,
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'nik' => $row['nik'] ?? null,
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                    'agama_id' => (int)($row['agama_id'] ?? 0),
                    'anak_keberapa' => $row['anak_keberapa'] ?? null,
                    'updated_at' => $now
                ];
                
                $sekolahAsal = [
                    'peserta_didik_id'  => $pd_id,
                    'sekolah_asal'  => $row['sekolah_asal'] ?? null,
                    'tanggal_masuk_sekolah' => $row['tanggal_masuk_sekolah'] ?? null,
                    'jenis_pendaftaran' => $row['jenis_pendaftaran_id_str'] ?? null,
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
                    $this->db->table('rwy_pendidikan_pd')->where('peserta_didik_id', $pd_id)->update($sekolahAsal);
                    $this->db->table('orang_tua')->where('peserta_didik_id', $pd_id)->update($dataOrtu);
                    $this->db->table('kesehatan_siswa')->where('peserta_didik_id', $pd_id)->update($kesehatan);
                    if($row['nomor_telepon_rumah'] || $row['nomor_telepon_seluler'] || $row['email']){
                        $this->db->table('alamat_siswa')->where('peserta_didik_id', $pd_id)->update($contact);
                    }
                    $updated++;

                } else {
                    $saveData['created_at'] = $now;
                    $sekolahAsal['created_at'] = $now;
                    $this->db->table($tableName)->insert($saveData);
                    $this->db->table('rwy_pendidikan_pd')->insert($sekolahAsal);
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
        $mapel_saved = 0; 
        $mapel_updated = 0; 
        $pembelajaranLink_saved = 0; 
        $pembelajaranLink_updated = 0; 
        
        $now = date('Y-m-d H:i:s');
        $rombelTable = 'rombel';
        $mapelTable = 'mapel';
        $pembelajaranLinkTable = 'pembelajaran'; 

        if (empty($dataRombel)) {
            return ['success' => false, 'message' => 'Data Rombongan Belajar kosong.'];
        }

        $this->db->transStart();

        try {
            foreach ($dataRombel as $row) {
                $rombel_id = trim($row['rombongan_belajar_id'] ?? '');
                if (empty($rombel_id)) continue;

                $existingRombel = $this->db->table($rombelTable)
                    ->where('rombongan_belajar_id', $rombel_id)
                    ->get()->getRow();

                $saveRombel = [
                    'rombongan_belajar_id' => $rombel_id,
                    'nama' => $row['nama'] ?? null,
                    'tingkat_pendidikan_id' => $row['tingkat_pendidikan_id'] ?? null,
                    'tingkat_pendidikan_id_str' => $row['tingkat_pendidikan_id_str'] ?? null,
                    'semester_id' => $row['semester_id'] ?? null,
                    'kurikulum_id' => (int)($row['kurikulum_id'] ?? 0),
                    'kurikulum_id_str' => $row['kurikulum_id_str'] ?? null,
                    'ptk_id' => $row['ptk_id'] ?? null,
                    'ptk_id_str' => $row['ptk_id_str'] ?? null,
                    'updated_at' => $now
                ];

                if ($existingRombel) {
                    $this->db->table($rombelTable)
                        ->where('rombongan_belajar_id', $rombel_id)
                        ->update($saveRombel);
                    $rombel_updated++;
                } else {
                    $saveRombel['created_at'] = $now;
                    $this->db->table($rombelTable)->insert($saveRombel);
                    $rombel_saved++;
                }

                if (!empty($row['pembelajaran']) && is_array($row['pembelajaran'])) {
                    foreach ($row['pembelajaran'] as $pb) {
                        $mata_pelajaran_id = trim($pb['mata_pelajaran_id'] ?? '');
                        $ptk_id = trim($pb['ptk_id'] ?? '');
                        $pembelajaran_id_dapodik = trim($pb['pembelajaran_id'] ?? ''); 
                        
                        if (empty($mata_pelajaran_id) || empty($pembelajaran_id_dapodik)) continue;

                        $existingMapel = $this->db->table($mapelTable)
                            ->where('mata_pelajaran_id', $mata_pelajaran_id)
                            ->get()->getRow();

                        $saveMapel = [
                            'mata_pelajaran_id' => $mata_pelajaran_id,
                            'nama_mata_pelajaran' => $pb['nama_mata_pelajaran'] ?? null,
                            'updated_at' => $now
                        ];

                        if ($existingMapel) {
                            $this->db->table($mapelTable)
                                ->where('mata_pelajaran_id', $mata_pelajaran_id)
                                ->update($saveMapel);
                            $mapel_updated++;
                        } else {
                            $saveMapel['created_at'] = $now;
                            $this->db->table($mapelTable)->insert($saveMapel);
                            $mapel_saved++;
                        }

                        $existingPembelajaran = $this->db->table($pembelajaranLinkTable)
                            ->where('pembelajaran_id', $pembelajaran_id_dapodik)
                            ->get()->getRow();

                        $savePembelajaranLink = [
                            'pembelajaran_id' => $pembelajaran_id_dapodik,
                            'rombongan_belajar_id' => $rombel_id,
                            'mata_pelajaran_id' => $mata_pelajaran_id,
                            'ptk_id' => $ptk_id, 
                            'updated_at' => $now
                        ];

                        if ($existingPembelajaran) {
                            $this->db->table($pembelajaranLinkTable)
                                ->where('pembelajaran_id', $pembelajaran_id_dapodik)
                                ->update($savePembelajaranLink);
                            $pembelajaranLink_updated++;
                        } else {
                            $savePembelajaranLink['created_at'] = $now;
                            
                            $this->db->table($pembelajaranLinkTable)->insert($savePembelajaranLink);
                            $pembelajaranLink_saved++;
                        }
                    }
                }
            } 

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal saat menyimpan Rombel, Mapel, dan Pembelajaran.');
            }

            return [
                'success' => true,
                'saved' => $rombel_saved,
                'updated' => $rombel_updated,
                'mapel_saved' => $mapel_saved, 
                'mapel_updated' => $mapel_updated, 
                'pembelajaran_saved' => $pembelajaranLink_saved, 
                'pembelajaran_updated' => $pembelajaranLink_updated, 
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

        foreach ($dataRombel as $row) {
            $rombel_id   = $row['rombongan_belajar_id'];
            $anggotaList = $row['anggota_rombel'];

                foreach ($anggotaList as $anggota) {
                    $anggota_id = $anggota['anggota_rombel_id'];
                    $pd_id      = $anggota['peserta_didik_id'];

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
        
        return [
            'sekolah' => $this->getSekolahStatistics(),
            'ptk' => $this->getPtkStatistics(),
            'peserta_didik' => $this->getPesertaDidikStatistics(),
            'rombongan_belajar' => $this->getRombelStatistics()
        ];
    }

    public function getAllPd($currentPage = 1)
    {
        $builder = $this->db->table('anggota_rombel ar');
        $builder->select('
            pd.nama AS nama, 
            r.nama AS kelas, 
            pd.nipd AS nipd, 
            pd.nisn AS nisn, 
            pd.jenis_kelamin AS jenis_kelamin,
            pd.peserta_didik_id AS pd_id
        ');
        $builder->join(
            'rombel r',
            'ar.rombongan_belajar_id = r.rombongan_belajar_id',
            'inner'
        );
        $builder->join(
            'pd',
            'ar.peserta_didik_id = pd.peserta_didik_id',
            'inner'
        );
        $builder->orderBy('kelas ASC, nama ASC');

        $perPage = 20;
        $offset = ($currentPage - 1) * $perPage;

        $data = $builder->limit($perPage, $offset)->get()->getResult();
        $jumlah = $builder->countAllResults();        

        $tr = '';
        if(empty($data)){
            $tr .= "<tr><td colspan='8' class='text-white bg-danger text-center h4'> DATA SISWA KOSONG, SILAHKAN TARIK DATA </td></tr>";
        }
        $i = 1;
        $offset+=1;
        foreach ($data as $row) {
            $jk = $row->jenis_kelamin==="L"?"Laki-laki":"Perempuan";
            $tr .= "<tr>
                            <th scope='row'>".$offset++."</th>
                            <td>".$row->nipd."</td>
                            <td>".$row->nisn."</td>
                            <td>".strtoupper($row->nama)."</td>
                            <td>".$jk."</td>
                            <td>".$row->kelas."</td>
                            <td><span class='badge bg-success'>Aktif</span></td>
                            <td>
                                <a href='".base_url('dapodik/detail_siswa/'.$row->nisn)."' class='btn btn-sm btn-info text-white me-1' title='Lihat Detail'><i class='fas fa-eye'></i></a>
                                <button class='btn btn-sm btn-danger delete-data-btn' data-id='$row->pd_id' data-nama='$row->nama' title='Hapus Data Peserta Didik'><i class='fas fa-trash-alt'></i></button>
                            </td>
                        </tr>    ";
        }

        return [
            'data' => $tr,
            'totalRows' => $jumlah,
            'currentPage' => $currentPage,
            'perPage'   => $perPage
        ];
    }

    public function getSiswa($nisn)
    {
        $pd_id = $this->db->table('pd')->where('nisn',$nisn)->get()->getRow();
        $siswa = $this->db->table('pd')
                    ->join('alamat_siswa','alamat_siswa.peserta_didik_id = pd.peserta_didik_id', 'left')
                    ->join('orang_tua','orang_tua.peserta_didik_id = pd.peserta_didik_id','left')
                    ->join('rwy_pendidikan_pd','rwy_pendidikan_pd.peserta_didik_id = pd.peserta_didik_id','left')
                    ->join('kesehatan_siswa','kesehatan_siswa.peserta_didik_id = pd.peserta_didik_id','left')
                    ->join('hobi_cita','hobi_cita.peserta_didik_id = pd.peserta_didik_id','left')
                    ->where('pd.peserta_didik_id', $pd_id->peserta_didik_id)
                    ->get()
                    ->getRow();
        $kasus = $this->db->table('kasus_siswa')->where('peserta_didik_id', $pd_id->peserta_didik_id)->get()->getResult();
        return [
          'siswa' => $siswa,
          'kasus' => $kasus,
          'peserta_didik_id' => $pd_id->peserta_didik_id  
        ];
    }

    public function update_detail($post)
    {
        switch ($post['type']){
            case 'kesehatan':
                unset($post['type']);
                $builder = $this->db->table('kesehatan_siswa');
                $id = $post['kesehatan_id'];
                if (empty($id)) {
                    $result = $builder->insert($post);
                } else {
                    $builder->where('kesehatan_id', $id);
                    $result = $builder->update($post);
                }
                
                if ($result) {
                    return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
                } else {
                    return ['status' => 'error', 'message' => 'Tidak ada perubahan'];
                }
            break;
            
            case 'alamat':                
                unset($post['type']);
                $builder = $this->db->table('alamat_siswa');
                $id = $post['alamat_id'];
                if (empty($id)) {
                    $result = $builder->insert($post);
                } else {
                    $builder->where('alamat_id', $id);
                    $result = $builder->update($post);
                }
                
                if ($result) {
                    return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
                } else {
                    return ['status' => 'error', 'message' => 'Tidak ada perubahan'];
                }
            break;

            case 'orangtua':
                unset($post['type']);
                $builder = $this->db->table('orang_tua'); 
                
                $id = $post['ortu_id'] ?? null;
                
                $post['tanggal_lahir_ayah'] = clean_and_format_date($post['tanggal_lahir_ayah'] ?? null);
                $post['tanggal_lahir_ibu']  = clean_and_format_date($post['tanggal_lahir_ibu'] ?? null);
                $post['tanggal_lahir_wali'] = clean_and_format_date($post['tanggal_lahir_wali'] ?? null);

                if (empty($id)) {
                    unset($post['ortu_id']); 
                    $result = $builder->insert($post);
                } else {
                    $builder->where('ortu_id', $id);
                    $result = $builder->update($post);
                }

                if ($result) {
                    if ($this->db->affectedRows() > 0 || empty($id)) { 
                        $message = empty($id) ? 'Data berhasil ditambahkan' : 'Data berhasil diupdate';
                        return ['status' => 'success', 'message' => $message];
                    } else {
                        return ['status' => 'success', 'message' => 'Tidak ada perubahan pada data'];
                    }
                } else {
                    return ['status' => 'error', 'message' => 'Gagal menyimpan data ke database.'];
                }
                break;

            case 'hobi':                
                unset($post['type']);
                $builder = $this->db->table('hobi_cita');
                $id = $post['hobi_id'];
                if (empty($id)) {
                    $result = $builder->insert($post);
                } else {
                    $builder->where('hobi_id', $id);
                    $result = $builder->update($post);
                }
                
                if ($result) {
                    return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
                } else {
                    return ['status' => 'error', 'message' => 'Tidak ada perubahan'];
                }
            break;
            
            case 'riwayatpendidikan':                
                unset($post['type']);
                $builder = $this->db->table('rwy_pendidikan_pd');
                $id = $post['peserta_didik_id'];
                if (empty($id)) {
                    $result = $builder->insert($post);
                } else {
                    $builder->where('peserta_didik_id', $id);
                    $result = $builder->update($post);
                }
                
                if ($result) {
                    return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
                } else {
                    return ['status' => 'error', 'message' => 'Tidak ada perubahan'];
                }
            break;

            case 'biodata':
                unset($post['type']);
                $builder = $this->db->table('pd');
                $id = $post['peserta_didik_id'];
                if (empty($id)) {
                    $result = $builder->insert($post);
                } else {
                    $builder->where('peserta_didik_id', $id);
                    $result = $builder->update($post);
                }
                
                if ($result) {
                    return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
                } else {
                    return ['status' => 'error', 'message' => 'Tidak ada perubahan'];
                }
            break;
        }
    }

    public function deleteSingleSiswa($id)
    {
        $oldFile = $this->db->table('pd')->select('foto')->where('peserta_didik_id', $id)->get()->getRow();
        $path = 'writable/uploads/foto_siswa';
        $oldPath = $path. DIRECTORY_SEPARATOR.$oldFile->foto;        
        @unlink($oldPath);

        return $this->db->table('pd')
                        ->where('peserta_didik_id', $id)
                        ->delete();
    }

    public function getRombel()
    {
        $builder = $this->db->table('rombel')->orderby('nama','ASC')->get()->getResult();
        return $builder;
    }

    public function getAnggotaRombel($id)
    {
        $builder = $this->db->table('pd')
                    ->join('anggota_rombel','pd.peserta_didik_id = anggota_rombel.peserta_didik_id', 'inner')
                    ->orderby('nama','ASC')
                    ->where('rombongan_belajar_id',$id)
                    ->get()
                    ->getResult();
        return $builder;
    }

    public function getMapel()
    {
        $mapel = $this->db->table('mapel')->get()->getResult();
        return $mapel;
    }

    public function saveRaporBatch(array $data)
    {
        return $this->db->table('nilai_rapor')->insertBatch($data);
    }

    public function getGtk()
    {
        $ptk = $this->db->table('ptk')
                ->orderby('nama', 'ASC')
                ->get()
                ->getResult();
        return $ptk;
    }

}