<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 text-white"><strong><?= $title.": ". strtoupper($siswa->nama);?></strong></h1>
            <button type="button" class="btn btn-sm btn-outline-light btn-dynamic-edit" data-action="biodata"  data-bs-toggle="modal" data-bs-target="#globalEditModal" data-title="Edit Biodata Siswa">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>
        <div class="card-body fs-5">

            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <?php 
                    $foto_tersedia = !empty($siswa->foto);
                    $jenis_kelamin = $siswa->jenis_kelamin ?? 'P'; // Asumsi default P
                    ?>

                    <?php if ($foto_tersedia): ?>
                        <img src="<?= $foto ?>" 
                            alt="Foto Siswa: <?= $siswa->nama ?>" 
                            class="img-thumbnail" 
                            style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: 
                        // FOTO TIDAK ADA: Tampilkan Ikon Placeholder
                        $icon_class = ($jenis_kelamin === 'L') ? 'fa-male' : 'fa-female';
                    ?>
                        <div class="bg-light d-flex justify-content-center align-items-center mx-auto" 
                            style="width: 150px; height: 150px; border: 1px solid #ccc;">
                            <i class="fas <?= $icon_class ?>" style="font-size: 80px; color: #6c757d;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <table class="table table-sm table-borderless">
                        <tr><th><i class="fas fa-id-badge me-2 text-primary"></i> NIK</th><td>:</td><td><?= $siswa->nik ?></td></tr>
                        <tr><th><i class="fas fa-id-badge me-2 text-primary"></i> NISN/NIPD</th><td>:</td><td><?= $siswa->nisn . ' / ' . $siswa->nipd ?></td></tr>
                        <tr><th><i class="fas fa-map-marker-alt me-2 text-info"></i> Tempat, Tgl. Lahir</th><td>:</td><td><?= $siswa->tempat_lahir . ', ' . date('d M Y', strtotime($siswa->tanggal_lahir)) ?></td></tr>
                        <tr><th><i class="fas fa-venus-mars me-2 text-danger"></i> Jenis Kelamin</th><td>:</td><td><?= $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                        <tr><th><i class="fas fa-user me-2 text-warning"></i> Anak-ke</th><td>:</td><td><?= $siswa->anak_keberapa ?></td></tr>
                        <tr><th><i class="fas fa-users me-2 text-primary"></i> Jumlah Saudara</th><td>:</td><td><?= $siswa->jumlah_saudara ?></td></tr>
                        <tr><th><i class="fas fa-home me-2 text-danger"></i> Tinggal Bersama</th><td>:</td><td><?= $siswa->tinggal_bersama ?></td></tr>
                        <tr><th><i class="fa-solid fa-person-walking me-2 text-success"></i> Jenis Transportasi</th><td>:</td><td><?= $siswa->jenis_transportasi ?></td></tr>
                    </table>
                </div>
            </div>

            <hr>

            <ul class="nav nav-tabs" id="siswaTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="alamat-tab" data-bs-toggle="tab" data-bs-target="#alamat-data" type="button" role="tab">
                        <i class="fas fa-home me-1"></i> Alamat & Kontak
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat-data" type="button" role="tab">
                        <i class="fas fa-home me-1"></i> Pendidikan Sebelumnya
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ortu-tab" data-bs-toggle="tab" data-bs-target="#ortu-data" type="button" role="tab">
                        <i class="fas fa-user-friends me-1"></i> Data Orang Tua
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="hobi-tab" data-bs-toggle="tab" data-bs-target="#hobi-data" type="button" role="tab">
                        <i class="fas fa-heart me-1"></i> Hobi & Cita-Cita
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kesehatan-tab" data-bs-toggle="tab" data-bs-target="#kesehatan-data" type="button" role="tab">
                        <i class="fas fa-heartbeat me-1"></i> Kesehatan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kasus-tab" data-bs-toggle="tab" data-bs-target="#kasus-data" type="button" role="tab">
                        <i class="fas fa-exclamation-triangle me-1"></i> Kasus Siswa (<?= count($kasus); ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content pt-3" id="siswaTabsContent">
                
                <div class="tab-pane fade show active" id="alamat-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th style="width: 25%;"><i class="fas fa-map-marked-alt me-2"></i> Alamat Lengkap</th><td><?= $siswa->alamat ?? '-' ?> RT/RW<?= $siswa->rt ?? '-' ?>/<?= $siswa->rw ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-city me-2"></i> Desa/Kecamatan/Kab/Prov</th><td><?= $siswa->desa_kelurahan ?? '-' ?> / <?= $siswa->kecamatan ?? '-' ?> / <?= $siswa->kabupaten ?? '-' ?> / <?= $siswa->provinsi ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-mobile-alt me-2"></i> Telepon Seluler</th><td><?= $siswa->telepon_seluler ?? $siswa->telepon_rumah ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-envelope me-2"></i> Email</th><td><?= $siswa->email ?? '-' ?></td></tr>
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-dynamic-edit" data-action="alamat"  data-bs-toggle="modal" data-bs-target="#globalEditModal" data-title="Edit Data Alamat">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>

                <div class="tab-pane fade" id="riwayat-data" role="tabpanel">    
                </div>

                <div class="tab-pane fade" id="ortu-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th colspan="2" class="bg-info px-4 pt-2"><h3 class="h3 text-white pt-1 pb-0"><i class="fas fa-grip-vertical"></i> Data Ayah</h3></th></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-credit-card me-2"></i> Nama Ayah</th><td><?= $siswa->nama_ayah ?? '-' ?></td></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-calendar me-2"></i> Tempat, Tanggal Lahir Ayah</th><td><?= $siswa->tempat_lahir_ayah ?? '-' ?>, <?= tanggal_indo($siswa->tanggal_lahir_ayah ?? '-') ?></td></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-book-open me-2"></i> Pendidikan Ayah</th><td><?= $siswa->pendidikan_ayah ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Ayah</th><td><?= job($siswa->pekerjaan_ayah_id) ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-dollar-sign me-2"></i> Penghasilan Ayah</th><td><?= $siswa->penghasilan_ayah ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-mobile-alt me-2"></i> No. Telepon Ayah</th><td><?= $siswa->telepon_ayah ?? '-' ?></td></tr>

                        <tr><th colspan="2" class="bg-info px-4 pt-2"><h3 class="h3 text-white pt-1 pb-0"><i class="fas fa-grip-vertical"></i> Data Ibu</h3></th></tr>
                        <tr><th><i class="fas fa-credit-card me-2"></i> Nama Ibu</th><td><?= $siswa->nama_ibu ?? '-' ?></td></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-calendar me-2"></i> Tempat, Tanggal Lahir Ibu</th><td><?= $siswa->tempat_lahir_ibu ?? '-' ?>, <?= tanggal_indo($siswa->tanggal_lahir_ibu ?? '-') ?></td></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-book-open me-2"></i> Pendidikan Ayah</th><td><?= $siswa->pendidikan_ibu ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Ibu</th><td><?= job($siswa->pekerjaan_ibu_id) ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-dollar-sign me-2"></i> Penghasilan Ibu</th><td><?= $siswa->penghasilan_ibu ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-mobile-alt me-2"></i> No. Telepon Ibu</th><td><?= $siswa->telepon_ibu ?? '-' ?></td></tr>
                        
                        <tr><th colspan="2" class="bg-info px-4 pt-2"><h3 class="h3 text-white pt-1 pb-0"><i class="fas fa-grip-vertical"></i> Data Wali</h3></th></tr>
                        <tr><th><i class="fas fa-user-tie me-2"></i> Nama Wali</th><td><?= $siswa->nama_wali ?? '-' ?></td></tr>
                        <?php
                            if($siswa->nama_wali=''){
                        ?>
                        <tr><th style="width: 25%;"><i class="fas fa-calendar me-2"></i> Tempat, Tanggal Lahir Wali</th><td><?= $siswa->tempat_lahir_wali ?? '-' ?>, <?= tanggal_indo($siswa->tanggal_lahir_wali ?? '-') ?></td></tr>
                        <tr><th style="width: 25%;"><i class="fas fa-book-open me-2"></i> Pendidikan Ayah</th><td><?= $siswa->pendidikan_wali ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Wali</th><td><?= job($siswa->pekerjaan_wali_id) ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-dollar-sign me-2"></i> Penghasilan Wali</th><td><?= $siswa->penghasilan_wali ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-mobile-alt me-2"></i> No. Telepon Wali</th><td><?= $siswa->telepon_wali ?? '-' ?></td></tr>
                        <?php
                            } else {
                        ?>
                        <tr>
                            <th colspan="2" class="py-2"></th>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-dynamic-edit" data-action="orangtua"  data-bs-toggle="modal" data-bs-target="#globalEditModal" data-title="Edit Data Orangtua">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>

                <div class="tab-pane fade" id="hobi-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th style="width: 25%;"><i class="fas fa-puzzle-piece me-2"></i> Hobi</th><td><?= $siswa->hobi ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-plane-departure me-2"></i> Cita-cita</th><td><?= $siswa->cita_cita ?? '-' ?></td></tr>
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-dynamic-edit" data-action="hobi"  data-bs-toggle="modal" data-bs-target="#globalEditModal" data-title="Edit Data Hobi">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>

                <div class="tab-pane fade" id="kesehatan-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th style="width: 25%;"><i class="fas fa-ruler-vertical me-2"></i> Tinggi Badan</th><td><?= $siswa->tinggi_badan ?? '-' ?> cm</td></tr>
                        <tr><th><i class="fas fa-weight me-2"></i> Berat Badan</th><td><?= $siswa->berat_badan ?? '-' ?> kg</td></tr>
                        <tr><th><i class="fas fa-wheelchair me-2"></i> Kebutuhan Khusus</th><td><?= $siswa->kebutuhan_khusus ?? '-' ?></td></tr>
                    </table>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-dynamic-edit" data-action="kesehatan"  data-bs-toggle="modal" data-bs-target="#globalEditModal" data-title="Edit Data Kesehatan">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>

                <div class="tab-pane fade" id="kasus-data" role="tabpanel">
                    <?php if (!empty($kasus)): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar-alt me-1"></i> Tanggal</th>
                                <th><i class="fas fa-gavel me-1"></i> Jenis Kasus</th>
                                <th><i class="fas fa-file-alt me-1"></i> Deskripsi</th>
                                <th><i class="fas fa-tasks me-1"></i> Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kasus as $k): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($k['tanggal'])) ?></td>
                                <td><?= $k['jenis_kasus'] ?></td>
                                <td><?= $k['deskripsi'] ?></td>
                                <td><?= $k['tindak_lanjut'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-check-circle me-1"></i> Tidak ada catatan kasus untuk siswa ini.
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="globalEditModal" tabindex="-1" aria-labelledby="globalEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="globalEditModalLabel">
                    <i class="fas fa-edit"></i> Edit Data Siswa
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="text-center" id="modal-loading">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat formulir...
                </div>
                <div id="dynamic-form-container">
                    </div>
            </div>
            
            <div class="modal-footer d-none" id="modal-footer-static">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                 </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const CURRENT_SISWA_ID = '<?php echo $siswa->nisn; ?>'; 
    const modal = $('#globalEditModal');
    const modalTitle = $('#globalEditModalLabel');
    const formContainer = $('#dynamic-form-container');
    const loadingDiv = $('#modal-loading');

    $(document).on('click', '.btn-dynamic-edit', function() {
        const actionType = $(this).data('action');
        const title = $(this).data('title'); 
        const apiUrl = '/dapodik/get_form/' + actionType + '/' + CURRENT_SISWA_ID;
        const urlWil = '/dapodik/getProvinces'; 
        if(actionType==="kesehatan"||actionType==="hobi"){
            $('.modal-dialog').removeClass('modal-lg')
        } else {
            $('.modal-dialog').addClass('modal-lg')
        }

        modalTitle.html('<i class="fas fa-edit"></i> ' + title);
        formContainer.empty().hide();
        loadingDiv.show();
        modal.modal('show');

        $.ajax({
            url: apiUrl,
            method: 'GET',
            success: function(response) {
                formContainer.html(response).show();
                loadingDiv.hide();
            },
            error: function() {
                formContainer.html('<div class="alert alert-danger">Gagal memuat formulir.</div>').show();
                loadingDiv.hide();
            }
        });
        
    });

    formContainer.on('submit', 'form', function(e) {
        e.preventDefault(); 
        const form = $(this);
        const btn = $('#btnSaveDynamic');
        const originalText = btn.html();
        
        const dataType = form.find('input[name="type"]').val(); 
        
        let ajaxData;
        let ajaxContentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        let ajaxProcessData = true;

        if (dataType === 'biodata') {
            ajaxData = new FormData(form[0]);
            ajaxContentType = false; 
            ajaxProcessData = false; 
        } else {
            ajaxData = form.serialize();
        }

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
                        modal.modal('hide');
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
});
</script>
<?= $this->endSection() ?>