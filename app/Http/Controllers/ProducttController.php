<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProducttController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::all();

        return view('product.index')->with('product',$product);
    }

    public function create()
    {
        $category = Category::all();
        return view('product.create')->with('category',$category);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $product = Product::create($request->all());

        if ($request->hasFile('file_attachment')) {
            foreach ($request->file('file_attachment') as $file) {
                $upload = $this->upload($file, 'product', $product->id);
                $request->merge([
                    'file_name'=>$upload['file_name'],
                    'file_path'=>$upload['file_path'],
                    'file_type'=>$upload['file_type']
                ]);
                $product->file_attachments()->create($request->all());
            }
        }

        return redirect()->route('product.index')->withSuccess('Data saved');
    }

    public function edit(Product $product)
    {
        $category = Category::all();
        return view('product.create')->with('product',$product)->with('category',$category);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        if ($request->hasFile('file_attachment')) {
            foreach ($request->file('file_attachment') as $file) {
                $upload = $this->upload($file, 'product', $product->id);
                $request->merge([
                    'file_name'=>$upload['file_name'],
                    'file_path'=>$upload['file_path'],
                    'file_type'=>$upload['file_type']
                ]);
                $product->file_attachments()->create($request->all());
            }
        }
        return redirect()->route('product.index')->withSuccess('Data updated');
    }

    public function destroy(Product $product)
    {
        if($product->categories()->count()>0){
            return redirect()->route('product.index')->withErrors('Product has related categories. You can not delete this.');
        }
        $product->delete();

        return redirect()->route('product.index')->withSuccess('Data deleted');
    }

}
