<?php

namespace Tulparstudyo;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->configureRateLimiting();
		$this->routes(function () {
			Route::middleware('api')
			     ->prefix('auth')
			     ->group(base_path('routes.auth.php'));
		});


		Route::get('/auth', [BrandController::class, 'index']);
		Route::group(['middleware' => ['auth:sanctum']], function(){
			Route::post('/brand', [BrandController::class, 'post']);
			Route::get('/brand/{id}', [BrandController::class, 'get']);
			Route::put('/brand/{id}', [BrandController::class, 'put']);
			Route::delete('/brand/{id}', [BrandController::class, 'delete']);
		});
	}
	protected function configureRateLimiting()
	{
		RateLimiter::for('api', function (Request $request) {
			return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
		});
	}

}
