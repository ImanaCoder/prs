<?php
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\TeamController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\manager\ClientController;
use App\Http\Controllers\manager\DealController;
use App\Http\Controllers\manager\PaymentController;
use App\Http\Controllers\manager\SalesManagerController;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TempImageController;
use App\Http\Controllers\verifier\VerifierController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest middleware for unauthenticated users
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    Artisan::call('db:seed');

    $output = Artisan::output();
    return $output;


});

Route::get('/verify-otp', [OTPVerificationController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/verify-otp', [OTPVerificationController::class, 'verify'])->name('otp.verify.submit');

Route::post('/login-process', [LoginController::class, 'authenticate'])->name('prs.login');

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
Route::group(['middleware' => ['auth', 'role:admin',"verified"]], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'dashboardv1'])->name('admin.dashboard');
        Route::get('/dashboardV2', [AdminController::class, 'dashboardv2'])->name('admin.dashboardv2');


        Route::delete('/payments/{id}', [PaymentController::class,'destroy'])->name('payments.destroy');
        Route::delete('/deals/{id}', [DealController::class,'destroy'])->name('deals.destroy');
        Route::get('/deals', [DealController::class,'getDealsForAdmin'])->name('admin.deals');
        Route::put('/deals/reapprove/{id}', [DealController::class,'reapprove'])->name('deals.reapprove');


        //Deal routes
        Route::get('/teams', [TeamController::class,'index'])->name('teams.index');
        Route::post('/teams/store', [TeamController::class,'store'])->name('teams.store');
        Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/teams/{id}', [TeamController::class,'update'])->name('teams.update');

        //User routes
        Route::get('/users', [UserController::class,'index'])->name('users.index');
        Route::post('/users/store', [UserController::class,'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/users/{teamId}', [UserController::class, 'getUsersByTeamId'])->name('users.teamId');



    });
});

// Verifier routes
Route::group(['middleware' => ['auth', 'role:verifier',"verified"]], function () {
    Route::group(['prefix' => 'verifier'], function () {
        Route::get('/payments', [PaymentController::class,'index'])->name('payments.index');
        Route::get('/dashboard', [DealController::class, 'getDealsForVerifier'])->name('verifier.dashboard');
        Route::put('/payments/verify/{id}', [PaymentController::class,'verify'])->name('payments.verify');


    });
});

Route::group(['middleware' => ['auth', 'role:sales_manager|verifier|admin',"verified"]], function () {
    Route::get('/payments/{id}/edit', [PaymentController::class,'edit'])->name('payments.edit');

});

Route::group(['middleware' => ['auth',"verified"]], function () {
    Route::get('/payment-details/{id}', [PaymentController::class,'paymentDetails'])->name('payments.details');

});



// Sales Manager routes
Route::group(['middleware' => ['auth', 'role:sales_manager',"verified"]], function () {
    Route::group(['prefix' => 'sales-manager'], function () {
        Route::get('/dashboard', [SalesManagerController::class, 'index'])->name('manager.dashboard');



        //Client routes
        Route::get('/clients', [ClientController::class,'index'])->name('clients.index');
        Route::post('/clients/store', [ClientController::class,'store'])->name('clients.store');
        Route::get('/clients/{id}/edit', [ClientController::class,'edit'])->name('clients.edit');
        Route::put('/clients/{id}', [ClientController::class,'update'])->name('clients.update');



        //Deal routes
        Route::get('/deals', [DealController::class,'index'])->name('deals.index');
        Route::post('/deals/store', [DealController::class,'store'])->name('deals.store');
        Route::get('/deals/{id}/edit', [DealController::class, 'edit'])->name('deals.edit');
        Route::put('/deals/{id}', [DealController::class,'update'])->name('deals.update');


        //Payment routes
        Route::post('/payments/store', [PaymentController::class,'store'])->name('payments.store');
        Route::put('/payments/{id}', [PaymentController::class,'update'])->name('payments.update');
    });
});
//Temp Images Create
Route::post('/upload-temp-image', [TempImageController::class,'create'])->name('temp-images.create');
Route::delete('/delete-temp-image', [TempImageController::class,'delete'])->name('temp-images.delete');
require __DIR__.'/auth.php';
