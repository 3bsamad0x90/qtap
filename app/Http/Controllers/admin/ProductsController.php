<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\StoreProductRequest;
use App\Models\Products;
use Illuminate\Http\Request;
use File;
use Response;
use Termwind\Components\Dd;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::paginate(25);
        return view('AdminPanel.products.index',[
            'active' => 'products',
            'title' => 'المنتجات',
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => 'المنتجات'
                ]
            ]
        ],compact('products'));
    }
    public function store(StoreProductRequest $request){
        $product = Products::create($request->validated());
        if($request->hasFile('image')){
            $product['image'] = upload_image_without_resize('products/'.$product->id , $request->image );
             $product->update();
        }
        $images = [];
        if($files = $request->file('images')){
            foreach($files as $image){
                $imageData = upload_image_without_resize('products/'.$product->id , $image );
                $images[] = $imageData;
            }
            $product['images'] = json_encode($images);
            $product->update();
        }
        if($product){
            return redirect()->route('admin.products')
                            ->with('success','تم حفظ البيانات بنجاح');
        }else{
            return redirect()->back()
                            ->with('failed','لم نستطع حفظ البيانات');
        }
    }
    public function update(Request $request, Products $product){
      $request->validate([
          'title_ar' => 'required',
          'title_en' => 'required',
          'description_ar' => 'required|string|max:255',
          'description_en' => 'required|string|max:255',
          'price' => 'required|numeric',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
          'video' => 'nullable',
      ],[
          'title_ar.required' => 'الإسم بالعربية مطلوب',
          'title_en.required' => 'الإسم بالانجليزية مطلوب',
          'description_ar.required' => 'الوصف بالعربية مطلوب',
          'description_en.required' => 'الوصف بالانجليزية مطلوب',
          'price.required' => 'السعر مطلوب',
          'price.numeric' => 'السعر يجب ان يكون رقم',
          'image.image' => 'الصورة يجب ان تكون صورة',
          'image.mimes' => 'الصورة يجب ان تكون من نوع jpeg,png,jpg,gif',
      ]);
        $product->update($request->except('_token','image'));
        if($request->hasFile('image')){
            if($product->image != '' && file_exists('uploads/products/'.$product->id.'/'.$product->image)){
                unlink('uploads/products/'.$product->id.'/'.$product->image);
            }
            $product['image'] = upload_image_without_resize('products/'.$product->id , $request->image );
            $product->update();
        }
        if($product){
            return redirect()->route('admin.products')
                            ->with('success','تم تعديل البيانات بنجاح');
        }else{
            return redirect()->back()
                            ->with('failed','لم نستطع تعديل البيانات');
        }
    }
    public function updateImages(Request $request, Products $product){

        $request->validate(
          [
            'images.*' => 'mimes:png,jpg,jpeg',
          ],
          [
            'images.mimes' => 'يجب ان تكون الصورة من نوع png, jpg, jpeg',
            'images.*.mimes' => 'يجب ان تكون الصورة من نوع png, jpg, jpeg',
          ]
        );
        $oldImage = $request['image_hidden'] ?? [];
        $allImages = [];
        if ($files = $request->File('images')) {
          $allImages += $oldImage;
          foreach ($files as $image) {
            $imageData = upload_image_without_resize('products/' . $product->id, $image);
            $allImages[] = $imageData;
          }
          $data['images'] = json_encode($allImages);
        }else {
          $data['images'] = json_encode($oldImage);
        }
        $product->images = $data['images'];
        $updateImages = $product->update();
        if ($updateImages) {
          return redirect()->route('admin.products')
            ->with('success', 'تم حفظ البيانات بنجاح');
        } else {
          return redirect()->back()
            ->with('failed', 'لم نستطع حفظ البيانات');
        }
    }
    public function delete(Products $product){

        if($product->image != ''){
            File::deleteDirectory(public_path('uploads/products/'.$product->id),);
        }
        if($product->delete()){
            return Response::json($product->id);
        }else{
            return Response::json("false");
        }
    }
}
