<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     return view('welcome');
    //redirect to the admin dashboard
    //return redirect('/admin');
});
