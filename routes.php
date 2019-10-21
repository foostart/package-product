<?php

Route::get('/hello',function(){
    echo "hello";
});

Route::group(['namespace'=>'Nhoma\Product\Controllers\Admin','prefix'=>'api'],function(){

    Route::get('/products','ProductController@index')->name('index');
    Route::get('/products/{id}','ProductController@showByID')->name('showByID');
    Route::post('/products/create','ProductController@post')->name('post');
    Route::post('/products/search','ProductController@search')->name('search');
    Route::put('/products/update','ProductController@post')->name('post');
    Route::delete('/products/delete','ProductController@delete')->name('delete');

    Route::post('/products/update-image', 'ImageController@update_image')->name('update_image');
});