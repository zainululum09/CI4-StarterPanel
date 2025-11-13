<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 d-flex justify-content-between text-white"><strong><i class="fas fa-users"></i> <?= $title;?></strong></h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-uppercase">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 25%;">Nama Guru</th>
                            <th style="width: 15%;">NIK</th>
                            <th style="width: 20%;">Tempat, Tanggal Lahir</th>
                            <th style="width: 15%;">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php if (!empty($ptk_data)): ?>
                            <?php foreach ($ptk_data as $ptk): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= esc($ptk->nama); ?></td>
                                    <td><?= esc($ptk->nik); ?></td>
                                    <td><?= esc($ptk->tempat_lahir . ', ' . tanggal_indo($ptk->tanggal_lahir)); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning me-1" 
                                            onclick="editPtk('<?= esc($ptk->ptk_id); ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                            onclick="deletePtk('<?= esc($ptk->ptk_id); ?>', '<?= esc($ptk->nama); ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Data PTK tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>