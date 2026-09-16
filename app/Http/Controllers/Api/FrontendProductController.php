<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;


class FrontendProductController extends Controller
{
    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Frontend API is working',
        ]);
    }

    public function products()
    {
        $products = Product::with([
            'category',
            'file_attachments'
        ])
        ->where('is_active', 1)
        ->orderBy('arrangement')
        ->get();

        return response()->json($products);
    }

    public function product(Product $product)
    {
        $product->load([
            'category',
            'file_attachments'
        ]);

        return response()->json($product);
    }
    public function categories()
    {
        $categories = \App\Models\Category::withCount([
            'products' => function ($query) {
                $query->where('is_active', 1);
            }
        ])
        ->where('is_active', 1)
        ->orderBy('arrangement')
        ->get();

        return response()->json($categories);
    }

    public function category(\App\Models\Category $category)
    {
        $products = Product::with([
            'file_attachments'
        ])
        ->where('category_id', $category->id)
        ->where('is_active', 1)
        ->orderBy('arrangement')
        ->get();

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }

    public function highlights()
    {
        $products = Product::with([
            'category',
            'file_attachments'
        ])
        ->where('is_active', 1)
        ->where('is_highlight', 1)
        ->orderBy('arrangement')
        ->get();

        return response()->json($products);
    }

    public function settings()
    {
        return response()->json([
            'whatsapp' => Setting::where('type', 'whatsapp')->value('value'),
            'telegram' => Setting::where('type', 'telegram')->value('value'),
            'phone' => Setting::where('type', 'phone')->value('value'),
        ]);
    }

    public function home()
    {
        $banners = Banner::with('file_attachments')
            ->where('is_active', 1)
            ->orderBy('arrangement')
            ->get()
            ->map(function ($banner) {
    
                $attachment = $banner->file_attachments->first();
    
                return [
                    'id' => $banner->id,
                    'box_wording' => $banner->box_wording,
                    'title' => $banner->title,
                    'description' => $banner->description,
                    'button_text' => $banner->button_text,
                    'button_link' => $banner->button_link,
                    'image' => $attachment
                        ? asset('storage/' . ltrim($attachment->file_path, '/'))
                        : null,
                ];
            });
    
        $highlights = Product::with([
            'category',
            'file_attachments'
        ])
        ->where('is_active', 1)
        ->where('is_highlight', 1)
        ->orderBy('arrangement')
        ->get();
    
        return response()->json([
            'banners' => $banners,
            'highlights' => $highlights,
        ]);
    }
}