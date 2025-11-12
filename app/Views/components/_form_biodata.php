<form id="formUpdateOrangtua" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="biodata" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">

    <div class="row g-3 align-items-center">
        <div class="col-sm-4">
            <div class="card shadow-sm">
                <div class="card-body text-center" id="previewContainer">
                    <img id="imagePreview" src="<?= 'uploads/foto_siswa'.$data_siswa->foto ?? 'placeholder.png' ?>" alt="Pratinjau Foto" 
                         style="max-width: 100%; max-height: 150px; border: 1px solid #ccc; display: block; margin: 0 auto;">
                    <p class="text-muted mt-2">Pratinjau akan muncul setelah Anda memilih file.</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label for="foto" class="form-label">Pilih File Foto (Max 2MB)</label>
                    <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nama" type="text" class="form-control" name="nama" value="<?= $data_siswa->nama ?? ''; ?>">
        </div>

        <div class="col-sm-3">
            <label for="nik" class="form-label">NIK</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nik" type="text" class="form-control" name="nik" value="<?= $data_siswa->nik ?? ''; ?>">
        </div>

        <div class="col-sm-3">
            <label for="nisn" class="form-label">NISN</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nisn" type="text" class="form-control" name="nisn" value="<?= $data_siswa->nisn ?? ''; ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="nipd" class="form-label">NIPD</label>
        </div>
        <div class="col-sm-8"> 
            <input id="nipd" type="text" class="form-control" name="nipd" value="<?= $data_siswa->nipd ?? ''; ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tempat_lahir" type="text" class="form-control" name="tempat_lahir" value="<?= $data_siswa->tempat_lahir ?? ''; ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        </div>
        <div class="col-sm-8"> 
            <input id="tanggal_lahir" type="date" class="form-control" name="tanggal_lahir" value="<?= $data_siswa->tanggal_lahir ?? ''; ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
        </div>
        <div class="col-sm-8"> 
            <input id="l" type="radio" class="form-check-input" name="jenis_kelamin" value="L" <?= $data_siswa->jenis_kelamin=="L" ? 'checked' :'' ?> > Laki-laki
            <input id="p" type="radio" class="form-check-input" name="jenis_kelamin" value="P" <?= $data_siswa->jenis_kelamin=="P" ? 'checked' :'' ?> > Perempuan
        </div>
        
        <div class="col-sm-3">
            <label for="tinggal_bersama" class="form-label">Tinggal Bersama</label>
        </div>
        <div class="col-sm-8"> 
            <select name="tinggal_bersama" class="form-select" id="tinggal_bersama">
                <option value="<?= $data_siswa->tinggal_bersama ?? '' ?>" selected><?= $data_siswa->tinggal_bersama ?? '= Tinggal Bersama ='?></option>
                <?= tinggal_bersama(); ?>
            </select>
        </div>
        
        <div class="col-sm-3">
            <label for="jenis_transportasi" class="form-label">Moda Transportasi</label>
        </div>
        <div class="col-sm-8"> 
            <select name="jenis_transportasi" class="form-select" id="jenis_transportasi">
                <option value="<?= $data_siswa->jenis_transportasi ?? '' ?>" selected><?= $data_siswa->jenis_transportasi ?? '= Jenis Transportasi ='?></option>
                <?= jenis_transportasi(); ?>
            </select>
        </div>

        <div class="col-sm-3">
            <label for="anak_keberapa" class="form-label">Anak ke-</label>
        </div>
        <div class="col-sm-8"> 
            <input id="anak_keberapa" type="number" class="form-control" name="anak_keberapa" min="1" value="<?= $data_siswa->anak_keberapa ?? ''; ?>">
        </div>

        <div class="col-sm-3">
            <label for="jumlah_saudara" class="form-label">Jumlah Saudara</label>
        </div>
        <div class="col-sm-8"> 
            <input id="jumlah_saudara" type="number" class="form-control" name="jumlah_saudara" min="1" value="<?= $data_siswa->jumlah_saudara ?? ''; ?>">
        </div>
        
    </div>
        
    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type='submit' class='btn btn-success' id='btnSaveDynamic'>
            <i class='fas fa-save'></i> Simpan
        </button>   
    </div>
</form>

<script>
    $(document).ready(function(){
        const $fileInput = $('#foto');
        const $imagePreview = $('#imagePreview');
        const $previewMessage = $('#previewContainer p.text-muted');
        
        // 💡 GANTI INI DENGAN URL CDN PLACEHOLDER
        const defaultPlaceholderSrc = 'https://placehold.co/150x150/EAEAEA/333333?text=NO+IMAGE';

        // Fungsi untuk mereset preview ke placeholder CDN
        function resetImagePreview() {
            $imagePreview.attr('src', defaultPlaceholderSrc);
            $previewMessage.removeClass('d-none'); // Tampilkan pesan
        }

        // Panggil sekali saat halaman dimuat
        resetImagePreview();

        $fileInput.on('change', function() {
            if (this.files && this.files.length > 0 && this.files[0]) {
                const file = this.files[0];
                
                if (file.type.match('image.*')) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        $imagePreview.attr('src', e.target.result);
                        $previewMessage.addClass('d-none'); // Sembunyikan pesan
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert("File yang Anda pilih bukan format gambar yang valid.");
                    $fileInput.val(''); 
                    resetImagePreview(); // Reset ke placeholder CDN
                }
            } else {
                // Jika tidak ada file yang dipilih (dibatalkan/direset)
                resetImagePreview();
            }
        });
    })
</script>