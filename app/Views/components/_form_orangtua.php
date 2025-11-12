<form id="formUpdateOrangtua" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="orangtua" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="ortu_id" value="<?= $data_orangtua->ortu_id ?? ''; ?>">

    <div class="row g-3 align-items-center">
        <h3 class="text-secondary">A. Data Ayah</h3>
        <div class="col-sm-3">
            <label for="nama_ayah" class="form-label">Nama Ayah</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nama_ayah" type="text" class="form-control" name="nama_ayah" value="<?= $data_orangtua->nama_ayah ?? ''; ?>">
        </div>

        <div class="col-sm-3">
            <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tempat_lahir_ayah" type="text" class="form-control" name="tempat_lahir_ayah" value="<?= $data_orangtua->tempat_lahir_ayah ?? ''; ?>">
        </div>
        <div class="col-sm-3">
            <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tanggal_lahir_ayah" type="date" class="form-control" name="tanggal_lahir_ayah" value="<?= tanggal_indo($data_orangtua->tanggal_lahir_ayah ?? ''); ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="pendidikan_ayah" class="form-label">Pendidikan Ayah</label>
        </div>
        <div class="col-sm-8">
            <select name="pendidikan_ayah" class="form-select" id="pendidikan_ayah">
                <?= pendidikan(); ?>
            </select>
        </div>
        
        <div class="col-sm-3">
            <label for="pekerjaan_ayah_id" class="form-label">Pekerjaan Ayah</label>
        </div>
        <div class="col-sm-8">
            <select name="pekerjaan_ayah_id" class="form-select" id="pekerjaan_ayah_id">
                <option value="<?= $data_orangtua->pekerjaan_ayah_id ?? '' ?>" selected> <?= job($data_orangtua->pekerjaan_ayah_id) ?? '= Pilih Pekerjaan Ayah ='?></option>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah</label>
        </div>
        <div class="col-sm-8">
            <select name="penghasilan_ayah" class="form-select" id="penghasilan_ayah">
                <?= penghasilan(); ?>
            </select>
        </div>
        
        <div class="col-sm-3">
            <label for="telepon_ayah" class="form-label">No. Telepon Ayah</label>
        </div>
        <div class="col-sm-8"> 
            <input id="telepon_ayah" type="text" class="form-control" name="telepon_ayah" value="<?= $data_orangtua->telepon_ayah ?? ''; ?>">
        </div>
        
        <h3 class="text-secondary">B. Data Ibu</h3>
        <div class="col-sm-3">
            <label for="nama_ibu" class="form-label">Nama Ibu</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nama_ibu" type="text" class="form-control" name="nama_ibu" value="<?= $data_orangtua->nama_ibu ?? ''; ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tempat_lahir_ibu" type="text" class="form-control" name="tempat_lahir_ibu" value="<?= $data_orangtua->tempat_lahir_ibu ?? ''; ?>">
        </div>
        <div class="col-sm-3">
            <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tanggal_lahir_ibu" type="date" class="form-control" name="tanggal_lahir_ibu" value="<?= tanggal_indo($data_orangtua->tanggal_lahir_ibu ?? ''); ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="pendidikan_ibu" class="form-label">Pendidikan Ibu</label>
        </div>
        <div class="col-sm-8">
            <select name="pendidikan_ibu" class="form-select" id="pendidikan_ibu">
                <?= pendidikan(); ?>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="pekerjaan_ibu_id" class="form-label">Pekerjaan Ibu</label>
        </div>
        <div class="col-sm-8">
            <select name="pekerjaan_ibu_id" class="form-select" id="pekerjaan_ibu_id">
                <option value="<?= $data_orangtua->pekerjaan_ibu_id ?? '' ?>" selected> <?= job($data_orangtua->pekerjaan_ibu_id) ?? '= Pilih Pekerjaan Ibu ='?></option>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu</label>
        </div>
        <div class="col-sm-8">
            <select name="penghasilan_ibu" class="form-select" id="penghasilan_ibu">
                <?= penghasilan(); ?>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="telepon_ibu" class="form-label">No. Telepon Ibu</label>
        </div>
        <div class="col-sm-8"> 
            <input id="telepon_ibu" type="text" class="form-control" name="telepon_ibu" value="<?= $data_orangtua->telepon_ibu ?? ''; ?>">
        </div>
        
        <h3 class="text-secondary">C. Data Wali</h3>
        <div class="col-sm-3">
            <label for="nama_wali" class="form-label">Nama Wali</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nama_wali" type="text" class="form-control" name="nama_wali" value="<?= $data_orangtua->nama_wali ?? ''; ?>">
        </div>
        
        <div class="form-wali col-sm-3">
            <label for="tempat_lahir_wali" class="form-label">Tempat Lahir Wali</label>
        </div>
        <div class="form-wali col-sm-8"> 
            <input id="tempat_lahir_wali" type="text" class="form-control" name="tempat_lahir_wali" value="<?= $data_orangtua->tempat_lahir_wali ?? ''; ?>">
        </div>
        <div class="form-wali col-sm-3">
            <label for="tanggal_lahir_wali" class="form-label">Tanggal Lahir Wali</label>
        </div>
        <div class="form-wali col-sm-8"> 
            <input id="tanggal_lahir_wali" type="date" class="form-control" name="tanggal_lahir_wali" value="<?= tanggal_indo($data_orangtua->tanggal_lahir_wali ?? ''); ?>">
        </div>
        
        <div class="form-wali col-sm-3">
            <label for="pendidikan_wali" class="form-label">Pendidikan Wali</label>
        </div>
        <div class="form-wali col-sm-8">
            <select name="pendidikan_wali" class="form-select" id="pendidikan_wali">
                <?= pendidikan(); ?>
            </select>
        </div>

        <div class="form-wali col-sm-3">
            <label for="pekerjaan_wali_id" class="form-label">Pekerjaan Wali</label>
        </div>
        <div class="form-wali col-sm-8">
            <select name="pekerjaan_wali_id" class="form-select" id="pekerjaan_wali_id">
                <option value="<?= $data_orangtua->pekerjaan_wali_id ?? '' ?>" selected> <?= job($data_orangtua->pekerjaan_wali_id) ?? '= Pilih Pekerjaan Wali ='?></option>
            </select>
        </div>

        <div class="form-wali col-sm-3">
            <label for="penghasilan_wali" class="form-label">Penghasilan Wali</label>
        </div>
        <div class="form-wali col-sm-8">
            <select name="penghasilan_wali" class="form-select" id="penghasilan_wali">
                <?= penghasilan(); ?>
            </select>
        </div>

        <div class="form-wali col-sm-3">
            <label for="telepon_wali" class="form-label">No. Telepon Wali</label>
        </div>
        <div class="form-wali col-sm-8"> 
            <input id="telepon_wali" type="text" class="form-control" name="telepon_wali" value="<?= $data_orangtua->telepon_wali ?? ''; ?>">
        </div>
    </div>
        
    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type='submit' class='btn btn-success' id='btnSaveDynamic'>
            <i class='fas fa-save'></i> Simpan
        </button>   
    </div>
</form>

<script>
    $(document).ready(function(){
        function generatePekerjaanOptions() {
            let optionsHtml = ''; 
            const codes = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 99]; 
            
            codes.forEach(code => {
                let jobName;            
                switch (code) {
                    case 1:
                        jobName = 'Tidak Bekerja';
                        break;
                    case 2:
                        jobName = 'Nelayan';
                        break;
                    case 3:
                        jobName = 'Petani';
                        break;
                    case 4:
                        jobName = 'Peternak';
                        break;
                    case 5:
                        jobName = 'PNS/TNI/Polri';
                        break;
                    case 6:
                        jobName = 'Karyawan Swasta';
                        break;
                    case 7:
                        jobName = 'Pedagang Kecil';
                        break;
                    case 8:
                        jobName = 'Pedagang Besar';
                        break;
                    case 9:
                        jobName = 'Wiraswasta';
                        break;
                    case 10:
                        jobName = 'Wirausaha';
                        break;
                    case 11:
                        jobName = 'Buruh';
                        break;
                    case 12:
                        jobName = 'Pensiunan';
                        break;
                    case 13:
                        jobName = 'Tenaga Kerja Indonesia';
                        break;
                    case 14:
                        jobName = 'Karyawan BUMN';
                        break;
                    case 15:
                        jobName = 'Tidak dapat diterapkan';
                        break;
                    case 16:
                        jobName = 'Sudah Meninggal';
                        break;
                    case 99:
                        jobName = 'Lainnya';
                        break;
                    default:
                        jobName = '-';
                        break;
                }
                optionsHtml += `<option value="${code}">${jobName}</option>`;
            });
            return optionsHtml;
        }

        const jobAyah = $('#pekerjaan_ayah_id').val();
        if(jobAyah ==='1' || jobAyah==='16')
        {
            $('#penghasilan_ayah').val('Tidak Berpenghasilan'); 
            $('#penghasilan_ayah').prop('disabled', true); 
        } else {
            $('#penghasilan_ayah').prop('disabled', false);
        }
        const jobIbu = $('#pekerjaan_ibu_id').val();
        if(jobIbu ==='1' || jobIbu==='16')
        {
            $('#penghasilan_ibu').val('Tidak Berpenghasilan'); 
            $('#penghasilan_ibu').prop('disabled', true); 
        } else {
            $('#penghasilan_ibu').prop('disabled', false);
        }
        const jobwali = $('#pekerjaan_wali_id').val();
        if(jobwali ==='1' || jobwali==='16')
        {
            $('#penghasilan_wali').val('Tidak Berpenghasilan'); 
            $('#penghasilan_wali').prop('disabled', true); 
        } else {
            $('#penghasilan_wali').prop('disabled', false);
        }

        $('#pekerjaan_ayah_id').one('focus', function() {
            const options = generatePekerjaanOptions();
            $(this).empty().append(options);
        });
        $('#pekerjaan_ayah_id').on('change', function() {
            const selectedCode = $(this).val();
            if (selectedCode === '1' || selectedCode === '16') {                
                $('#penghasilan_ayah').val('Tidak Berpenghasilan'); 
                $('#penghasilan_ayah').prop('disabled', true); 
            } else {
                $('#penghasilan_ayah').prop('disabled', false);
            }
        });

        $('#pekerjaan_ibu_id').one('focus', function() {
            const options = generatePekerjaanOptions();
            $(this).empty().append(options);
        });
        $('#pekerjaan_ibu_id').on('change', function() {
            const selectedCode = $(this).val();
            if (selectedCode === '1' || selectedCode === '16') {                
                $('#penghasilan_ibu').val('Tidak Berpenghasilan'); 
                $('#penghasilan_ibu').prop('disabled', true); 
            } else {
                $('#penghasilan_ibu').prop('disabled', false);
            }
        });

        const $namaWaliInput = $('#nama_wali');
        const $turunanWali = $('.form-wali'); 
        function toggleWaliForms() {
            const namaWaliValue = $namaWaliInput.val().trim(); 

            if (namaWaliValue === '') {
                $turunanWali.slideUp(200); 
            } else {
                $turunanWali.slideDown(200);
            }
        }
        $namaWaliInput.on('keyup', toggleWaliForms);
        $namaWaliInput.on('change', toggleWaliForms);
        toggleWaliForms();

        $('#pekerjaan_wali_id').one('focus', function() {
            const options = generatePekerjaanOptions();
            $(this).empty().append(options);
        });
        $('#pekerjaan_wali_id').on('change', function() {
            const selectedCode = $(this).val();
            if (selectedCode === '1' || selectedCode === '16') {                
                $('#penghasilan_wali').val('Tidak Berpenghasilan'); 
                $('#penghasilan_wali').prop('disabled', true); 
            } else {
                $('#penghasilan_wali').prop('disabled', false);
            }
        });
    })
</script>