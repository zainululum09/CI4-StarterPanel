<form id="formUpdateHobi" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="hobi" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="hobi_id" value="<?= $data_hobi->hobi_id ?? ''; ?>">
    
    <div class="row g-3 align-items-center">
        <div class="col-sm-3">
            <label for="hobi" class="form-label">Hobi</label>
        </div>
        <div class="col-sm-8">
            <select name="hobi" id="hobi" class="form-select">
                <option value="<?= $data_hobi->hobi ?? ''; ?>"><?= $data_hobi->hobi ?? '= Pilih Hobi ='; ?></option>
                <?= hobi(); ?>
            </select>
        </div>
        
        <div class="col-sm-3">
            <label for="cita_cita" class="form-label">Cita-cita</label>
        </div>
        <div class="col-sm-8">
            <select name="cita_cita" id="cita_cita" class="form-select">
                <option value="<?= $data_hobi->cita_cita ?? ''; ?>"><?= $data_hobi->cita_cita ?? '= Pilih Cita-cita ='; ?></option>
                <?= cita_cita(); ?>
            </select>
        </div>

    </div>
    
    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success" id="btnSaveDynamic">
            <i class="fas fa-save"></i> Simpan Hobi
        </button>
    </div>
</form>