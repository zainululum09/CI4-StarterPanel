<form id="formUpdateAlamat" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="alamat" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="alamat_id" value="<?= $data_alamat->alamat_id ?? ''; ?>">

    <div class="row g-3 align-items-center">
        <div class="col-sm-3">
            <label for="alamat" class="form-label">Alamat</label>
        </div>
        <div class="col-sm-8"> 
            <input type="text" class="form-control" name="alamat" value="<?= $data_alamat->alamat; ?>" required>
        </div>
        
        <div class="col-sm-3">
            <label for="desa_kelurahan" class="form-label">Desa/Kelurahan</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="desa_kelurahan" value="<?= $data_alamat->desa_kelurahan; ?>" required>
        </div>
        
        <div class="col-sm-3">
            <label for="kecamatan" class="form-label">Kecamatan</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="kecamatan" value="<?= $data_alamat->kecamatan; ?>" required>
        </div>
        
        <div class="col-sm-3">
            <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="kabupaten" value="<?= $data_alamat->kabupaten; ?>" required>
        </div>
        
        <div class="col-sm-3">
            <label for="provinsi" class="form-label">Provinsi</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="provinsi" value="<?= $data_alamat->provinsi; ?>" required>
        </div>

              
    </div>

    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success" id="btnSaveDynamic">
            <i class="fas fa-save"></i> Simpan Alamat
        </button>
    </div>
</form>