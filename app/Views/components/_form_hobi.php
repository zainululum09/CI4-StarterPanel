<form id="formUpdateHobi" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="hobi" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="hobi_id" value="<?= $data_hobi->hobi_id ?? ''; ?>">
    
    <div class="mb-3">
        <label for="hobi" class="form-label">Hobi</label>
        <input type="text" class="form-control" name="hobi" value="<?= $data_hobi->hobi; ?>" required>
    </div>

    <div class="mb-3">
        <label for="cita_cita" class="form-label">Cita-cita</label>
        <input type="text" class="form-control" name="cita_cita" value="<?= $data_hobi->cita_cita; ?>" required>
    </div>
    
    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success" id="btnSaveDynamic">
            <i class="fas fa-save"></i> Simpan Hobi
        </button>
    </div>
</form>