<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3 d-flex justify-content-between"><strong><?= $title; ?></strong>
    <button type="button" class="btn btn-primary btn-dynamic-edit"  data-bs-toggle="modal" data-bs-target="#uploadNilai" data-title="Upload Nilai">
        <i class="fas fa-edit"></i> Upload Nilai
    </button>
</h1>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-secondary text-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white"><i class="fas fa-user-circle me-2"></i> Daftar Nilai Siswa Kelas </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tingkat</th>
                            <th scope="col">Rombel</th>
                            <th scope="col">Wali Kelas</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // dd($rombel);
                            $i = 1;
                            foreach ($rombel as $rb):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $rb->tingkat_pendidikan_id ?></td>
                            <td><?= $rb->nama ?></td>
                            <td><?= $rb->ptk_id_str ?></td>
                            <td></td>
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


<div class="modal fade" id="uploadNilai" tabindex="-1" aria-labelledby="uploadNilaiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="uploadNilaiLabel">
                    <i class="fas fa-edit"></i> Edit Data Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                
            </div>
            
            <div class="modal-footer d-none" id="modal-footer-static">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                 </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>
		