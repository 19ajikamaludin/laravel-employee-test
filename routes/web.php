<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('employees.index');
});

Route::get('employees/datatable', [EmployeeController::class, 'datatable'])->name('employees.datatable');
Route::resource('employees', EmployeeController::class);
