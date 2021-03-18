<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Subcategory extends Model
{
    protected $fillable = ['name','category_id'];
//subcategory thuộc về category
     public function category(){
    	return $this->belongsTo(Category::class);
    }
}
