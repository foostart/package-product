<?php

namespace Nhoma\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Category extends Model
{
    protected $table = 'product_categories';
    protected $primaryKey = 'id';

    public function getAllCategories()
    {
        return Product_Category::get();
    }
}
