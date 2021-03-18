<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Subcategory;
class Category extends Model
{
    protected $fillable = ['name','slug','description','image'];

// 1 category có nhiều subcategory
    public function subcategory(){
    	return $this->hasMany(Subcategory::class);
    }
}
