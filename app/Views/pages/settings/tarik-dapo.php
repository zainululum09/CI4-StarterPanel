<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3"><strong><?= $title; ?></strong> </h1>

    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-database me-2"></i>
                    Penarikan Data dari Web Service Dapodik
                </h4>
                <p class="mb-0 mt-2 opacity-75">Kelola sinkronisasi data dari server Dapodik ke database lokal</p>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#configDapodikModal">
                        <i class="fas fa-sliders-h"></i> Atur Konfigurasi Dapodik
                    </button>
                    <button class="btn btn-warning" onclick="testConnection()">
                        <i class="fas fa-wifi me-2"></i>
                        <span class="btn-text">Test Koneksi Dapodik</span>
                        <span class="loading" style="display: none;"> <span class="spinner-border spinner-border-sm me-1"></span>
                            Testing...
                        </span>
                    </button>
                    <div class="badge bg-secondary fs-6" id="connection-status">
                        <i class="fas fa-question-circle me-1"></i>
                        Status: Belum Ditest
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Jenis Data</th>
                                <th style="width: 40%">Jumlah Data</th>
                                <th style="width: 35%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-type="sekolah">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-school text-primary me-3"></i>
                                        <div>
                                            <strong>Data Sekolah</strong>
                                            <br>
                                            <small class="text-muted">Informasi sekolah dan profil</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-sekolah">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-sekolah">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-sekolah"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('sekolah')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('sekolah')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>

                            <tr data-type="ptk">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-success me-3"></i>
                                        <div>
                                            <strong>Data PTK</strong>
                                            <br>
                                            <small class="text-muted">Pendidik dan Tenaga Kependidikan</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-ptk">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-ptk">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-ptk"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('ptk')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('ptk')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>

                            <tr data-type="peserta_didik">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-graduation-cap text-warning me-3"></i>
                                        <div>
                                            <strong>Data Peserta Didik</strong>
                                            <br>
                                            <small class="text-muted">Siswa dan informasi akademik</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-peserta_didik">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-peserta_didik">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-peserta_didik"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('peserta_didik')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('peserta_didik')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>

                            <tr data-type="rombongan_belajar">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher text-danger me-3"></i>
                                        <div>
                                            <strong>Data Rombongan Belajar</strong>
                                            <br>
                                            <small class="text-muted">Kelas dan Walikelas</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-rombongan_belajar">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-rombongan_belajar">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-rombongan_belajar"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('rombongan_belajar')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('rombongan_belajar')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            <tr data-type="anggota_rombongan_belajar">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chalkboard-teacher text-info me-3"></i>
                                        <div>
                                            <strong>Data Anggota Rombongan Belajar</strong>
                                            <br>
                                            <small class="text-muted">Anggota Kelas</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-anggota_rombongan_belajar">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-anggota_rombongan_belajar">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-anggota_rombongan_belajar"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('anggota_rombongan_belajar')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('anggota_rombongan_belajar')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info">
                    <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                    <ul class="mb-0 small">
                        <li>Klik <strong>Test Koneksi</strong> untuk memastikan koneksi ke web service Dapodik</li>
                        <li>Klik <strong>Cek Data</strong> untuk melihat jumlah data yang tersedia di web service Dapodik</li>
                        <li>Klik <strong>Simpan Data</strong> untuk menyinkronkan data ke database MySQL lokal</li>
                        <li>Progress bar akan menampilkan kemajuan proses penyimpanan data</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="configDapodikModal" tabindex="-1" aria-labelledby="configDapodikModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="configDapodikModalLabel">
                        <i class="fas fa-cogs"></i> Konfigurasi Tarik Data Dapodik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="formDapodikConfig" action="<?= base_url('tarik-dapo/config/save') ?>" method="POST">
                    <input type="hidden" id="config_id" name="id" value="">
                    <div class="modal-body">
                        
                        <div class="row mb-3 align-items-center">
                            <label for="nama_config" class="col-sm-4 col-form-label">Nama Konfigurasi</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama_config" name="nama_config" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="api_url" class="col-sm-4 col-form-label">IP Dapodik Server</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="api_url" name="api_url" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="api_token" class="col-sm-4 col-form-label">Token/Kunci API</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="api_token" name="api_token" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="npsn" class="col-sm-4 col-form-label">NPSN</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="npsn" name="npsn" required>
                            </div>
                        </div>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success" id="btnSaveConfig">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Base URL untuk API - sesuaikan dengan konfigurasi Anda
        const baseUrl = '<?= base_url('tarik-dapo'); ?>'; // Menggunakan base_url() helper CodeIgniter 4
        
        // Fungsi untuk mengaktifkan status loading pada tombol
        function startLoading(button) {
            button.prop('disabled', true);
            button.find('.btn-text').hide();
            button.find('.loading').show();
        }

        // Fungsi untuk menonaktifkan status loading pada tombol
        function stopLoading(button) {
            button.prop('disabled', false);
            button.find('.btn-text').show();
            button.find('.loading').hide();
        }

        function testConnection() {
            const button = $('.btn-warning');
            
            // Show loading state
            startLoading(button);
            
            // Update status badge
            $('#connection-status').removeClass('bg-success bg-danger bg-secondary')
                                        .addClass('bg-warning')
                                        .html('<i class="fas fa-spinner fa-spin me-1"></i>Testing...');
            
            $.ajax({
                url: `${baseUrl}/test-connection`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update status badge to success
                        $('#connection-status').removeClass('bg-warning bg-danger bg-secondary')
                                                    .addClass('bg-success')
                                                    .html('<i class="fas fa-check-circle me-1"></i>Terhubung');
                        
                        // Show success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Koneksi Berhasil!',
                            html: `
                                <div class="text-start">
                                    <p><strong>Status:</strong> Terhubung ke server Dapodik</p>
                                    <p><strong>Server Info:</strong> ${response.server_info || 'N/A'}</p>
                                    <p><strong>IP Server Dapodik:</strong> ${response.ip || 'N/A'}</p>
                                    <p><strong>IP Local:</strong> ${response.my_ip || 'N/A'}</p>
                                    <p><strong>Response Time:</strong> ${response.response_time || 'N/A'}ms</p>
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745'
                        });
                    } else {
                        // Update status badge to error
                        $('#connection-status').removeClass('bg-warning bg-success bg-secondary')
                                                    .addClass('bg-danger')
                                                    .html('<i class="fas fa-times-circle me-1"></i>Gagal');
                        
                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Gagal!',
                            html: `
                                <div class="text-start">
                                    <p><strong>Error:</strong> ${response.message}</p>
                                    <p class="text-muted small">Pastikan web service Dapodik sudah berjalan dan konfigurasi URL sudah benar.</p>
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Update status badge to error
                    $('#connection-status').removeClass('bg-warning bg-success bg-secondary')
                                            .addClass('bg-danger')
                                            .html('<i class="fas fa-times-circle me-1"></i>Error');
                    
                    // Show error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        html: `
                            <div class="text-start">
                                <p><strong>Error:</strong> ${error}</p>
                                <p><strong>Status:</strong> ${status}</p>
                                <p class="text-muted small">Periksa koneksi internet dan konfigurasi server.</p>
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                    
                    console.error('Connection test error:', error);
                },
                complete: function() {
                    // Hide loading state
                    stopLoading(button);
                }
            });
        }
        
        function checkData(type) {
            const button = $(`tr[data-type="${type}"] .btn-success`);
            const saveButton = $(`tr[data-type="${type}"] .btn-primary`);
            
            // Show loading state
            startLoading(button);
            
            $.ajax({
                url: `${baseUrl}/checkData/${type}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(`#count-${type}`).text(response.count);
                        saveButton.prop('disabled', false);
                        
                        // Show modern success notification
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Berhasil Dicek!',
                            text: `Ditemukan ${response.count} record data ${type}`,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengecek Data',
                            text: response.message || 'Terjadi kesalahan saat mengecek data',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal terhubung ke server',
                        confirmButtonColor: '#dc3545'
                    });
                    console.error('Error:', error);
                },
                complete: function() {
                    // Hide loading state
                    stopLoading(button);
                }
            });
        }
        
        function saveData(type) {
            const button = $(`tr[data-type="${type}"] .btn-primary`);
            const progressBar = $(`#progress-${type}`);
            const progressText = $(`#progress-text-${type}`);
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Konfirmasi Penyimpanan',
                text: `Apakah Anda yakin ingin menyimpan data ${type} ke database?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    startLoading(button);
                    
                    // Show progress bar
                    progressBar.show();
                    progressBar.find('.progress-bar').css('width', '0%');
                    progressText.text('Memulai proses sinkronisasi...');
                    
                    // Start save process
                    $.ajax({
                        url: `${baseUrl}/saveData/${type}`,
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                console.log(response.message)
                                // Simulate progress updates (since actual real-time progress is complex with standard AJAX)
                                let progress = 0;
                                const totalExpectedTime = 3000; // 3 seconds simulation
                                const intervalTime = 200;
                                const steps = totalExpectedTime / intervalTime;
                                let currentStep = 0;

                                const interval = setInterval(function() {
                                    currentStep++;
                                    progress = Math.min(100, (currentStep / steps) * 100);

                                    if (progress >= 100) {
                                        clearInterval(interval);
                                        
                                        progressBar.find('.progress-bar').css('width', '100%');
                                        progressText.text(`Selesai: ${response.saved} data berhasil disimpan`);
                                        
                                        // Show success notification
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Data Berhasil Disimpan!',
                                            html: `
                                                <div class="text-center">
                                                    <h5 class="text-success">${response.saved} Record</h5>
                                                    <p>Data ${type} berhasil disinkronkan ke database</p>
                                                </div>
                                            `,
                                            confirmButtonColor: '#28a745'
                                        });
                                        
                                        // Hide progress after 3 seconds
                                        setTimeout(function() {
                                            progressBar.hide();
                                            progressText.text('');
                                        }, 3000);
                                    } else {
                                        progressBar.find('.progress-bar').css('width', progress + '%');
                                        progressText.text(`Menyimpan... ${Math.round(progress)}%`);
                                    }
                                }, intervalTime);
                            } else {
                                progressBar.hide();
                                progressText.text('');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menyimpan Data',
                                    text: response.message || 'Terjadi kesalahan saat menyimpan data',
                                    confirmButtonColor: '#dc3545'
                                });
                                console.log(response.message);                                
                            }
                        },
                        error: function(xhr, status, error) {
                            progressBar.hide();
                            progressText.text('');
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyimpan data ke database',
                                confirmButtonColor: '#dc3545'
                            });
                            console.error('Error:', error);
                        },
                        complete: function() {
                            // Hide loading state
                            stopLoading(button);
                            // Note: Progress bar remains visible until success timeout
                        }
                    });
                }
            });
        }

        // Tangkap event ketika modal Konfigurasi Dapodik selesai ditampilkan
        $('#configDapodikModal').on('shown.bs.modal', function () {
            
            const apiUrl =`${baseUrl}/config`; // Ganti dengan URL endpoint getConfig() Anda

            // 1. Bersihkan formulir terlebih dahulu
            $('#formDapodikConfig')[0].reset();
            
            // 2. Panggil API untuk mengambil data konfigurasi
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    // Tampilkan loading spinner jika perlu
                    console.log("Mengambil data konfigurasi...");
                },
                success: function(response) {
                    if (response.success && response.data) {
                        const config = response.data;
                        
                        // 3. Isi field formulir dengan data yang diterima
                        // Pastikan ID field di modal Anda sesuai dengan key data (nama_config, ip_dapodik, token_dapodik, npsn)
                        $('#config_id').val(config.id);
                        $('#nama_config').val(config.nama_config);
                        $('#api_url').val(config.api_url);
                        $('#api_token').val(config.api_token);
                        $('#npsn').val(config.npsn);
                        
                        console.log("Data konfigurasi berhasil dimuat.");
                        
                    } else if (response.success && !response.data) {
                        // Jika success=true tapi data kosong (misal, belum ada config tersimpan)
                        console.log("Belum ada konfigurasi yang tersimpan. Biarkan formulir kosong.");
                    } else {
                        // Jika success=false (terjadi exception di Controller)
                        alert('Gagal memuat data konfigurasi: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Kesalahan AJAX:", status, error);
                    alert("Terjadi kesalahan saat menghubungi server.");
                }
            });
        });

        // Tangani event pengiriman formulir
        $('#formDapodikConfig').submit(function(e) {
            e.preventDefault(); 
            
            const form = $(this);
            const btn = $('#btnSaveConfig');
            const originalText = btn.html();

            // Tampilkan loading state pada tombol
            btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        
                        // 1. Tampilkan SweetAlert (Swal) Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message, // Menggunakan pesan dari Controller
                            showConfirmButton: false,
                            timer: 2000 // Notifikasi akan hilang setelah 2 detik
                        }).then(() => {
                            // 2. Sembunyikan modal dan Reload halaman
                            $('#configDapodikModal').modal('hide');
                            window.location.reload(); 
                        });
                        
                    } else {
                        // Tampilkan SweetAlert untuk kegagalan (misalnya validasi gagal)
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan!',
                            text: response.message,
                            footer: 'Periksa kembali data yang Anda masukkan.'
                        });
                        console.error('Validation/System Errors:', response.errors);
                    }
                },
                error: function(xhr, status, error) {
                    // Tangani kesalahan koneksi (misalnya HTTP 500, 404)
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan!',
                        text: 'Terjadi kesalahan koneksi saat menyimpan. Silakan coba lagi.'
                    });
                    console.error("AJAX Error:", xhr.responseText);
                },
                complete: function() {
                    // Kembalikan tombol ke keadaan semula setelah operasi selesai
                    btn.html(originalText).prop('disabled', false);
                    // Catatan: Jika success, tombol ini akan di-reload, jadi ini hanya untuk kasus error.
                }
            });
        });
    </script>
<?= $this->endSection(); ?>