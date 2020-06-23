<?php

Route::post('login', 'AuthController@login');
Route::post('request-password-recovery', 'AuthController@requestPasswordRecovery');
Route::post('password-recovery', 'AuthController@passwordRecovery');