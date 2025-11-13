<form id="riwayatpendidikan" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="riwayatpendidikan" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">

    <div class="row g-3 align-items-center">

        <div class="col-sm-3">
            <label for="sekolah_asal" class="form-label">Sekolah Asal</label>
        </div>
        <div class="col-sm-8">
            <input type="text" name="sekolah_asal" class="form-control" id="sekolah_asal" <?= $data_siswa->sekolah_asal ? 'disabled' : '' ?> value="<?= $data_siswa->sekolah_asal ?? '' ?>">
        </div>

        <div class="col-sm-3">
            <label for="npsn" class="form-label">NPSN Sekolah Asal</label>
        </div>
        <div class="col-sm-8">
            <input type="text" name="npsn" class="form-control" id="npsn" value="<?= $data_siswa->npsn ?? '' ?>">
        </div>

        <div class="col-sm-3">
            <label for="alamat_sekolah_asal" class="form-label">Alamat</label>
        </div>
        <div class="col-sm-8">
            <textarea class="form-control" id="alamat_sekolah_asal" name="alamat_sekolah_asal" style="height: 80px"><?= $data_siswa->alamat_sekolah_asal ?? '' ?></textarea>
        </div>

        <div class="col-sm-3">
            <label for="nomor_ijazah" class="form-label">Nomor Ijazah</label>
        </div>
        <div class="col-sm-8">
            <input type="text" name="nomor_ijazah" class="form-control" id="nomor_ijazah" value="<?= $data_siswa->nomor_ijazah ?? '' ?>">
        </div>

        <div class="col-sm-3">
            <label for="tgl_ijazah" class="form-label">Tanggal Ijazah</label>
        </div>
        <div class="col-sm-8">
            <input type="date" name="tgl_ijazah" class="form-control" id="tgl_ijazah" value="<?= $data_siswa->tgl_ijazah ?? '' ?>">
        </div>

    </div>

    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type='submit' class='btn btn-success' id='btnSaveDynamic'>
            <i class='fas fa-save'></i> Simpan
        </button>   
    </div>
</form>