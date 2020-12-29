<?php

use Illuminate\Session\TokenMismatchException;

/**
 * FRONT
 */
Route::get('product', [
    'as' => 'product',
    'uses' => 'Foostart\Product\Controllers\Front\ProductFrontController@index'
]);


/**
 * ADMINISTRATOR
 */
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['admin_logged', 'can_see', 'in_context'],
                  'namespace' => 'Foostart\Product\Controllers\Admin',
        ], function () {

        /*
          |-----------------------------------------------------------------------
          | Manage product
          |-----------------------------------------------------------------------
          | 1. List of products
          | 2. Edit product
          | 3. Delete product
          | 4. Add new product
          | 5. Manage configurations
          | 6. Manage languages
          |
        */

        /**
         * list
         */
        Route::get('admin/products', [
            'as' => 'products.list',
            'uses' => 'ProductAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/products/edit', [
            'as' => 'products.edit',
            'uses' => 'ProductAdminController@edit'
        ]);

        /**
         * copy
         */
        Route::get('admin/products/copy', [
            'as' => 'products.copy',
            'uses' => 'ProductAdminController@copy'
        ]);

        /**
         * product
         */
        Route::post('admin/products/edit', [
            'as' => 'products.product',
            'uses' => 'ProductAdminController@post'
        ]);

        /**
         * delete
         */
        Route::get('admin/products/delete', [
            'as' => 'products.delete',
            'uses' => 'ProductAdminController@delete'
        ]);

        /**
         * trash
         */
         Route::get('admin/products/trash', [
            'as' => 'products.trash',
            'uses' => 'ProductAdminController@trash'
        ]);

        /**
         * configs
        */
        Route::get('admin/products/config', [
            'as' => 'products.config',
            'uses' => 'ProductAdminController@config'
        ]);

        Route::product('admin/products/config', [
            'as' => 'products.config',
            'uses' => 'ProductAdminController@config'
        ]);

        /**
         * language
        */
        Route::get('admin/products/lang', [
            'as' => 'products.lang',
            'uses' => 'ProductAdminController@lang'
        ]);

        Route::product('admin/products/lang', [
            'as' => 'products.lang',
            'uses' => 'ProductAdminController@lang'
        ]);

    });
});
