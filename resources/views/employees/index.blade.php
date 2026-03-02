@extends('layouts.app')

@section('title', 'Daftar Pegawai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">
                <i class="bi bi-people me-2"></i>Daftar Pegawai
            </h1>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Tambah Pegawai
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <select id="departmentFilter" class="form-select">
                            <option value="">Semua Dept</option>
                            <option value="IT">IT</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operations">Operations</option>
                            <option value="Sales">Sales</option>
                            <option value="Legal">Legal</option>
                            <option value="R&D">R&D</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="genderFilter" class="form-select">
                            <option value="">Semua Gender</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="employeeTable" class="table table-hover table-striped" style="width: 100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Departemen</th>
                                <th>Posisi</th>
                                <th>Tanggal Masuk</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let employeeTable;

$(document).ready(function() {
    employeeTable = $('#employeeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employees.datatable") }}',
            data: function(d) {
                d.search = d.search.value;
                d.department = $('#departmentFilter').val();
                d.status = $('#statusFilter').val();
                d.gender = $('#genderFilter').val();
            }
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            { data: 'nik', name: 'nik' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { 
                data: 'join_date', 
                name: 'join_date',
                render: function(data) {
                    if (data) {
                        const date = new Date(data);
                        return date.toLocaleDateString('id-ID', { 
                            day: '2-digit', 
                            month: 'short', 
                            year: 'numeric' 
                        });
                    }
                    return '-';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    const statusClass = data === 'Aktif' ? 'status-active' : 'status-inactive';
                    return `<span class="status-badge ${statusClass}">${data}</span>`;
                }
            },
            {
                data: null,
                render: function(data) {
                    return `
                        <div class="d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-info text-white" onclick="viewEmployee(${data.id})" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="/employees/${data.id}/edit" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="deleteEmployee(${data.id})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[1, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    let searchTimeout = null;
    $('#searchFilter').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            employeeTable.search($('#searchFilter').val()).draw();
        }, 300);
    });

    $('#departmentFilter, #statusFilter, #genderFilter').on('change', function() {
        employeeTable.draw();
    });

    $('#resetFilter').on('click', function() {
        $('#searchFilter').val('');
        $('#departmentFilter').val('').trigger('change');
        $('#statusFilter').val('').trigger('change');
        $('#genderFilter').val('').trigger('change');
        employeeTable.search('').draw();
    });
});

function viewEmployee(id) {
    $.ajax({
        url: `/api/employees/${id}`,
        type: 'GET',
        success: function(response) {
            const emp = response;
            const photoUrl = emp.photo ? `/storage/photos/${emp.photo}` : 'https://via.placeholder.com/150?text=Foto';
            
            $('#viewContent').html(`
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img src="${photoUrl}" alt="${emp.name}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr><td width="35%" class="text-muted">NIK</td><td><strong>${emp.nik}</strong></td></tr>
                            <tr><td class="text-muted">Nama</td><td><strong>${emp.name}</strong></td></tr>
                            <tr><td class="text-muted">Email</td><td>${emp.email}</td></tr>
                            <tr><td class="text-muted">Telepon</td><td>${emp.phone}</td></tr>
                            <tr><td class="text-muted">Jenis Kelamin</td><td>${emp.gender}</td></tr>
                            <tr><td class="text-muted">Tempat, Tgl Lahir</td><td>${emp.birth_place}, ${new Date(emp.birth_date).toLocaleDateString('id-ID')}</td></tr>
                            <tr><td class="text-muted">Alamat</td><td>${emp.address}</td></tr>
                            <tr><td class="text-muted">Departemen</td><td>${emp.department}</td></tr>
                            <tr><td class="text-muted">Posisi</td><td>${emp.position}</td></tr>
                            <tr><td class="text-muted">Tanggal Masuk</td><td>${new Date(emp.join_date).toLocaleDateString('id-ID')}</td></tr>
                            <tr><td class="text-muted">Gaji</td><td>Rp ${parseFloat(emp.salary).toLocaleString('id-ID')}</td></tr>
                            <tr><td class="text-muted">Status</td><td><span class="badge ${emp.status === 'Aktif' ? 'bg-success' : 'bg-danger'}">${emp.status}</span></td></tr>
                        </table>
                    </div>
                </div>
            `);
            $('#viewModal').modal('show');
        },
        error: function() {
            alert('Gagal memuat data');
        }
    });
}

function deleteEmployee(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            url: `/employees/${id}`,
            type: 'DELETE',
            success: function(response) {
                alert(response.message);
                employeeTable.ajax.reload();
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });
    }
}
</script>
@endsection
