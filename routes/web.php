<?php

Route::get('/manifest.json', function () {
    return response(view('manifest'), headers: [
        'Content-Type' => 'application/json',
    ]);
})->name('manifest');