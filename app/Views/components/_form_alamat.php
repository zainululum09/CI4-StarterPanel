<form id="formUpdateAlamat" action="<?= base_url('dapodik/update_data'); ?>" method="post">

    <input type="hidden" name="type" value="alamat" id="type">
    <input type="hidden" name="peserta_didik_id" value="<?= $peserta_didik_id ?? ''; ?>">
    <input type="hidden" name="alamat_id" value="<?= $data_alamat->alamat_id ?? ''; ?>">

    <div class="row g-3 align-items-center">
        <div class="col-sm-3">
            <label for="alamat" class="form-label">Alamat</label>
        </div>
        <div class="col-sm-8"> 
            <input id="alamat" type="text" class="form-control" name="alamat" value="<?= $data_alamat->alamat; ?>" required>
        </div>

        <div class="col-sm-3">
            <label for="provinsi_code" class="form-label">Provinsi</label>
        </div>
        <div class="col-sm-8">
            <select class="form-select" name="provinsi_code" id="provinsi_code">
                <option value="<?= $data_alamat->provinsi_code ?? '' ?>" selected><?= $data_alamat->provinsi ?? '= Pilih Provinsi ='?></option>
            </select>
            <input type="hidden" name="provinsi" id="provinsi" value="<?= $data_alamat->provinsi ??'' ?>">
        </div>
        
        <div class="col-sm-3">
            <label for="kabupaten_code" class="form-label">Kabupaten/Kota</label>
        </div>
        <div class="col-sm-8">
            <select class="form-select" name="kabupaten_code" id="kabupaten_code">
                <option value="<?= $data_alamat->kabupaten_code ?? '' ?>" selected><?= $data_alamat->kabupaten ?? '= Pilih Kabupaten ='?></option>
            </select>
            <input type="hidden" name="kabupaten" id="kabupaten" value="<?= $data_alamat->kabupaten ??'' ?>">
        </div>
            
        <div class="col-sm-3">
            <label for="kecamatan_code" class="form-label">Kecamatan</label>
        </div>
        <div class="col-sm-8">
            <select class="form-select" name="kecamatan_code" id="kecamatan_code">
                <option value="<?= $data_alamat->kecamatan_code ?? '' ?>" selected><?= $data_alamat->kecamatan ?? '= Pilih Kecamatan ='?></option>
            </select>
            <input type="hidden" name="kecamatan" id="kecamatan" value="<?= $data_alamat->kecamatan ??'' ?>">
        </div>
                
        <div class="col-sm-3">
            <label for="desa_code" class="form-label">Desa/Kelurahan</label>
        </div>
        <div class="col-sm-8">
            <select class="form-select" name="desa_code" id="desa_code">
                <option value="<?= $data_alamat->desa_code ?? '' ?>" selected><?= $data_alamat->desa_kelurahan ?? '= Pilih Desa/Kelurahan ='?></option>
            </select>
            <input type="hidden" name="desa_kelurahan" id="desa_kelurahan" value="<?= $data_alamat->desa_kelurahan ??'' ?>">
        </div>                
        
        <div class="col-sm-3">
            <label for="kode_pos" class="form-label">Kode Pos</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="kode_pos" id="kode_pos" value="<?= $data_alamat->kode_pos??'' ?>">
        </div>                
        
        <div class="col-sm-3">
            <label for="telepon_seluler" class="form-label">Nomor Telepon Selular</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="telepon_seluler" id="telepon_seluler" value="<?= $data_alamat->telepon_seluler ?? '' ?>">
        </div>                
        
        <div class="col-sm-3">
            <label for="telepon_rumah" class="form-label">Nomor Telepon Rumah</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="telepon_rumah" id="telepon_rumah" value="<?= $data_alamat->telepon_rumah ?? '' ?>">
        </div>                
        
        <div class="col-sm-3">
            <label for="email" class="form-label">Email Aktif</label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="form-control" name="email" id="email" value="<?= $data_alamat->email ?? '' ?>">
        </div>                
    </div>

    <div class="modal-footer p-0 mt-3 border-top-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type='submit' class='btn btn-success' id='btnSaveDynamic'>
            <i class='fas fa-save'></i> Simpan Alamat
        </button>";
    
    </div>
</form>

<script>
    $(document).ready(function(){

        $('#provinsi_code').on('click', function()
        {
            $.ajax({
                url: '/dapodik/getProvinces',
                method: 'GET',
                dataType: 'json',
                success: function(response){
                    const dataProvinsi = response.data;
                    
                    $.each(dataProvinsi, function(index, prov){
                        const optionHtml = `<option value="${prov.code}">${prov.name}</option>`;
                        $('#provinsi_code').append(optionHtml);
                    })
                }
            })
        })

        $('#provinsi_code').on('change', function(){
        const provCode = $(this).val();
        console.log(provCode)
        $.ajax({
            url: '/dapodik/getRegencies/'+provCode,
            method: 'GET',
            dataType: 'json',
            success: function(response){
                const dataKabupaten = response.data;
                $('#kabupaten_code').empty(); 
                $('#kabupaten_code').append('<option>= Pilih Kabupaten =</option>');
                
                $.each(dataKabupaten, function(index, kab){
                        const optionHtml = `<option value="${kab.code}">${kab.name}</option>`;
                        $('#kabupaten_code').append(optionHtml);
                    })
                }
            })
        })

        $('#kabupaten_code').on('change', function(){
        const kabCode = $(this).val();

        $.ajax({
            url: '/dapodik/getDistricts/'+kabCode,
            method: 'GET',
            dataType: 'json',
            success: function(response){
                const dataKecamatan = response.data;
                $('#kecamatan_code').empty(); 
                $('#kecamatan_code').append('<option>= Pilih Kecamatan =</option>');
                
                $.each(dataKecamatan, function(index, kec){
                        const optionHtml = `<option value="${kec.code}">${kec.name}</option>`;
                        $('#kecamatan_code').append(optionHtml);
                    })
                }
            })
        })

        $('#kecamatan_code').on('change', function(){
        const disCode = $(this).val();

        $.ajax({
            url: '/dapodik/getVillages/'+disCode,
            method: 'GET',
            dataType: 'json',
            success: function(response){
                const datadesa = response.data;
                $('#desa_code').empty();
                $('#desa_code').append('<option>= Pilih Desa =</option>');
                
                $.each(datadesa, function(index, desa){
                        const optionHtml = `<option value="${desa.code}">${desa.name}</option>`;
                        $('#desa_code').append(optionHtml);
                    })
                }
            })
        })

        function getSelectedText(selectId) {
            const $selectElement = $(selectId);
            
            return $selectElement.find('option:selected').text();
        }

        $('#desa_code').on('change', function() {
            const provinsiText = getSelectedText('#provinsi_code');
            const kabupatenText = getSelectedText('#kabupaten_code');
            const kecamatanText = getSelectedText('#kecamatan_code');
            const desaText = getSelectedText('#desa_code');            

            $('#provinsi').val(provinsiText);
            $('#kabupaten').val(kabupatenText);
            $('#kecamatan').val(kecamatanText);
            $('#desa_kelurahan').val(desaText);
            
        });

        $('#provinsi').val();
        $('#kabupaten').val();
        $('#kecamatan').val();
        $('#desa_kelurahan').val();
    })
</script>