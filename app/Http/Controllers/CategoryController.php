<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category = Category::all();

        return view('category.index')->with('category',$category);
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $category = Category::create($request->all());

        return redirect()->route('category.index')->withSuccess('Data saved');
    }

    public function edit(Category $category)
    {
        return view('category.create')->with('category',$category);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        return redirect()->route('category.index')->withSuccess('Data updated');
    }

    public function destroy(Category $category)
    {
        if($category->products()->count()>0){
            return redirect()->route('category.index')->withErrors('Category has related items. You can not delete this.');
        }
        $category->delete();

        return redirect()->route('category.index')->withSuccess('Data deleted');
    }

}
