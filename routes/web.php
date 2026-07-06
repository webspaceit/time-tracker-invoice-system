<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeEntryController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/settings/timezone', [DashboardController::class, 'updateTimezone'])->name('settings.timezone');
    Route::post('/settings/profile', [DashboardController::class, 'updateProfile'])->name('settings.profile');

    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'download'])->name('invoices.pdf');
    Route::resource('invoices', InvoiceController::class);
    Route::patch('/invoices/{invoice}/status/{status}', [InvoiceController::class, 'updateStatus'])
        ->name('invoices.update-status');

    Route::resource('customers', CustomerController::class);
    Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::resource('payments', PaymentController::class)->only(['index', 'destroy']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::match(['put', 'patch'], '/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Time Tracker
    Route::get('/time-tracker', [TimeEntryController::class, 'index'])->name('time-tracker.index');
    Route::get('/time-tracker/current', [TimeEntryController::class, 'current'])->name('time-tracker.current');
    Route::get('/time-tracker/today', [TimeEntryController::class, 'today'])->name('time-tracker.today');
    Route::post('/time-tracker/start', [TimeEntryController::class, 'start'])->name('time-tracker.start');
    Route::post('/time-tracker/stop', [TimeEntryController::class, 'stop'])->name('time-tracker.stop');
    Route::post('/time-tracker', [TimeEntryController::class, 'store'])->name('time-tracker.store');
    Route::match(['put', 'patch'], '/time-tracker/{timeEntry}', [TimeEntryController::class, 'update'])->name('time-tracker.update');
    Route::get('/time-tracker/summary', [TimeEntryController::class, 'summary'])->name('time-tracker.summary');
    Route::delete('/time-tracker/{timeEntry}', [TimeEntryController::class, 'destroy'])->name('time-tracker.destroy');
});
