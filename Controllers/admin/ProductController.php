<?php namespace Nhoma\Product\Controllers\Admin;

/*
|-----------------------------------------------------------------------
| ProductAdminController
|-----------------------------------------------------------------------
| @author: Nhom A
| @date: 2/5/2019
|
*/


use Illuminate\Http\Request;
use Nhoma\Product\Models\Product;
use Nhoma\Product\Models\Image;
use Nhoma\Product\Transformers\ProductTransformer;
use Nhoma\Product\Models\Product_Category;
use URL, Route, Redirect;
use Illuminate\Support\Facades\App;

use Foostart\Category\Library\Controllers\FooController;
use Foostart\Category\Models\Category;
use Nhoma\Product\Validators\ProductValidator;


class ProductController extends FooController {

    public $obj_item = NULL;
    public $obj_category = NULL;
    protected $obj_pro_cate = NULL;

    public function __construct() {

        parent::__construct();
        // models
        $this->obj_item = new Product(array('perPage' => 10));
        $this->obj_category = new Category();
        $this->obj_pro_cate = new Product_Category();
        // validators
        $this->obj_validator = new ProductValidator();

        // set language files
        $this->plang_admin = 'product-admin';
        $this->plang_front = 'product-front';

        // package name
        $this->package_name = 'package-product';
        $this->package_base_name = 'product';

        // root routers
        $this->root_router = 'products';

        // page views
        $this->page_views = [
            'admin' => [
                'items' => $this->package_name.'::admin.'.$this->package_base_name.'-items',
                'edit'  => $this->package_name.'::admin.'.$this->package_base_name.'-edit',
                'config'  => $this->package_name.'::admin.'.$this->package_base_name.'-config',
                'lang'  => $this->package_name.'::admin.'.$this->package_base_name.'-lang',
            ]
        ];

        $this->data_view['status'] = $this->obj_item->getPluckStatus();

        // //set category
        $this->category_ref_name = 'admin/products';

    }

    /**
     * Show list of items
     * @return view list of items
     * @date 27/12/2017
     */
    public function index(Request $request) {

        $params = $request->all();
        $items = $this->obj_item->selectItems($params);
        $items = $items->toArray();

        $items = $this->transformProductArray($items);
        // display view
        $this->data_view = array_merge($this->data_view, array(
            'items' => $items,
            'request' => $request,
            'params' => $params,
        ));
        return response()->json(['status' => 200, 'List Product' => $items], 200);
//        return view($this->page_views['admin']['items'], $this->data_view);
    }

    public function search(Request $request) {

        $params = $request->all();
        $items = $this->obj_item->selectItems($params);
        // display view
        $this->data_view = array_merge($this->data_view, array(
            'items' => $items,
            'request' => $request,
            'params' => $params,
        ));
            return response()->json(['status' => 200, 'Product' => $items], 200);
        
//        return view($this->page_views['admin']['items'], $this->data_view);
    }
    /**
     * Show items by id
     */
    public function showByID(Request $request, $id) {

        $params = $request->all();
        $item = $this->obj_item->find($id);
        
        if(!empty($item)){

            $params['id'] = $id;
            $items = $this->obj_item->selectItem($params);
            $items = $items->toArray();
            // dd($items);
            $items = $this->transformProduct($items);        
    
            return response()->json(['status' => 200, 'Product' => $items], 200);
        }else{
            return response()->json(['message' => trans($this->plang_admin.'.actions.find-not'), 'status' => 401], 401);
        }
        
//        return view($this->page_views['admin']['items'], $this->data_view);
    }

    /**
     * Edit existing item by {id} parameters OR
     * Add new item
     * @return view edit page
     * @date 26/12/2017
     */
    public function edit(Request $request) {
        $item = NULL;
        $categories = NULL;

        $params = $request->all();
        $params['id'] = $request->get('id', NULL);

        $context = $this->obj_item->getContext($this->category_ref_name);

        if (!empty($params['id'])) {

            $item = $this->obj_item->selectItem($params, FALSE);

            if (empty($item)) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
            }
        }

        //get categories by context
        $context = $this->obj_item->getContext($this->category_ref_name);
        if ($context) {
            $params['context_id'] = $context->context_id;
            $categories = $this->obj_category->pluckSelect($params);
        }

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'item' => $item,
            'categories' => $categories,
            'request' => $request,
            'context' => $context,
        ));
        return view($this->page_views['admin']['edit'], $this->data_view);
    }

    /**
     * Processing data from POST method: add new item, edit existing item
     * @return view edit page
     * @date 27/12/2017
     */
    public function post(Request $request) {

        $item = NULL;
        //$params = array_merge($request->all(), $this->getUser());
        $params = array_merge($request->all());
        $is_valid_request = $this->isValidRequest($request);
        $id = (int) $request->get('id');
        if ($is_valid_request && $this->obj_validator->validate($params)) {// valid data

            // update existing item
            if (!empty($id)) {
                $item = $this->obj_item->find($id);
                
                if (!empty($item)) {

                    $params['id'] = $id;
                    $obj = $this->obj_pro_cate->getAllCategories();
                    $flag = 0;
                    foreach($obj as $ob)
                    {
                        if($ob['id'] == intval($request['cate_id']))
                        {
                            $flag = 1;
                        }
                    }
                    if($flag == 0)
                    {
                        return response()->json(['error' => trans($this->plang_admin.'.actions.find-not') . $request['cate_id'], 'status' => 400], 400);
                    }
                    $item = $this->obj_item->updateItem($params);
        
                    // message
                    //return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                    //                ->withMessage(trans($this->plang_admin.'.actions.edit-ok'));
                    return response()->json(['success' => trans($this->plang_admin.'.actions.edit-ok'), 'status' => 201], 201);
                } else {

                    // message
                    //return Redirect::route($this->root_router.'.list')
                    //               ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
                    return response()->json(['message' => trans($this->plang_admin.'.actions.find-not'), 'status' => 401], 401);
                }

            // add new item
            } else {
                $obj = $this->obj_pro_cate->getAllCategories();
                $flag = 0;
                foreach($obj as $ob)
                {
                    if($ob['id'] == intval($request['cate_id']))
                    {
                        $flag = 1;
                    }
                }
                if($flag == 0)
                {
                    return response()->json(['error' => trans($this->plang_admin.'.actions.find-not') . $request['cate_id'], 'status' => 400], 400);
                }

                $item = $this->obj_item->insertItem($params);

                if (!empty($item)) {

                    //message
                    //return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                    //                ->withMessage(trans($this->plang_admin.'.actions.add-ok'));
                
                    return response()->json(['success' => trans($this->plang_admin.'.actions.add-ok'), 'status' => 200], 200);
                } else {

                    //message
                    //return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                    //                ->withMessage(trans($this->plang_admin.'.actions.add-error'));
                    return response()->json(['message' => trans($this->plang_admin.'.actions.add-error'), 'status' => 400], 400);
                }

            }

        } else { // invalid data

            //$errors = $this->obj_validator->getErrors();

            // passing the id incase fails editing an already existing item
            //return Redirect::route($this->root_router.'.edit', $id ? ["id" => $id]: [])
            //        ->withInput()->withErrors($errors);
            return response()->json(['error' => $this->obj_validator->getErrors(), 'status' => 400], 400);
        }
    }

    /**
     * Delete existing item
     * @return view list of items
     * @date 27/12/2017
     */
    public function delete(Request $request) {

        $item = NULL;
        $flag = TRUE;
        //$params = array_merge($request->all(), $this->getUser());
        $params = array_merge($request->all());
        //$delete_type = isset($params['del-forever'])?'delete-forever':'delete-trash';
        $delete_type = 'delete-forever';
        $id = (int)$request->get('id');
        $ids = $request->get('ids');

        $is_valid_request = $this->isValidRequest($request);

        if ($is_valid_request && (!empty($id) || !empty($ids))) {

            $ids = !empty($id)?[$id]:$ids;

            foreach ($ids as $id) {

                $params['id'] = $id;

                if (!$this->obj_item->deleteItem($params, $delete_type)) {
                    $flag = FALSE;
                }
                
            }
            if ($flag) {
                //return Redirect::route($this->root_router.'.list')
                //                ->withMessage(trans($this->plang_admin.'.actions.delete-ok'));
                return response()->json(['success' => trans($this->plang_admin.'.actions.delete-ok'), 'status' => 204], 200);
            }
        }
        return response()->json(['message' => trans($this->plang_admin.'.actions.find-not'), 'status' => 404], 404);
        //return Redirect::route($this->root_router.'.list')
        //                ->withMessage(trans($this->plang_admin.'.actions.delete-error'));
    }

    /**
     * Manage configuration of package
     * @param Request $request
     * @return view config page
     */
    public function config(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $config_path = realpath(base_path('config/package-product.php'));
        $package_path = realpath(base_path('vendor/nhoma/package-product'));

        $config_bakup = realpath($package_path.'/storage/backup/config');

        if ($version = $request->get('v')) {
            //load backup config
            $content = file_get_contents(base64_decode($version));
        } else {
            //load current config
            $content = file_get_contents($config_path);
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            file_put_contents($config_bakup.'/package-product-'.date('YmdHis',time()).'.php', $content);

            //update new config
            $content = $request->get('content');

            file_put_contents($config_path, $content);
        }

        $backups = array_reverse(glob($config_bakup.'/*'));

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'content' => $content,
            'backups' => $backups,
        ));

        return view($this->page_views['admin']['config'], $this->data_view);
    }

    /**
     * Manage languages of package
     * @param Request $request
     * @return view lang page
     */
    public function lang(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $langs = config('package-product.langs');
        $lang_paths = [];

        if (!empty($langs) && is_array($langs)) {
            foreach ($langs as $key => $value) {
                $lang_paths[$key] = realpath(base_path('resources/lang/'.$key.'/product-admin.php'));
            }
        }

        $package_path = realpath(base_path('vendor/nhoma/package-product'));

        $lang_bakup = realpath($package_path.'/storage/backup/lang');
        $lang = $request->get('lang')?$request->get('lang'):'en';
        $lang_contents = [];

        if ($version = $request->get('v')) {
            //load backup lang
            $group_backups = base64_decode($version);
            $group_backups = empty($group_backups)?[]: explode(';', $group_backups);

            foreach ($group_backups as $group_backup) {
                $_backup = explode('=', $group_backup);
                $lang_contents[$_backup[0]] = file_get_contents($_backup[1]);
            }

        } else {
            //load current lang
            foreach ($lang_paths as $key => $lang_path) {
                $lang_contents[$key] = file_get_contents($lang_path);
            }
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            foreach ($lang_paths as $key => $value) {
                $content = file_get_contents($value);

                //format file name product-admin-YmdHis.php
                file_put_contents($lang_bakup.'/'.$key.'/product-admin-'.date('YmdHis',time()).'.php', $content);
            }


            //update new lang
            foreach ($langs as $key => $value) {
                $content = $request->get($key);
                file_put_contents($lang_paths[$key], $content);
            }

        }

        //get list of backup langs
        $backups = [];
        foreach ($langs as $key => $value) {
            $backups[$key] = array_reverse(glob($lang_bakup.'/'.$key.'/*'));
        }

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'backups' => $backups,
            'langs'   => $langs,
            'lang_contents' => $lang_contents,
            'lang' => $lang,
        ));

        return view($this->page_views['admin']['lang'], $this->data_view);
    }

    /**
     * Edit existing item by {id} parameters OR
     * Add new item
     * @return view edit page
     * @date 26/12/2017
     */
    public function copy(Request $request) {

        $params = $request->all();

        $item = NULL;
        $params['id'] = $request->get('cid', NULL);

        $context = $this->obj_item->getContext($this->category_ref_name);

        if (!empty($params['id'])) {

            $item = $this->obj_item->selectItem($params, FALSE);

            if (empty($item)) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
            }

            $item->id = NULL;
        }

        $categories = $this->obj_category->pluckSelect($params);

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'item' => $item,
            'categories' => $categories,
            'request' => $request,
            'context' => $context,
        ));

        return view($this->page_views['admin']['edit'], $this->data_view);
    }

    public function transformProduct($product){
        
                $new_product = [];
                $images = Image::where('product_id', $product['id'])->get();
              
                if(!empty($images->name)){
                
                    foreach ($images as $image) {
                        $product['image'][] = asset("uploads/" . $image->name);
                    }
                }else{
                    $product['image'] = [];
                }
                
                $new_product = [
                    'id'=>$product['id'],
                    'name'=>$product['name'],
                    'description'=>$product['description'],
                    'cate_name'=>$product['cate_name'],
                    'image'=>$product['image'],
                ];
        return $new_product;
    }
    public function transformProductArray($products)
    {
        $new_products = [];
       
        if ($products) {
            # code...
            foreach ($products['data'] as $product) {
                $product['image'] = [];
                $images = Image::where('product_id', $product['id'])->get();
            
                foreach ($images as $image) {     
                    // $product['image'] = asset("uploads/" . $image->name);
                    array_push($product['image'], asset("uploads/" . $image->name));
                }

                $new_product = [
                    'id'=>$product['id'],
                    'name'=>$product['name'],
                    'description'=>$product['description'],
                    'cate_name'=>$product['cate_name'],
                    'image'=>$product['image'],
                ];
                $new_products[] = $new_product;
            }
        }
       
        return $new_products;
    }

}