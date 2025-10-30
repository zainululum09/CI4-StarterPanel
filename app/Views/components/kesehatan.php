<form id="formUpdateKesehatan" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="kesehatan" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="kesehatan_id" value="<?= $data_kesehatan->kesehatan_id ?? ''; ?>">
    
    <div class="mb-3">
        <label for="tinggi_badan" class="form-label">Tinggi Badan (Cm)</label>
        <input type="number" class="form-control" name="tinggi_badan" value="<?= $data_kesehatan->tinggi_badan; ?>" required>
    </div>

    <div class="mb-3">
        <label for="tinggi_badan" class="form-label">Berat Badan (Kg)</label>
        <input type="number" class="form-control" name="berat_badan" value="<?= $data_kesehatan->berat_badan; ?>" required>
    </div>

    <div class="mb-3">
        <?php
            $kebutuhan_khusus_value = $data_kesehatan->kebutuhan_khusus ?? 'Tidak ada';
            $is_tidak_ada = (strtolower($kebutuhan_khusus_value) === 'tidak ada');
            $is_ada = !$is_tidak_ada;
        ?>
        <label class="form-label d-block">Kebutuhan Khusus</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kebutuhan_khusus" 
                id="kh_tidak" value="Tidak ada" <?= $is_tidak_ada ? 'checked' : '' ?>>
            <label class="form-check-label" for="kh_tidak">
                Tidak Ada
            </label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="kebutuhan_khusus" 
                id="kh_ada" value="Ada" <?= $is_ada ? 'checked' : '' ?>>
            <label class="form-check-label" for="kh_ada">
                Ada
            </label>
        </div>           
    </div>

    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success" id="btnSaveDynamic">
            <i class="fas fa-save"></i> Simpan Kesehatan
        </button>
    </div>
</form>