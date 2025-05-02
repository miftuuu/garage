<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GradeController;
use App\Models\Grade;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // User Dashboard
    Route::get('/dashboard', [GradeController::class, 'dashboard'])->name('dashboard');

    // Grade Management
    Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
    Route::put('/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');

    // Calculator Page
    Route::get('/calculator', function () {
        $grades = Grade::all(); // fetch all grades
        $gpa = $grades->avg('grade'); // calculate GPA
        return view('calculator', compact('grades', 'gpa'));
    })->name('calculator');

    // Retake Page (Controller-based)
    Route::get('/retakes', [GradeController::class, 'retakes'])->name('grades.retakes');
    Route::get('/download-pdf', [GradeController::class, 'downloadPDF'])->name('grades.downloadPDF');


    // Approve Retake (Admin Action)
    Route::put('/grades/{id}/approve', [GradeController::class, 'approve'])->name('grades.approve');

    // Admin Dashboard
    Route::get('/admin/dashboard', [GradeController::class, 'adminDashboard'])->name('admin.dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
