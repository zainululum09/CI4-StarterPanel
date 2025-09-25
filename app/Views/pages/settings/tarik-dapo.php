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
                    <button class="btn btn-warning" onclick="testConnection()">
                        <i class="fas fa-wifi me-2"></i>
                        <span class="btn-text">Test Koneksi Dapodik</span>
                        <span class="loading">
                            <span class="spinner-border spinner-border-sm me-1"></span>
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
                                        <span class="loading">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('sekolah')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading">
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
                                        <span class="loading">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('ptk')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading">
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
                                        <span class="loading">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('peserta_didik')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            <tr data-type="pengguna">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-cog text-info me-3"></i>
                                        <div>
                                            <strong>Data Pengguna</strong>
                                            <br>
                                            <small class="text-muted">User dan hak akses sistem</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="data-info">
                                        <span class="fw-bold text-dark" id="count-pengguna">-</span>
                                        <span class="text-muted">data tersedia</span>
                                        <div class="progress mt-2" style="display: none;" id="progress-pengguna">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="progress-text-pengguna"></small>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm me-2" onclick="checkData('pengguna')">
                                        <i class="fas fa-search me-1"></i>
                                        <span class="btn-text">Cek Data</span>
                                        <span class="loading">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Mengecek...
                                        </span>
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="saveData('pengguna')" disabled>
                                        <i class="fas fa-download me-1"></i>
                                        <span class="btn-text">Simpan Data</span>
                                        <span class="loading">
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

    <script>
        // Base URL untuk API - sesuaikan dengan konfigurasi Anda
        const baseUrl = '/tarik-dapo'; // Ganti dengan base URL controller Anda
        
        function testConnection() {
            const button = $('.btn-warning');
            
            // Show loading state
            button.prop('disabled', true);
            button.find('.btn-text').hide();
            button.find('.loading').show();
            
            // Update status badge
            $('#connection-status').removeClass('bg-success bg-danger bg-secondary')
                                  .addClass('bg-warning')
                                  .html('<i class="fas fa-spinner fa-spin me-1"></i>Testing...');
            
            $.ajax({
                url: `${baseUrl}/testConnection`,
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
                                    <p><strong>URL:</strong> ${response.url || 'N/A'}</p>
                                    <p><strong>Response Time:</strong> ${response.response_time || 'N/A'}ms</p>
                                    <p><strong>Server Info:</strong> ${response.server_info || 'N/A'}</p>
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
                    button.prop('disabled', false);
                    button.find('.btn-text').show();
                    button.find('.loading').hide();
                }
            });
        }
        
        function checkData(type) {
            const button = $(`tr[data-type="${type}"] .btn-success`);
            const saveButton = $(`tr[data-type="${type}"] .btn-primary`);
            
            // Show loading state
            button.prop('disabled', true);
            button.find('.btn-text').hide();
            button.find('.loading').show();
            
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
                    button.prop('disabled', false);
                    button.find('.btn-text').show();
                    button.find('.loading').hide();
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
                    button.prop('disabled', true);
                    button.find('.btn-text').hide();
                    button.find('.loading').show();
                    
                    // Show progress bar
                    progressBar.show();
                    progressBar.find('.progress-bar').css('width', '0%');
                    
                    // Start save process
                    $.ajax({
                        url: `${baseUrl}/saveData/${type}`,
                        method: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                // Simulate progress updates
                                let progress = 0;
                                const interval = setInterval(function() {
                                    progress += Math.random() * 20;
                                    if (progress >= 100) {
                                        progress = 100;
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
                                }, 200);
                            } else {
                                progressBar.hide();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menyimpan Data',
                                    text: response.message || 'Terjadi kesalahan saat menyimpan data',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            progressBar.hide();
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
                            button.prop('disabled', false);
                            button.find('.btn-text').show();
                            button.find('.loading').hide();
                        }
                    });
                }
            });
        }
    </script>
<?= $this->endSection(); ?>
		