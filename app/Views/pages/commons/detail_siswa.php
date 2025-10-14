<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3"><strong><?= $title.": ". $siswa->nama;?></strong></h1>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i> Biodata</h4>
            <a href="/siswa/edit/<?= $siswa->peserta_didik_id ?>" class="btn btn-warning btn-sm" title="Edit Data Siswa">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <?php 
                    $foto_tersedia = !empty($siswa->foto);
                    $jenis_kelamin = $siswa->jenis_kelamin ?? 'P'; // Asumsi default P
                    ?>

                    <?php if ($foto_tersedia): ?>
                        <img src="<?= $siswa->foto ?>" 
                            alt="Foto Siswa: <?= $siswa->nama ?>" 
                            class="img-thumbnail rounded-circle" 
                            style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: 
                        // FOTO TIDAK ADA: Tampilkan Ikon Placeholder
                        $icon_class = ($jenis_kelamin === 'L') ? 'fa-male' : 'fa-female';
                    ?>
                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center mx-auto" 
                            style="width: 150px; height: 150px; border: 1px solid #ccc;">
                            <i class="fas <?= $icon_class ?>" style="font-size: 80px; color: #6c757d;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <table class="table table-sm table-borderless">
                        <tr><th><i class="fas fa-id-badge me-2 text-primary"></i> NISN/NIPD</th><td>:</td><td><?= $siswa->nisn . ' / ' . $siswa->nipd ?></td></tr>
                        <tr><th><i class="fas fa-map-marker-alt me-2 text-info"></i> Tempat, Tgl. Lahir</th><td>:</td><td><?= $siswa->tempat_lahir . ', ' . date('d M Y', strtotime($siswa->tanggal_lahir)) ?></td></tr>
                        <tr><th><i class="fas fa-venus-mars me-2 text-danger"></i> Jenis Kelamin</th><td>:</td><td><?= $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                        <tr><th><i class="fas fa-calendar-day me-2 text-success"></i> Tanggal Masuk</th><td>:</td><td><?= date('d M Y', strtotime($siswa->tanggal_masuk_sekolah)) ?></td></tr>
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
                        <tr><th style="width: 25%;"><i class="fas fa-map-marked-alt me-2"></i> Alamat Lengkap</th><td><?= $siswa->alamat ?? '-' ?>, <?= $siswa->desa_kelurahan ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-city me-2"></i> Kecamatan/Kab/Prov</th><td><?= $siswa->kecamatan ?? '-' ?> / <?= $siswa->kabupaten ?? '-' ?> / <?= $siswa->provinsi ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-mobile-alt me-2"></i> Telepon Seluler</th><td><?= $siswa->telepon_seluler ?? $siswa->telepon_rumah ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-envelope me-2"></i> Email</th><td><?= $siswa->email ?? '-' ?></td></tr>
                    </table>
                </div>

                <div class="tab-pane fade" id="ortu-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th style="width: 25%;"><i class="fas fa-male me-2"></i> Nama Ayah</th><td><?= $siswa->nama_ayah ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Ayah</th><td><?= job($siswa->pekerjaan_ayah_id) ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-female me-2"></i> Nama Ibu</th><td><?= $siswa->nama_ibu ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Ibu</th><td><?= job($siswa->pekerjaan_ibu_id) ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-user-tie me-2"></i> Nama Wali</th><td><?= $siswa->nama_wali ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-briefcase me-2"></i> Pekerjaan Wali</th><td><?= job($siswa->pekerjaan_wali_id) ?? '-' ?></td></tr>
                    </table>
                </div>

                <div class="tab-pane fade" id="hobi-data" role="tabpanel">
                    <table class="table table-sm">
                        <tr><th style="width: 25%;"><i class="fas fa-puzzle-piece me-2"></i> Hobi</th><td><?= $siswa->hobi ?? '-' ?></td></tr>
                        <tr><th><i class="fas fa-plane-departure me-2"></i> Cita-cita</th><td><?= $siswa->cita_cita ?? '-' ?></td></tr>
                    </table>
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
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="globalEditModalLabel">
                    <i class="fas fa-edit"></i> Edit Data Siswa
                </h5>
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
    const CURRENT_SISWA_ID = <?php echo $siswa->nisn; ?>; 
    const modal = $('#globalEditModal');
    const modalTitle = $('#globalEditModalLabel');
    const formContainer = $('#dynamic-form-container');
    const loadingDiv = $('#modal-loading');

    $(document).on('click', '.btn-dynamic-edit', function() {
        const actionType = $(this).data('action');
        const title = $(this).data('title'); 
        const apiUrl = '/dapodik/get_form/' + actionType + '/' + CURRENT_SISWA_ID; 

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

        btn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'post', 
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil!', response.message, 'success').then(() => {
                        modal.modal('hide');
                        window.location.reload(); 
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
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