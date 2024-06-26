<?php
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\manager\ClientController;
use App\Http\Controllers\manager\DealController;
use App\Http\Controllers\manager\PaymentController;
use App\Http\Controllers\manager\SalesManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TempImageController;
use App\Http\Controllers\verifier\VerifierController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest middleware for unauthenticated users
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

// Authenticated middleware for authenticated users
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Default route for authenticated users
    Route::get('/dashboard', function () {
        $user = User::findOrFail(Auth::id());

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('verifier')) {
            return redirect()->route('verifier.dashboard');
        } elseif ($user->hasRole('sales_manager')) {
            return redirect()->route('manager.dashboard');
        } else {
            return redirect()->route('profile.edit');
        }
    })->name('dashboard');
});

// Admin routes
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'dashboardv1'])->name('admin.dashboard');
        Route::delete('/payments/{id}', [PaymentController::class,'destroy'])->name('payments.destroy');
        Route::delete('/deals/{id}', [DealController::class,'destroy'])->name('deals.destroy');

    });
});

// Verifier routes
Route::group(['middleware' => ['auth', 'role:verifier']], function () {
    Route::group(['prefix' => 'verifier'], function () {
        Route::get('/payments', [PaymentController::class,'index'])->name('payments.index');
        Route::get('/dashboard', [DealController::class, 'getDealsForVerifier'])->name('verifier.dashboard');
        Route::put('/payments/verify/{id}', [PaymentController::class,'verify'])->name('payments.verify');

    });
});

Route::group(['middleware' => ['auth', 'role:sales_manager|verifier']], function () {
    Route::get('/payments/{id}/edit', [PaymentController::class,'edit'])->name('payments.edit');

});


// Sales Manager routes
Route::group(['middleware' => ['auth', 'role:sales_manager']], function () {
    Route::group(['prefix' => 'sales-manager'], function () {
        Route::get('/dashboard', [SalesManagerController::class, 'index'])->name('manager.dashboard');



        //Client routes
        Route::get('/clients', [ClientController::class,'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class,'create'])->name('clients.create');
        Route::post('/clients/store', [ClientController::class,'store'])->name('clients.store');
        Route::get('/clients/{id}/edit', [ClientController::class,'edit'])->name('clients.edit');
        Route::put('/clients/{id}', [ClientController::class,'update'])->name('clients.update');



        //Deal routes
        Route::get('/deals', [DealController::class,'index'])->name('deals.index');
        Route::get('/deals/create', [DealController::class,'create'])->name('deals.create');
        Route::post('/deals/store', [DealController::class,'store'])->name('deals.store');
        Route::get('/deals/{id}/edit', [DealController::class, 'edit'])->name('deals.edit');

        Route::put('/deals/{id}', [DealController::class,'update'])->name('deals.update');


        //Payment routes
        Route::get('/payments/create', [PaymentController::class,'create'])->name('payments.create');
        Route::post('/payments/store', [PaymentController::class,'store'])->name('payments.store');
        Route::put('/payments/{id}', [PaymentController::class,'update'])->name('payments.update');
    });
});
//Temp Images Create
Route::post('/upload-temp-image', [TempImageController::class,'create'])->name('temp-images.create');
Route::delete('/delete-temp-image', [TempImageController::class,'delete'])->name('temp-images.delete');
require __DIR__.'/auth.php';
