<?php namespace Foostart\Product\Models;

use Foostart\Category\Library\Models\FooModel;
use Illuminate\Database\Eloquent\Model;
use Foostart\Comment\Models\Comment;

class Product extends FooModel {

    /**
     * @table categories
     * @param array $attributes
     */
    public $user = NULL;
    public function __construct(array $attributes = array()) {
        //set configurations
        $this->setConfigs();

        parent::__construct($attributes);

    }

    public function setConfigs() {

        //table name
        $this->table = 'products';

        //list of field in table
        $this->fillable = [
            'user_id',
            'product_name',
            'product_overview',
            'product_description',
            'product_image',
            'product_images',
            'product_price_root',
            'product_price',
            'product_price_sale',
            'product_status',
            'category_id',
        ];

        //list of fields for inserting
        $this->fields = [
            'product_name' => [
                'name' => 'product_name',
                'type' => 'Text',
            ],
      
            'category_id' => [
                'name' => 'category_id',
                'type' => 'Int',
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => 'Int',
            ],
   
    
            'product_overview' => [
                'name' => 'product_overview',
                'type' => 'Text',
            ],
            'product_description' => [
                'name' => 'product_description',
                'type' => 'Text',
            ],
            'product_image' => [
                'name' => 'product_image',
                'type' => 'Text',
            ],
            'product_images' => [
                'name' => 'product_images',
                'type' => 'Text',
            ],
            'product_price_root' => [
                'name' => 'product_price_root',
                'type' => 'Int',
            ],
            'product_price' => [
                'name' => 'product_price',
                'type' => 'Int',
            ],
            'product_price_sale' => [
                'name' => 'product_price_sale',
                'type' => 'Int',
            ],
            'product_status' => [
                'name' => 'status',
                'type' => 'Int',
            ],
        ];

        //check valid fields for inserting
        $this->valid_insert_fields = [

            'user_id',
            'product_name',
            'product_overview',
            'product_description',
            'product_image',
            'product_images',
            'product_price_root',
            'product_price',
            'product_price_sale',
            'product_status',
            'category_id',
        ];

        //check valid fields for ordering
        $this->valid_ordering_fields = [
            'product_name',
            'updated_at',
            $this->field_status,
        ];
        //check valid fields for filter
        $this->valid_filter_fields = [
            'keyword',
            'status',
            'category',
            '_id',
            'limit',
            'product_id!',
            'category_id',
            'user_id',
        ];

        //primary key
        $this->primaryKey = 'product_id';

        //the number of items on page
        $this->perPage = 10;

        //item status
        $this->field_status = 'product_status';

    }

    /**
     * Gest list of items
     * @param type $params
     * @return object list of categories
     */
    public function selectItems($params = array()) {

        //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo);

        //select fields
        $elo = $this->createSelect($elo);

        //order filters
        $elo = $this->orderingFilters($params, $elo);

        //paginate items
        if ($this->is_pagination) {
            $items = $this->paginateItems($params, $elo);
        } else {
            $items = $elo->get();
        }

        return $items;
    }

    /**
     * Get a product by {id}
     * @param ARRAY $params list of parameters
     * @return OBJECT product
     */
    public function selectItem($params = array(), $key = NULL) {


        if (empty($key)) {
            $key = $this->primaryKey;
        }
       //join to another tables
        $elo = $this->joinTable();

        //search filters
        $elo = $this->searchFilters($params, $elo, FALSE);

        //select fields
        $elo = $this->createSelect($elo);

        //id
        $elo = $elo->where($this->primaryKey, $params['id']);

        //first item
        $item = $elo->first();

        return $item;
    }


    public function getComments($product_id) {

        // Get product
        $params = array(
            'id' => $product_id,
        );
        $product = $this->selectItem($params);

        // Get comment by context
        $params = array(
            'context_name' => 'product',
            'context_id' => $product_id,
            'by_status' => true,
        );
        $obj_comment = new Comment();
        $obj_comment->user = $this->user;
        $comments = $obj_comment->selectItems($params);

        $users_comments = $obj_comment->mapCommentArray($comments);
        $product->cache_comments = json_encode($users_comments);
        $product->cache_time = time();
        $product->save();

        return $users_comments;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function joinTable(array $params = []){
        return $this;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    protected function searchFilters(array $params = [], $elo, $by_status = TRUE){

        //filter
        if ($this->isValidFilters($params) && (!empty($params)))
        {
            foreach($params as $column => $value)
            {
                if($this->isValidValue($value))
                {
                    switch($column)
                    {
                        case 'category_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.category_id', '=', $value);
                            }
                            break;
                        case 'category':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.category_id', '=', $value);
                            }
                            break;
                        case 'user_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.user_id', '=', $value);
                            }
                            break;
                        case 'limit':
                            if (!empty($value)) {
                                $this->perPage = $value;
                                $elo = $elo->limit($value);
                            }
                            break;
                        case '_id':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.product_id', '!=', $value);
                            }
                            break;
                        case 'status':
                            if (!empty($value)) {
                                $elo = $elo->where($this->table . '.'.$this->field_status, '=', $value);
                            }
                            break;
                        case 'keyword':
                            if (!empty($value)) {
                                $elo = $elo->where(function($elo) use ($value) {
                                    $elo->where($this->table . '.product_name', 'LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.product_description','LIKE', "%{$value}%")
                                    ->orWhere($this->table . '.product_overview','LIKE', "%{$value}%");
                                });
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        } elseif ($by_status) {

            $elo = $elo->where($this->table . '.'.$this->field_status, '=', $this->status['publish']);

        }

        return $elo;
    }

    /**
     * Select list of columns in table
     * @param ELOQUENT OBJECT
     * @return ELOQUENT OBJECT
     */
    public function createSelect($elo) {

        $elo = $elo->select($this->table . '.*',
                            $this->table . '.product_id as id'
                );

        return $elo;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @return ELOQUENT OBJECT
     */
    public function paginateItems(array $params = [], $elo) {
        $items = $elo->paginate($this->perPage);

        return $items;
    }

    /**
     *
     * @param ARRAY $params list of parameters
     * @param INT $id is primary key
     * @return type
     */
    public function updateItem($params = [], $id = NULL) {

        if (empty($id)) {
            $id = $params['id'];
        }
        $field_status = $this->field_status;

        //get product item by conditions
        $_params = [
            'id' => $id,
        ];
        $product = $this->selectItem($_params);

        if (!empty($product)) {
            $dataFields = $this->getDataFields($params, $this->fields);

            foreach ($dataFields as $key => $value) {
                $product->$key = $value;
            }

            $product->save();

            return $product;
        } else {
            return NULL;
        }
    }


    /**
     *
     * @param ARRAY $params list of parameters
     * @return OBJECT product
     */
    public function insertItem($params = []) {

        $dataFields = $this->getDataFields($params, $this->fields);
        $dataFields['product_images'] = $dataFields['product_image'];
        $dataFields['product_price_sale'] =100 - round(($dataFields['product_price'] / $dataFields['product_price_root']) * 100 );
        $dataFields[$this->field_status] = $this->status['publish'];
        $item = self::create($dataFields);
        $key = $this->primaryKey;
        $item->id = $item->$key;
        return $item;
    }


    /**
     *
     * @param ARRAY $input list of parameters
     * @return boolean TRUE incase delete successfully otherwise return FALSE
     */
    public function deleteItem($input = [], $delete_type) {

        $item = $this->find($input['id']);

        if ($item) {
            switch ($delete_type) {
                case 'delete-trash':
                    return $item->fdelete($item);
                    break;
                case 'delete-forever':
                    return $item->delete();
                    break;
            }

        }

        return FALSE;
    }

    public function getCoursesByCategoriesRoot($categories) {

        $this->is_pagination = false;

        if (!empty($categories)) {

            //get courses of category root
            $_params = [
                'limit' => 9,
                'category' => $categories->category_id,
                'is_pagination' => false
            ];
            $categories->courses = $this->selectItems($_params);

            //get courses of category childs
            foreach ($categories->childs as $key => $category) {
                $ids = [$category->category_id => 1];
                if (!empty($category->category_id_child_str)) {
                    $ids += (array)json_decode($category->category_id_child_str);;
                }
                $ids = array_keys($ids);

                //error
                $_temp = $categories->childs[$key];
                $_temp->courses = $this->getCouresByCategoryIds($ids);
            }


        }
        return $categories;
    }

    public function getCouresByCategoryIds($ids) {
        $courses = self::whereIn('category_id', $ids)
                    ->paginate($this->perPage);
        return $courses;
    }


    public function getItemsByCategories($categories) {

        $items = [];
        $ids = [];

        foreach ($categories as $category ) {
            $ids += [$category->category_id => 1];

            if (!empty($category->category_id_child_str)) {
                $ids += (array) json_decode($category->category_id_child_str);
            }
        }

        //Get list of items by ids
        $items = $this->getCouresByCategoryIds(array_keys($ids));

        return $items;
    }

    }