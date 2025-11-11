<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<h1 class="h3 mb-3 d-flex justify-content-between"><strong><?= $title; ?></strong>
    <button type="button" class="btn btn-primary btn-dynamic-edit"  data-bs-toggle="modal" data-bs-target="#uploadNilai" data-title="Upload Nilai">
        <i class="fas fa-edit"></i> Upload Nilai
    </button>
</h1>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-secondary text-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white"><i class="fas fa-user-circle me-2"></i> Daftar Nilai Siswa Kelas </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tingkat</th>
                            <th scope="col">Rombel</th>
                            <th scope="col">Wali Kelas</th>
                            <th scope="col">Lihat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // dd($rombel);
                            $i = 1;
                            foreach ($rombel as $rb):
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $rb->tingkat_pendidikan_id ?></td>
                            <td><?= $rb->nama ?></td>
                            <td><?= $rb->ptk_id_str ?></td>
                            <td class="text-center"><a href="<?= base_url('nilai_siswa/daftar_nilai/').$rb->rombongan_belajar_id ?>" title="Lihat Daftar Nilai" class="btn btn-success"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="uploadNilai" tabindex="-1" aria-labelledby="uploadNilaiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <form action="<?= base_url('nilai_siswa/upload') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="uploadNilaiLabel">
                        <i class="fas fa-file-excel"></i> Upload Nilai Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            
                <div class="modal-body">
                    <label for="file_excel" class="form-label">Pilih File Foto (Max 2MB)</label>
                    <input class="form-control" type="file" id="file" name="file_excel">
                </div>
                
                <div class="modal-footer" id="modal-footer-static">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#uploadNilai form').submit(function(e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr('action');
        
        const formData = new FormData(form[0]);

        Swal.fire({
            title: 'Konfirmasi Upload?',
            text: "Pastikan file Excel yang Anda unggah sudah benar dan sesuai format.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Proses Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                
                Swal.fire({
                    title: 'Mengunggah...',
                    text: 'Mohon tunggu, proses pembacaan file sedang berlangsung...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    processData: false, 
                    contentType: false,
                    dataType: 'json',
                    
                    success: function(response) {
                        $('#uploadNilai').modal('hide'); 

                        if (response.success) {
                            Swal.fire({
                                title: 'Upload Berhasil!',
                                html: response.message, 
                                icon: 'success'
                            });
                            setTimeout(() => { 
                                window.location.reload(); 
                            }, 1500); 

                        } else {
                            Swal.fire({
                                title: 'Gagal Upload!',
                                html: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#uploadNilai').modal('hide');
                        Swal.fire({
                            title: 'Error Jaringan!',
                            text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.',
                            icon: 'error'
                        });
                        console.error("AJAX Error:", status, error);
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
		