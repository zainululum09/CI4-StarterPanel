<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DapodikModel;

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
        $data = array_merge($this->data, [
            'title' => 'Detail Siswa',
            'siswa' => $detail['siswa'],
            'kasus' => $detail['kasus'],
            'peserta_didik_id' => $detail['peserta_didik_id']
        ]);
        return view('pages/commons/detail_siswa', $data);
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
                    
                    $data = [
                       'data_kesehatan' => $data_kesehatan['siswa'],
                       'peserta_didik_id' => $data_kesehatan['peserta_didik_id'],
                       'form_title' => 'Data Kesehatan Siswa'
                    ];
                    
                    $view_name = 'components/kesehatan'; 
                    break;

                case 'alamat':
                    // $data_alamat = $alamatModel->getSiswa($nisn);
                    
                    // $data['data_alamat'] = $data_alamat;
                    // $data['form_title'] = 'Alamat & Kontak Siswa';

                    $view_name = 'siswa/_form_alamat';
                    break;
                    
                default:
                    throw new \CodeIgniter\Exceptions\PageNotFoundException('Jenis form tidak valid: ' . $type);
            }

            return view($view_name, $data); 

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
                    break;

                case 'alamat':
                    $model = $this->alamatModel; 
                    $validation_rules = $this->getValidationRules('alamat');
                    $success_msg = 'Data Alamat & Kontak berhasil diperbarui.';
                    break;
                    
                case 'orang_tua':
                    $model = $this->orangTuaModel; 
                    $validation_rules = $this->getValidationRules('orang_tua');
                    $success_msg = 'Data Orang Tua berhasil diperbarui.';
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
            unset($post['type']); 
            
            $data=[
                "peserta_didik_id" => $post['peserta_didik_id'],
                "kesehatan_id"  => $post['kesehatan_id'],
                "tinggi_badan"  => $post['tinggi_badan'],
                "berat_badan"   => $post['berat_badan'],
                "kebutuhan_khusus" => $post['kebutuhan_khusus']
            ];

            // if (empty($post['kesehatan_id'])) {
            //     $result = $model->insert($data);
            // } else {
                $result = $model->update_detail($post);
            // }

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
                    'alamat_jalan' => 'required|max_length[255]',
                ];
            default:
                return [];
        }
    }
}
		