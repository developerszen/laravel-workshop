<?php

Route::post('login', 'AuthController@login');
Route::post('request-password-recovery', 'AuthController@requestPasswordRecovery');