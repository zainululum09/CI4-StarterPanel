<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h3 class="h3 d-flex justify-content-between text-light"><strong><i class="fa fa-grip-vertical"></i> <?= $title; ?></strong></h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped table-bordered">
                    <thead class="table text-center align-middle bg-secondary text-light">
                        <tr>
                            <th scope="col" class="py-3">#</th>
                            <th scope="col">Rombel</th>
                            <th scope="col">Jumlah Peserta Didik</th>
                            <th scope="col">Wali Kelas</th>
                            <th scope="col">Lihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach ($rombel as $rb):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $rb->nama ?></td>
                            <td><?= $rb->jumlah_per ?> Siswa</td>
                            <td><?= $rb->ptk_id_str ?></td>
                            <td class="text-center"><a href="#" title="Lihat Daftar Nilai" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a></td>
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