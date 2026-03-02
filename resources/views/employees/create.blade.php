@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">
                <i class="bi bi-person-plus me-2"></i>Tambah Pegawai
            </h1>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img src="https://via.placeholder.com/150?text=Foto" 
                                     alt="Preview" 
                                     id="previewImage"
                                     class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <small class="text-muted">Format: jpg, png (max 2MB)</small>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" class="form-control" name="nik" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="phone" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3">Data Pribadi</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select select2" name="gender" required>
                                <option value="">Pilih...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="birth_place" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control datepicker" name="birth_date" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="address" rows="2" required></textarea>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-4">Data Kepegawaian</h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Departemen</label>
                            <select class="form-select select2" name="department" required>
                                <option value="">Pilih...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Posisi</label>
                            <select class="form-select select2" name="position" required>
                                <option value="">Pilih...</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos }}">{{ $pos }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select select2" name="status" required>
                                <option value="">Pilih...</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="text" class="form-control datepicker" name="join_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji (Rp)</label>
                            <input type="number" class="form-control" name="salary" min="0" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.datepicker').daterangepicker({
        locale: {
            format: 'DD MMMM YYYY',
            applyLabel: 'Pilih',
            cancelLabel: 'Batal',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        },
        singleDatePicker: true,
        showDropdowns: true,
        drops: 'down',
        opens: 'right'
    });

    $('#photo').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
    
    $('#employeeForm').validate({
        errorElement: 'div',
        errorClass: 'text-danger small',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endsection
