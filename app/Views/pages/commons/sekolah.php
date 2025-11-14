<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white p-2 d-flex justify-content-between align-items-center">
            <h1 class="px-2 h3 text-white mb-0"><strong><i class="fa fa-home"></i> <?= $title;?></strong></h1>
            <button type="button" class="btn btn-light edit">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>
        <div class="card-body fs-5">
            <div class="container my-4 data-sekolah">

                <form action="<?= base_url('dapodik/update_data'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="sekolah" id="type">

                    <div class="row mb-3 align-items-center justify-content-around">
                        <div class="col-md-4" id="previewContainer">
                            <h4>Icon</h4>
                            <img id="imagePreview" src="<?= $path.$data->icon ?? 'placeholder.png' ?>" alt="Pratinjau Foto" 
                                    style="max-width: 100%; max-height: 100px; border: 1px solid #ccc; display: block; margin: 0 auto;">
                            <p class="text-muted mt-2">Pratinjau akan muncul setelah Anda memilih file.</p>
                        
                            <label for="icon" class="form-label">Pilih File Icon (Max 2MB)</label>
                            <input class="form-control" type="file" id="icon" name="icon" accept="image/*">
                        </div>
                        <div class="col-md-4" id="previewContainer">
                            <h4>Kop Surat</h4>
                            <img id="kopPreview" src="<?= $path.$data->kop_sekolah ?? 'placeholder.png' ?>" alt="Pratinjau Foto" 
                                    style="max-width: 100%; max-height: 100px; border: 1px solid #ccc; display: block; margin: 0 auto;">
                            <p class="text-muted mt-2">Pratinjau akan muncul setelah Anda memilih file.</p>
                        
                            <label for="kop" class="form-label">Pilih File Kop (Max 2MB)</label>
                            <input class="form-control" type="file" id="kop" name="kop_sekolah" accept="image/*">
                        </div>
                    </div>
                    <hr class="bg-secondary py-1">
                    <div class="row mb-3 align-items-center">
                        <label for="namaSekolah" class="col-md-3 col-form-label">Nama Sekolah</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="namaSekolah" name="nama" value="<?= $data->nama ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="npsn" class="col-md-3 col-form-label">NPSN</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="npsn" name="npsn" value="<?= $data->npsn ?>" >
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="nss" class="col-md-3 col-form-label">NSS</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="nss" name="nss" value="<?= $data->nss ?>" >
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="kepalaSekolah" class="col-md-3 col-form-label">Kepala Sekolah</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="kepala_sekolah" name="kepala_sekolah" value="<?= $data->kepala_sekolah ?>" >
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="alamatJalan" class="col-md-3 col-form-label">Alamat Jalan</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="alamatJalan" name="alamat_jalan" value="<?= $data->alamat_jalan ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label for="rt" class="col-md-3 col-form-label">RT / RW</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            
                            <label for="rt" class="me-2">RT</label>
                            <input type="number" class="form-control me-4" id="rt" name="rt" style="max-width: 100px;" value="<?= $data->rt ?>">
                            
                            <label for="rw" class="me-2 ms-4">RW</label>
                            <input type="number" class="form-control" id="rw" name="rw" style="max-width: 100px;" value="<?= $data->rw ?>">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="desaKelurahan" class="col-md-3 col-form-label">Desa/Kelurahan</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="desaKelurahan" name="desa_kelurahan" value="<?= $data->desa_kelurahan ?>">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="kodePos" class="col-md-3 col-form-label">Kode Pos</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="kodePos" name="kode_pos" value="<?= $data->kode_pos ?>">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="kecamatan" class="col-md-3 col-form-label">Kecamatan</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= $data->kecamatan ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label for="kabupatenKota" class="col-md-3 col-form-label">Kabupaten/Kota</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="kabupatenKota" name="kabupaten_kota" value="<?= $data->kabupaten_kota ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label for="provinsi" class="col-md-3 col-form-label">Provinsi</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="<?= $data->provinsi ?>">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="noTelepon" class="col-md-3 col-form-label">No. Telepon</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="noTelepon" name="nomor_telepon" value="<?= $data->nomor_telepon ?>">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="email" class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $data->email ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label for="website" class="col-md-3 col-form-label">Website</label>
                        <div class="col-md-9 d-flex align-items-center">
                            <span class="me-2">:</span>
                            <input type="text" class="form-control" id="website" name="website" value="<?= $data->website ?>">
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-9 offset-md-3">
                            <button type="submit" class="btn btn-primary d-none save"><i class="fa fa-save"></i> Simpan Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.data-sekolah input').prop('disabled', true);
        
        $('.edit').on('click', function(){
            $('.data-sekolah input').prop('disabled', false);
            $('.data-sekolah .save').removeClass('d-none');
        })

        const $icon = $('#icon');
        const $kop = $('#kop');
        const $imagePreview = $('#imagePreview');
        const $kopPreview = $('#kopPreview');
        const $previewMessage = $('#previewContainer p.text-muted');
        
        const defaultPlaceholderSrc = 'https://placehold.co/150x150/EAEAEA/333333?text=NO+IMAGE';

        function resetSinglePreview($targetPreview) {
            $targetPreview.attr('src', defaultPlaceholderSrc);
            
            if ($imagePreview.attr('src') === defaultPlaceholderSrc && $kopPreview.attr('src') === defaultPlaceholderSrc) {
                if (typeof $previewMessage !== 'undefined') {
                    $previewMessage.removeClass('d-none');
                }
            }
        }

        // resetSinglePreview($imagePreview);
        // resetSinglePreview($kopPreview);

        $icon.on('change', function() {
            prev($(this), $imagePreview);
        });

        $kop.on('change', function(){
            prev($(this), $kopPreview);
        });

        function prev($inputElement, $targetPreview) {
            const inputDOM = $inputElement.get(0); 

            if (inputDOM.files && inputDOM.files.length > 0 && inputDOM.files[0]) {
                const file = inputDOM.files[0];
                
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $targetPreview.attr('src', e.target.result);
                        
                        if (typeof $previewMessage !== 'undefined') {
                            $previewMessage.addClass('d-none'); 
                        }
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert("File yang Anda pilih bukan format gambar yang valid.");
                    $inputElement.val(''); 
                    resetSinglePreview($targetPreview);
                }
            } else {
                resetSinglePreview($targetPreview);
            }
        }
        
        const dataSekolah = $('.data-sekolah');

            dataSekolah.on('submit', 'form', function(e) {
            e.preventDefault(); 
            const form = $(this);
            const btn = $('.save');
            const originalText = btn.html();
                        
            let ajaxData;
            let ajaxContentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            let ajaxProcessData = true;
            ajaxData = new FormData(form[0]);
            ajaxContentType = false; 
            ajaxProcessData = false; 
            
            btn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                method: 'POST', 
                data: ajaxData, 
                contentType: ajaxContentType,
                processData: ajaxProcessData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            // modal.modal('hide');
                            window.location.reload(); 
                        });
                    } else {
                        if (response.errors) {
                            const errorMessages = Object.values(response.errors).join('<br>');
                            Swal.fire('Gagal Validasi!', errorMessages, 'error');
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan koneksi saat menyimpan.', 'error');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    })
</script>
<?= $this->endSection();?>