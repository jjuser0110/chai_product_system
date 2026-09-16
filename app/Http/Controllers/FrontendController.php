<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $banners = Banner::with('file_attachments')
            ->where('is_active', 1)
            ->orderBy('arrangement')
            ->get();

        $highlights = Product::with([
            'category',
            'file_attachments'
        ])
        ->where('is_active', 1)
        ->where('is_highlight', 1)
        ->orderBy('arrangement')
        ->get();

        $whatsapp = Setting::where('type', 'whatsapp')->value('value');
        $telegram = Setting::where('type', 'telegram')->value('value');
        $phone = Setting::where('type', 'phone')->value('value');

        return view('frontend.index', compact(
            'banners',
            'highlights',
            'whatsapp',
            'telegram',
            'phone'
        ));
    }

    public function product(Product $product)
    {
        $product->load([
            'category',
            'file_attachments'
        ]);

        return view('frontend.product', compact('product'));
    }

    public function categories(Request $request)
    {
        $categories = Category::withCount('products')
            ->orderBy('category_name')
            ->get();
    
        $selectedCategory = null;
        $products = collect();
    
        if ($request->filled('category')) {
    
            $selectedCategory = Category::findOrFail($request->category);
    
            $products = Product::with([
                'category',
                'file_attachments'
            ])
            ->where('category_id', $selectedCategory->id)
            ->where('is_active', 1)
            ->orderBy('arrangement')
            ->get();
        }
    
        $whatsapp = Setting::where('type', 'whatsapp')->value('value');
        $telegram = Setting::where('type', 'telegram')->value('value');
        $phone = Setting::where('type', 'phone')->value('value');
    
        return view('frontend.category', compact(
            'categories',
            'selectedCategory',
            'products',
            'whatsapp',
            'telegram',
            'phone'
        ));
    }

    public function highlights()
    {
        $highlights = Product::with([
            'category',
            'file_attachments'
        ])
        ->where('is_active', 1)
        ->where('is_highlight', 1)
        ->orderBy('arrangement')
        ->get();

        $whatsapp = Setting::where('type', 'whatsapp')->value('value');
        $telegram = Setting::where('type', 'telegram')->value('value');
        $phone = Setting::where('type', 'phone')->value('value');

        return view('frontend.highlight', compact(
            'highlights',
            'whatsapp',
            'telegram',
            'phone'
        ));
    }

    public function contact()
    {
        $whatsapp = Setting::where('type', 'whatsapp')->value('value');
        $telegram = Setting::where('type', 'telegram')->value('value');
        $phone = Setting::where('type', 'phone')->value('value');

        return view('frontend.contact', compact(
            'whatsapp',
            'telegram',
            'phone'
        ));
    }
    
}