<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h3 class="h3 d-flex justify-content-between text-light"><strong><i class="fa fa-grip-vertical"></i> <?= $title; ?></strong></h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="action">
                    <h4>action</h4>
                </div>
                <table class="table table-sm table-hover table-striped table-bordered">
                    <thead class="text-center align-middle text-uppercase">
                        <tr>
                            <th scope="col" class="py-3">#</th>
                            <th scope="col" style="width: 10%">Rombel</th>
                            <th scope="col" style="width: 20%">Jumlah Anggota Rombel</th>
                            <th scope="col">Wali Kelas</th>
                            <th scope="col">Lihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i = 1;
                            foreach ($rombel as $rb):
                        ?>
                        <tr data-id="<?= $rb->rombongan_belajar_id ?>">
                            <td class="text-center"><?= $i++ ?></td>
                            <td class="text-center"><?= $rb->nama ?></td>
                            <td class="text-center"><?= $rb->jumlah_per ?> Siswa</td>
                            <td><?= $rb->ptk_id_str ?></td>
                            <td class="text-center">
                                <a href="#" title="Lihat Anggota Rombel" role="button" class="btn btn-sm py-0 btn-info">
                                    <i class="fas fa-users"></i><small> Anggota Rombel</small>
                                </a>
                            </td>
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