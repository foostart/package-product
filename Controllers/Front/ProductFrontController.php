<?php

namespace Foostart\Product\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Foostart\Product\Models\Product;
use URL,
    Route,
    Redirect;
use Foostart\Sample\Models\Samples;

class ProductFrontController extends Controller
{
    public $data = array();
    public function __construct()
    {
        $this->obj_item = new Product(array('perPage' => 4));
    }

    public function index(Request $request)
    {
 
        // Get list Product
        $params = [];
        $items = $this->obj_item->selectItems($params);
        // echo '<pre>';
        // print_r($items->toArray());
        // die();
        $this->data = array(
            'request' => $request,
            'items' => $items
        );
        return view('package-product::front.product-items', $this->data);
    }
    
    public function edit(Request $request) {

        /**
         * Breadcrumb
         */

        $item = NULL;

        /**
         * Params
         */
        $params = $request->all();
        $params['id'] = $request->get('id', NULL);
        

        /**
         * Get current user and ignore admin
         */


        //get item data by id
        if (!empty($params['id'])) {

            $item = $this->obj_item->selectItem($params, FALSE);
            $this->data = array(
                'request' => $request,
                'item' => $item->toArray()
            );
            // echo '<pre>';
            // print_r($item->toArray());
            // die();
            if (empty($item)) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans('.actions.edit-error'));
            }
        }

        
        return view('package-product::front.product-item', $this->data);
    }

    public function searchProduct(Request $request) {
        //tim theo ki tu
        $item = Product::query();
        if ($request->has('keyword')) {
            $item->where('product_name', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('product_description', 'LIKE', '%' . $request->keyword . '%')
            ->orWhere('product_overview', 'LIKE', '%' . $request->keyword . '%');
        }
        $items =  $item->get();
        return view('package-product::front.product-items', ['items' => $items]);

    }

}
