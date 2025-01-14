<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

public function viewpage(){

    return view('product');
}


public function getProductDetails()
{
    return response()->json(Product::all());
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ],
    [
        'name.required' => 'Please provide a product name.',
        'price.required' => 'The price is a required field. Please set a price for the product.',
        'price.numeric' => 'The price must be a valid number.',
        'image.image' => 'The image must be a valid image file (JPG, JPEG, or PNG).',
        'image.mimes' => 'The image must be in JPG, JPEG, or PNG format.',
        'image.max' => 'The image size should not exceed 2MB.',
    ]
);

     $imagePath = null;

     if ($request->hasFile('image')) {
            $image = $request['image'];
            $destinationPath = 'images/product';
            $name =time() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $name);
            $imagePath = $destinationPath . '/' . $name;

        }

    $product = Product::create([
        'prd_name' => $request->name,
        'prd_description' => $request->description,
        'prd_price' => $request->price,
        'prd_image' => $imagePath,
       
    ]);

    return response()->json($product);
}

    public function productEdit($id){
        $product = Product::where('prd_id', $id)->firstOrFail();
        return response()->json($product);
    }



    public function productUpdate(Request $request, Product $product)
    {

      
        $request->validate([
            'prd_id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]
        ,
    [
        'name.required' => 'Please provide a product name.',
        'price.required' => 'The price is a required field. Please set a price for the product.',
        'price.numeric' => 'The price must be a valid number.',
        'image.mimes' => 'The image must be in JPG, JPEG, or PNG format.',
        'image.max' => 'The image size should not exceed 2MB.',
    ]
    );
    $editproduct =Product::where('prd_id', $request->prd_id)->first();
    $imagePath = $editproduct->image;

  
    
       
     if ($request->hasFile('image')) {
        if ($editproduct->image) {
            Storage::disk('public')->delete($editproduct->image);
        }
        $image = $request['image'];
        $destinationPath = 'images/product';
        $name =time() . '.' . $image->getClientOriginalExtension();
        $image->move($destinationPath, $name);
        $imagePath = $destinationPath . '/' . $name;
      

    }
    $product = array();
    $product['prd_image'] = $imagePath;
    $product['prd_name'] = $request->name;
    $product['prd_description'] = $request->description;
    $product['prd_price'] = $request->price;

         
 

        $data =Product::where('prd_id', $request->prd_id)->update($product);

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
       
        if ($product->prd_image) {
            Storage::disk('public')->delete($product->prd_image);
        }
    
       
        $product->delete();
    
        return response()->json(['message' => 'Product deleted successfully.']);
    }
    


}
