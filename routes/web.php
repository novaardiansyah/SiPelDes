<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/admin'));

Route::get('/testing/view-pdf', function () {
  // TODO:
});