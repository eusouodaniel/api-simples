<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
	$api->group(['prefix' => 'auth'], function(Router $api) {
		$api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
		$api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

		$api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
		$api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
	});

	$api->group(['middleware' => 'jwt.auth'], function(Router $api) {
		$api->get('protected', function() {
			return response()->json([
				'message' => 'Acesso permitido apenas com token'
			]);
		});

		$api->get('refresh', [
			'middleware' => 'jwt.refresh',
			function() {
				return response()->json([
					'message' => 'Erro ao acessar URL'
				]);
			}
		]);

		$api->get('inscriptions', 'App\Api\V1\Controllers\InscriptionController@index');
		$api->get('user', 'App\Api\V1\Controllers\UserController@index');
		$api->get('inscriptions/{id}', 'App\Api\V1\Controllers\InscriptionController@show');
		$api->post('inscriptions', 'App\Api\V1\Controllers\InscriptionController@store');
		$api->put('inscriptions/{id}', 'App\Api\V1\Controllers\InscriptionController@update');
		$api->delete('inscriptions/{id}', 'App\Api\V1\Controllers\InscriptionController@destroy');

	});

	$api->get('hello', function() {
		return response()->json([
			'message' => 'Api simples'
		]);
	});
});