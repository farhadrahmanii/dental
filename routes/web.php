<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DentalController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LanguageController;

// Frontend routes for dental practice
Route::get('/', [DentalController::class, 'home'])->name('home');
Route::get('/about', [DentalController::class, 'about'])->name('about');
Route::get('/dental-services', [DentalController::class, 'services'])->name('services');
Route::get('/contact', [DentalController::class, 'contact'])->name('contact');
Route::get('/patients', [DentalController::class, 'patients'])->name('patients');
Route::get('/patient/{id}', [DentalController::class, 'patientDetail'])->name('patient.detail');

// Financial System Routes
Route::prefix('financial')->name('financial.')->group(function () {
    Route::get('/dashboard', [FinancialController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [FinancialController::class, 'reports'])->name('reports');
    Route::get('/cash-flow', [FinancialController::class, 'cashFlow'])->name('cash-flow');
});

// Invoice Routes - Hidden for later implementation
// Route::resource('invoices', InvoiceController::class);
// Route::post('invoices/{invoice}/mark-sent', [InvoiceController::class, 'markAsSent'])->name('invoices.mark-sent');
// Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');

// Payment Routes
Route::resource('payments', PaymentController::class);
// Invoice-related API route hidden for later implementation
// Route::get('api/patient-invoices', [PaymentController::class, 'getPatientInvoices'])->name('api.patient-invoices');

// Service Routes
Route::resource('services', ServiceController::class);
Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');

// Appointment Routes
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// Language switching route
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('/api/appointments/time-slots', [AppointmentController::class, 'getAvailableTimeSlots'])->name('api.appointments.time-slots');
Route::get('/api/services', [AppointmentController::class, 'getServices'])->name('api.services');
Route::get('/api/appointments/calendar', [AppointmentController::class, 'calendar'])->name('api.appointments.calendar');

// Admin Appointment Management Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status');
});

// PWA Routes (commented out - controller not implemented)
// Route::get('/pwa', [PwaController::class, 'index'])->name('pwa');
