<?php

use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;
use Foostart\Category\Helpers\FooCategory;
use Foostart\Category\Helpers\SortTable;

/*
|-----------------------------------------------------------------------
| GLOBAL VARIABLES
|-----------------------------------------------------------------------
|   $sidebar_items
|   $sorting
|   $order_by
|   $plang_admin = 'product-admin'
|   $plang_front = 'product-front'
*/
View::composer([
                'package-product::admin.product-edit',
                'package-product::admin.product-form',
                'package-product::admin.product-items',
                'package-product::admin.product-item',
                'package-product::admin.product-search',
                'package-product::admin.product-config',
                'package-product::admin.product-lang',
    ], function ($view) {

        //Order by params
        $params = Request::all();

        /**
         * $plang-admin
         * $plang-front
         */

        $plang_admin = 'product-admin';
        $plang_front = 'product-front';

        $fooCategory = new FooCategory();
        $key = $fooCategory->getContextKeyByRef('admin/products');

        /**
         * $sidebar_items
         */
        $sidebar_items = [
            trans('product-admin.sidebar.add') => [
                'url' => URL::route('products.edit', []),
                'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
            ],
            trans('product-admin.sidebar.list') => [
                "url" => URL::route('products.list', []),
                'icon' => '<i class="fa fa-list-ul" aria-hidden="true"></i>'
            ],
            trans('product-admin.sidebar.category') => [
                'url'  => URL::route('categories.list',['_key='.$key]),
                'icon' => '<i class="fa fa-sitemap" aria-hidden="true"></i>'
            ],
            trans('product-admin.sidebar.config') => [
                "url" => URL::route('products.config', []),
                'icon' => '<i class="fa fa-braille" aria-hidden="true"></i>'
            ],
            trans('product-admin.sidebar.lang') => [
                "url" => URL::route('products.lang', []),
                'icon' => '<i class="fa fa-language" aria-hidden="true"></i>'
            ],
        ];

        /**
         * $sorting
         * $order_by
         */
        $orders = [
            '' => trans($plang_admin.'.form.no-selected'),
            'id' => trans($plang_admin.'.fields.id'),
            'product_name' => trans($plang_admin.'.fields.name'),
            'product_status' => trans($plang_admin.'.fields.status'),
            'updated_at' => trans($plang_admin.'.fields.updated_at'),
        ];
        $sortTable = new SortTable();
        $sortTable->setOrders($orders);
        $sorting = $sortTable->linkOrders();



        //Order by
        $order_by = [
            'asc' => trans('category-admin.order.by-asc'),
            'desc' => trans('category-admin.order.by-des'),
        ];

        // assign to view
        $view->with('sidebar_items', $sidebar_items );
        $view->with('order_by', $order_by);
        $view->with('sorting', $sorting);
        $view->with('plang_admin', $plang_admin);
        $view->with('plang_front', $plang_front);
});
