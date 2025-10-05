<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3"><strong><?= $title; ?></strong></h1>
<div class="container col-12 mt-5">
    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Daftar Peserta Didik Aktif</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIPD</th>
                            <th scope="col">NISN</th>
                            <th scope="col">Nama Siswa</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Kelas</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $datasiswa; ?>                    
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-muted">
            Total Siswa: <?= $total ?> Siswa
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
		