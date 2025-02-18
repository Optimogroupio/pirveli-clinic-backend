<?php

use App\Http\Controllers\Dashboard\DashboardAdminsController;
use App\Http\Controllers\Dashboard\DashboardAppointmentController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DashboardDoctorController;
use App\Http\Controllers\Dashboard\DashboardLanguageController;
use App\Http\Controllers\Dashboard\DashboardLocaleController;
use App\Http\Controllers\Dashboard\DashboardLoginController;
use App\Http\Controllers\Dashboard\DashboardNewsController;
use App\Http\Controllers\Dashboard\DashboardPageController;
use App\Http\Controllers\Dashboard\DashboardPermissionsController;
use App\Http\Controllers\Dashboard\DashboardRolesController;
use App\Http\Controllers\Dashboard\DashboardServiceCategoryController;
use App\Http\Controllers\Dashboard\DashboardServiceController;
use App\Http\Controllers\Dashboard\DashboardSettingsController;
use App\Http\Controllers\Dashboard\DashboardSliderController;
use App\Http\Controllers\Dashboard\DashboardSpecialtyController;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/','http://pirveliclinic.com');

Route::prefix('dashboard')->name('dashboard.')->group(function () {

    // Login and logout routes
    Route::get('/login', [DashboardLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DashboardLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [DashboardLoginController::class, 'logout'])->name('logout');

    Route::redirect('/', 'dashboard/home')->name('redirect');

    // Protected dashboard route
    Route::middleware('dashboard')->group(function () {
        Route::get('/home', [DashboardController::class, 'dashboard'])->name('home');

        // Administrators
        Route::resource('administrators', DashboardAdminsController::class)->except('show');

        // Roles
        Route::resource('roles', DashboardRolesController::class)->except('show');

        // Permissions
        Route::resource('permissions', DashboardPermissionsController::class)->except('show');

        // Locales
        Route::resource('locales', DashboardLocaleController::class)->except('show');

        // Service categories
        Route::resource('service_categories', DashboardServiceCategoryController::class)->except('show');

        // Services
        Route::resource('services', DashboardServiceController::class)->except('show');

        // Languages
        Route::resource('languages', DashboardLanguageController::class)->except('show');

        // Doctors
        Route::resource('doctors', DashboardDoctorController::class)->except('show');

        // Protected dashboard route
        Route::middleware('dashboard')->group(function () {
            Route::get('/home', [DashboardController::class, 'dashboard'])->name('home');

            // Administrators
            Route::resource('administrators', DashboardAdminsController::class)->except('show');

            // Roles
            Route::resource('roles', DashboardRolesController::class)->except('show');

            // Permissions
            Route::resource('permissions', DashboardPermissionsController::class)->except('show');

            // Locales
            Route::resource('locales', DashboardLocaleController::class)->except('show');

            // Service categories
            Route::resource('service_categories', DashboardServiceCategoryController::class)->except('show');

            // Services
            Route::resource('services', DashboardServiceController::class)->except('show');

            // Languages
            Route::resource('languages', DashboardLanguageController::class)->except('show');

            // Doctors and Doctor Details
            Route::resource('doctors', DashboardDoctorController::class)->except('show');

            // Specialty
            Route::resource('specialties', DashboardSpecialtyController::class)->except('show');

            // Appointmnets
            Route::resource('appointments', DashboardAppointmentController::class)->except('show');

            Route::prefix('doctors/{doctorId}')->name('doctors.')->group(function () {
                Route::prefix('doctor-details/{type}')
                    ->group(function () {
                        Route::get('/create', [DashboardDoctorController::class, 'createDoctorDetail'])->name('doctor_details.create');
                        Route::post('/', [DashboardDoctorController::class, 'storeDoctorDetail'])->name('doctor_details.store');
                        Route::get('/{id}/edit', [DashboardDoctorController::class, 'editDoctorDetail'])->name('doctor_details.edit');
                        Route::patch('/{id}', [DashboardDoctorController::class, 'updateDoctorDetail'])->name('doctor_details.update');
                        Route::delete('/{id}', [DashboardDoctorController::class, 'destroyDoctorDetail'])->name('doctor_details.destroy');
                    });
                Route::post('/update-detail-order', [DashboardDoctorController::class, 'updateDoctorDetailOrder'])->name('doctor_details.reorder');
                Route::delete('/delete-multiple-details', [DashboardDoctorController::class, 'deleteMultipleDoctorDetails'])->name('doctor_details.delete_multiple');
            });

            // Pages
            Route::resource('pages', DashboardPageController::class)->except('show');

            // News
            Route::resource('news', DashboardNewsController::class)->except('show');

            // Slider
            Route::resource('slider', DashboardSliderController::class)->except('show');
            Route::post('/slider/update-order', [DashboardSliderController::class, 'updateOrder'])->name('slider.reorder');

            // Settings
            Route::resource('settings', DashboardSettingsController::class)->except('show');

        });


    });


});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('dashboard');
});
