<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProducttController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::with([
            'category',
            'tag',
            'file_attachments'
        ])->get();
    
        return view('product.index')
            ->with('product', $product);
    }

    public function create()
    {
        $category = Category::all();
        $tags = Tag::where('is_active', 1)
        ->orderBy('arrangement')
        ->orderBy('tag_name')
        ->get();
        
        return view('product.create', compact('category', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tag_id' => 'nullable|exists:tags,id',
            'description' => 'nullable|string',
            'arrangement' => 'required|integer',
            'is_highlight' => 'required|boolean',
            'file_attachment.*' => 'nullable|image',
        ]);
    
        $product = Product::create([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'tag_id' => $request->tag_id,
            'description' => $request->description,
            'arrangement' => $request->arrangement,
            'is_highlight' => $request->is_highlight,
            'is_active' => 1,
        ]);
    
        // Your existing image upload logic stays here.
    
        return redirect()
            ->route('productt.index')
            ->with('success', 'Girls created successfully.');
    }

    public function edit(Product $product)
    {
        $category = Category::orderBy('category_name')->get();

        $tags = Tag::where('is_active', 1)
            ->orderBy('arrangement')
            ->orderBy('tag_name')
            ->get();

        $product->load('file_attachments');

        return view('productt.create', compact(
            'product',
            'category',
            'tags'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tag_id' => 'nullable|exists:tags,id',
            'description' => 'nullable|string',
            'arrangement' => 'required|integer',
            'is_highlight' => 'required|boolean',
            'is_active' => 'required|boolean',
            'file_attachment.*' => 'nullable|image',
        ]);
    
        $product->update([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'tag_id' => $request->tag_id,
            'description' => $request->description,
            'arrangement' => $request->arrangement,
            'is_highlight' => $request->is_highlight,
            'is_active' => $request->is_active,
        ]);
    
        // Your existing image upload logic stays here.
    
        return redirect()
            ->route('productt.index')
            ->with('success', 'Girls updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
    
        return redirect()
            ->route('productt.index')
            ->with('success', 'Data deleted');
    }

}
