<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DapodikModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Files\File;

class Dapodik extends BaseController
{
    public function __construct()
    {
        $this->dapodikModel = new DapodikModel();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $tr = $this->dapodikModel->getAllPd($page);
        $data = array_merge($this->data, [
            'title'         => 'Daftar Peserta Didik',
            'datasiswa'     => $tr['data'],
            'totalRows' => $tr['totalRows'],
            'currentPage' => $tr['currentPage'],
            'perPage'   => $tr['perPage']
        ]);
        return view('pages/commons/peserta_didik', $data);
    }

    public function detailSiswa($nisn)
    {
        $detail = $this->dapodikModel->getSiswa($nisn);
        $provinsi = '$this->getProvinces()';
        $fotoUrl = base_url('writable/uploads/foto_siswa/'. $detail['siswa']->foto);
        $data = array_merge($this->data, [
            'title' => 'Detail Siswa',
            'siswa' => $detail['siswa'],
            'foto' => $fotoUrl,
            'kasus' => $detail['kasus'],
            'peserta_didik_id' => $detail['peserta_didik_id'],
            'provinsi' => $provinsi
        ]);
        return view('pages/commons/detail_siswa', $data);
    }

    public function showImage($filename)
    {
        $path = WRITEPATH . 'writable/uploads/foto_siswa/' . $filename;
        if (! file_exists($path)) {
            die('Foto tidak ditemukan.'); 
        }
        $mimeType = mime_content_type($path);
        return $this->response
                    ->setStatusCode(200)
                    ->setContentType($mimeType)
                    ->setBody(file_get_contents($path));
    }

    public function getForm($type, $nisn)
    {

        $nisn = filter_var($nisn, FILTER_SANITIZE_NUMBER_INT);

        $data = [
            'siswa_id' => $nisn,
            'action_url' => base_url('siswa/update_' . $type)
        ];
        
        try {
            switch ($type) {
                case 'kesehatan':
                    $data_kesehatan = $this->dapodikModel->getSiswa($data['siswa_id']); 
                    
                    $data_return = [
                       'data_kesehatan' => $data_kesehatan['siswa'],
                       'peserta_didik_id' => $data_kesehatan['peserta_didik_id'],
                       'form_title' => 'Data Kesehatan Siswa'
                    ];
                    
                    $view_name = 'components/kesehatan'; 
                    break;

                case 'alamat':
                    $data_alamat = $this->dapodikModel->getSiswa($data['siswa_id']); 
                    
                    $data_return = [
                       'data_alamat' => $data_alamat['siswa'],
                       'peserta_didik_id' => $data_alamat['peserta_didik_id'],
                       'form_title' => 'Data Alamat Siswa'
                    ];

                    $view_name = 'components/_form_alamat';
                    break;
                
                case 'orangtua':
                    $data_orangtua = $this->dapodikModel->getSiswa($data['siswa_id']); 
                    
                    $data_return = [
                       'data_orangtua' => $data_orangtua['siswa'],
                       'peserta_didik_id' => $data_orangtua['peserta_didik_id'],
                       'form_title' => 'Data Orangtua Siswa'
                    ];

                    $view_name = 'components/_form_orangtua';
                    break;
                
                case 'hobi':
                    $data_hobi = $this->dapodikModel->getSiswa($data['siswa_id']); 
                    
                    $data_return = [
                       'data_hobi' => $data_hobi['siswa'],
                       'peserta_didik_id' => $data_hobi['peserta_didik_id'],
                       'form_title' => 'Data Hobi & Cita-cita Siswa'
                    ];

                    $view_name = 'components/_form_hobi';
                    break;
                
                case 'biodata':
                    $data_siswa = $this->dapodikModel->getSiswa($data['siswa_id']); 
                    
                    $data_return = [
                       'data_siswa' => $data_siswa['siswa'],
                       'peserta_didik_id' => $data_siswa['peserta_didik_id'],
                       'form_title' => 'Data Orangtua Siswa'
                    ];

                    $view_name = 'components/_form_biodata';
                    break;
                    
                default:
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Jenis form tidak valid: ' . $type);
            }

            return view($view_name, $data_return); 

        } catch (\Exception $e) {
            log_message('error', 'Error loading dynamic form: ' . $e->getMessage());
            
            $errorMessage = '<div class="alert alert-danger" role="alert">';
            $errorMessage .= 'Gagal memuat form (' . $type . '). Kesalahan: ' . $e->getMessage();
            $errorMessage .= '</div>';
            
            $this->response->setStatusCode(500);
            return $errorMessage;
        }
    }

    public function update_data()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false, 
                'message' => 'Metode request tidak diizinkan.'
            ]);
        }
        
        $post = $this->request->getPost();
        $type = $post['type'] ?? null;
        $file = null; 
        $fotoRules = [];
        
        if (!$type) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false, 
                'message' => 'Parameter "type" (jenis data) tidak ditemukan.'
            ]);
        }

        try {
            switch ($type) {
                case 'kesehatan':
                    $model = $this->dapodikModel; 
                    $validation_rules = $this->getValidationRules('kesehatan');
                    $success_msg = 'Data Kesehatan Siswa berhasil diperbarui.';
                    $id = $post['kesehatan_id'];
                    break;

                case 'alamat':
                    $model = $this->dapodikModel; 
                    $validation_rules = $this->getValidationRules('alamat');
                    $success_msg = 'Data Alamat & Kontak berhasil diperbarui.';
                    $id = $post['alamat_id'];
                    break;
                    
                case 'orangtua':
                    $model = $this->dapodikModel;                     
                    $validation_rules = $this->getValidationRules('orangtua');
                    $success_msg = 'Data Orang Tua berhasil diperbarui.';
                    $id = $post['ortu_id'];
                    break;
                    
                case 'hobi':
                    $model = $this->dapodikModel;                     
                    $validation_rules = $this->getValidationRules('hobi');
                    $success_msg = 'Data Hobi berhasil diperbarui.';
                    $id = $post['hobi_id'];
                    break;
                
                case 'biodata':
                    $model = $this->dapodikModel;                     
                    $validation_rules = $this->getValidationRules('biodata');
                    $success_msg = 'Biodata berhasil diperbarui.';
                    $id = $post['peserta_didik_id'];

                    $validationRule = [
                        'foto' => [
                            'label' => 'Image File',
                            'rules' => [
                                'uploaded[foto]',
                                'is_image[foto]',
                                'mime_in[foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                                'max_size[foto,2000]',
                            ],
                        ],
                    ];
                    $validated = $this->validate($validationRule);  
                    if ($validated) {    
                        $path = 'writable/uploads/foto_siswa';
                        $targetDir = WRITEPATH . 'uploads/foto_siswa'; 
                        $image = $this->request->getFile('foto');
                        if ($image && $image->isValid() && !$image->hasMoved()) {
                            $siswa = $this->dapodikModel->getSiswa($post['nisn']);
                            $oldFile = $siswa['siswa']->foto ?? null;
                            $filename = $image->getRandomName(); 
                            $destinationPath = $targetDir . DIRECTORY_SEPARATOR . $filename;
                            if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
                            if (compressImage($image->getTempName(), $destinationPath, 25)) {
                                if($oldFile) {
                                    $oldFilePath = $targetDir . DIRECTORY_SEPARATOR . $oldFile;
                                    if (file_exists($oldFilePath)) {
                                        @unlink($oldFilePath); 
                                    }
                                }
                                $post['foto'] = $filename;
                            } else {
                            }
                        } else {
                            unset($post['foto']); 
                        }
                    }
                    break;

                default:
                    return $this->response->setStatusCode(400)->setJSON([
                        'success' => false, 
                        'message' => 'Jenis data (' . $type . ') tidak valid.'
                    ]);
            }

            if (!$this->validate($validation_rules)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Validasi gagal. Periksa kembali input Anda.',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            if ($id="") {
                $result = $model->insert($post);
            } else {
                if($type==='biodata'){
                    $result = $model->update_detail($post, $file);
                } else {
                    $result = $model->update_detail($post);
                }
            }

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => $success_msg
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan ke database. Mungkin tidak ada perubahan data.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Update data error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    protected function getValidationRules(string $type)
    {
        switch ($type) {
            case 'kesehatan':
                return [
                    'peserta_didik_id' => 'required|string',
                    'tinggi_badan' => 'required|integer|greater_than[0]',
                    'berat_badan' => 'required|integer|greater_than[0]',
                    'kebutuhan_khusus' => 'required|max_length[50]',
                ];
            case 'alamat':
                return [
                    'peserta_didik_id' => 'required|string',
                    'alamat' => 'required|max_length[255]',
                ];
            case 'orangtua':
                return [
                    'nama_ayah' => 'required|string',
                    'nama_ibu' => 'required|string',
                    'nama_wali' => 'string',
                ];
            case 'biodata':
                return [
                    'nama' => 'required|string',
                    'nisn' => 'required|string',
                    'nik' => 'required|string',
                ];
            case 'hobi':
                return [
                    'hobi' => 'required|string',
                    'cita_cita' => 'required|string',
                ];
        }
    }

    public function getProvinces()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $data = file_get_contents("https://wilayah.id/api/provinces.json");
        echo $data;
    }

    public function getRegencies($provinceId)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        if (!$provinceId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID provinsi tidak valid']);
            exit;
        }

        $url = "https://wilayah.id/api/regencies/$provinceId.json";
        $data = file_get_contents($url);

        if ($data === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil data dari sumber']);
            exit;
        }

        echo $data;
    }

    public function getDistricts($districtId)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        if (!$districtId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID Kabupaten/Kota tidak valid']);
            exit;
        }

        $url = "https://wilayah.id/api/districts/$districtId.json";
        $data = file_get_contents($url);

        if ($data === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil data dari sumber']);
            exit;
        }

        echo $data;
    }

    public function getVillages($villageId)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        if (!$villageId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID Kecamatan tidak valid']);
            exit;
        }

        $url = "https://wilayah.id/api/villages/$villageId.json";
        $data = file_get_contents($url);

        if ($data === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil data dari sumber']);
            exit;
        }

        echo $data;
    }

    public function deleteSiswa($pd_id)
    {
        try {
        $result = $this->dapodikModel->deleteSingleSiswa($pd_id);
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus data atau siswa tidak ditemukan'
            ]);
        }

    } catch (\Exception $e) {
        log_message('error', 'Error deleting single student data: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem saat menghapus data.'
        ]);
    }
    }
}
		