<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'nik' => 'required|string|max:20|unique:employees,nik,'.$employee?->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,'.$employee?->id,
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'address' => 'required|string',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:Aktif,Non-Aktif',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'birth_date.required' => 'Tanggal lahir wajib diisi',
            'birth_place.required' => 'Tempat lahir wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'department.required' => 'Departemen wajib diisi',
            'position.required' => 'Posisi wajib diisi',
            'join_date.required' => 'Tanggal masuk wajib diisi',
            'salary.required' => 'Gaji wajib diisi',
            'salary.numeric' => 'Gaji harus berupa angka',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg',
            'photo.max' => 'Ukuran gambar maksimal 2MB',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}
