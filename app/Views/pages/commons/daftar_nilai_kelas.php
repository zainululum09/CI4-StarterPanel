<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3 d-flex justify-content-between"><strong><?= $title; ?></strong></h1>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-secondary text-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white"><i class="fas fa-user-circle me-2"></i> Daftar Nilai Siswa Kelas </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table text-center align-middle">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Peserta Didik</th>
                            <th colspan="10">Mata Pelajaran</th>
                        </tr>
                        <tr>
                            <?php
                                foreach ($mapel as $mp):
                            ?>
                            <td><?= 'A' ?></td>
                            <?php
                                endforeach;
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                            foreach($rombel as $anggota):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= strtoupper($anggota->nama) ?></td>
                        </tr>
                        <?php
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>