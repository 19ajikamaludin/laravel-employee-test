<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        return view('employees.index');
    }

    public function create(): View
    {
        $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Operations', 'Sales', 'Legal', 'R&D'];
        $positions = ['Manager', 'Senior Staff', 'Staff', 'Junior Staff', 'Intern', 'Director', 'Head'];

        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time().'_'.$photo->getClientOriginalName();
            $photo->storeAs('photos', $filename);
            $data['photo'] = $filename;
        }

        Employee::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan',
            ]);
        }

        return redirect()->route('employees.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Employee $employee): View
    {
        $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Operations', 'Sales', 'Legal', 'R&D'];
        $positions = ['Manager', 'Senior Staff', 'Staff', 'Junior Staff', 'Intern', 'Director', 'Head'];

        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time().'_'.$photo->getClientOriginalName();
            $photo->storeAs('photos', $filename);
            $data['photo'] = $filename;

            if ($employee->photo) {
                Storage::delete('photos/'.$employee->photo);
            }
        }

        $employee->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ]);
        }

        return redirect()->route('employees.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::delete('photos/'.$employee->photo);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function datatable(Request $request): JsonResponse
    {
        $query = Employee::query();

        $searchValue = $request->input('search', '');
        if (! empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nik', 'like', "%{$searchValue}%")
                    ->orWhere('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhere('department', 'like', "%{$searchValue}%")
                    ->orWhere('position', 'like', "%{$searchValue}%");
            });
        }

        if ($request->has('department') && $request->department) {
            $query->where('department', $request->department);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        $total = Employee::count();
        $filteredCount = $query->count();

        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');

        $columns = ['id', 'nik', 'name', 'email', 'department', 'position', 'join_date', 'status'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        $query->orderBy($orderColumn, $orderDir);

        $employees = $query->skip($start)->take($length)->get();

        return response()->json([
            'draw' => $request->draw ?? 1,
            'recordsTotal' => $total,
            'recordsFiltered' => $filteredCount,
            'data' => $employees,
        ]);
    }
}
