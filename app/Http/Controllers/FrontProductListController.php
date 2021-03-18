<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Slider;

class FrontProductListController extends Controller
{
    public function index(){
    	$products =  Product::latest()->limit(9)->get();
    	$randomActiveProducts = Product::inRandomOrder()->limit(3)->get();
    	$randomActiveProductIds=[];
    	foreach($randomActiveProducts as $product){
    		array_push($randomActiveProductIds,$product->id);
    	}
    	$randomItemProducts = Product::whereNotIn('id',$randomActiveProductIds)->limit(3)->get();
       
        $sliders = Slider::get();
      
    	return view('product',compact('products','randomItemProducts','randomActiveProducts','sliders'));
    }
    public function show($id){
       $product = Product::find($id);
        $productFromSameCategories = Product::inRandomOrder()
            ->where('category_id',$product->category_id)
            ->where('id','!=',$product->id)
            ->limit(3)
            ->get();

        return view('show',compact('product','productFromSameCategories'));
    }

    public function allProduct($name,Request $request){
      
        $category  = Category::where('slug',$name)->first();
        $categoryId = $category->id;
        

        // lọc hiển thị sản phẩm theo danh mục sản phẩm
        if($request->subcategory){
            $products = $this->filterProducts($request);
            $filterSubCategories = $this->getSubcategoriesId($request);
         // lọc hiển thị sản phẩm theo giá
        }elseif($request->min||$request->max){
            $products = $this->filterByPrice($request);
         // cứ hiển thị sản phẩm thôi:
        }else{
            $products = Product::where('category_id',$category->id)->get();
        }
            $subcategories = Subcategory::where('category_id',$category->id)->get();
            $slug = $name;


        return view('category',compact('products','subcategories','slug','categoryId'));
    }
   // hiển thị lọc sản phẩm theo danh mục sản phẩm (category)
    public function filterProducts(Request $request){
        $subId =[];
        $subcategory = Subcategory::whereIn('id',$request->subcategory)->get();
        foreach($subcategory as $sub){
            array_push($subId, $sub->id);
        }
        $products = Product::whereIn('subcategory_id',$subId)->get();
        return $products;

    }
    // nhớ mục checkbox vừa chọn sau khi ấn filter để hiển thị các sản phẩm trong danh mục sp đó:
     public function getSubcategoriesId(Request $request){
        $subId =[];
        $subcategory = Subcategory::whereIn('id',$request->subcategory)->get();
        foreach($subcategory as $sub){
            array_push($subId, $sub->id);
        }
    
        return $subId;

    }
     public function filterByPrice(Request $request){
        $categoryId = $request->categoryId;
        $product = Product::whereBetween('price',[$request->min,$request->max])->where('category_id',$categoryId)->get();
        return $product;
    }
    public function moreProducts(Request $request){
        // tìm theo tên , theo description hoặc theo additional_info: từ form tìm kiếm
        if($request->search){
            $products = Product::where('name','like','%'.$request->search.'%')
            ->orWhere('description','like','%'.$request->search.'%')
            ->orWhere('additional_info','like','%'.$request->search.'%')

            ->paginate(50);
            return view('all-product',compact('products'));
        }

        $products  =Product::latest()->paginate(50);
        return view('all-product',compact('products'));
       
    }
}
