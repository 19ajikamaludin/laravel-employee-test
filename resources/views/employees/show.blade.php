@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')
<div class="row">
    <div class="col-md-4 text-center mb-4">
        <img src="{{ $employee->photo ? asset('storage/photos/'.$employee->photo) : 'https://via.placeholder.com/150?text=Foto' }}" 
             alt="{{ $employee->name }}" 
             class="img-fluid rounded-circle" 
             style="max-width: 150px;">
    </div>
    <div class="col-md-8">
        <table class="table table-borderless">
            <tr>
                <td width="35%" class="text-muted">NIK</td>
                <td><strong>{{ $employee->nik }}</strong></td>
            </tr>
            <tr>
                <td class="text-muted">Nama</td>
                <td><strong>{{ $employee->name }}</strong></td>
            </tr>
            <tr>
                <td class="text-muted">Email</td>
                <td>{{ $employee->email }}</td>
            </tr>
            <tr>
                <td class="text-muted">Telepon</td>
                <td>{{ $employee->phone }}</td>
            </tr>
            <tr>
                <td class="text-muted">Jenis Kelamin</td>
                <td>{{ $employee->gender }}</td>
            </tr>
            <tr>
                <td class="text-muted">Tempat, Tanggal Lahir</td>
                <td>{{ $employee->birth_place }}, {{ \Carbon\Carbon::parse($employee->birth_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Alamat</td>
                <td>{{ $employee->address }}</td>
            </tr>
            <tr>
                <td class="text-muted">Departemen</td>
                <td>{{ $employee->department }}</td>
            </tr>
            <tr>
                <td class="text-muted">Posisi</td>
                <td>{{ $employee->position }}</td>
            </tr>
            <tr>
                <td class="text-muted">Tanggal Masuk</td>
                <td>{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Gaji</td>
                <td>Rp {{ number_format($employee->salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td>
                    <span class="badge {{ $employee->status === 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                        {{ $employee->status }}
                    </span>
                </td>
            </tr>
        </table>
        
        <div class="mt-3">
            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection
