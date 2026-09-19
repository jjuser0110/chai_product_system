<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('arrangement')
            ->orderBy('tag_name')
            ->get();

        return view('tag.index', compact('tags'));
    }

    public function create()
    {
        return view('tag.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string|max:255',
            'arrangement' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        Tag::create([
            'tag_name' => $request->tag_name,
            'arrangement' => $request->arrangement,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('tag.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        return view('tag.form', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'tag_name' => 'required|string|max:255',
            'arrangement' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $tag->update([
            'tag_name' => $request->tag_name,
            'arrangement' => $request->arrangement,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('tag.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()
            ->route('tag.index')
            ->with('success', 'Tag deleted successfully.');
    }
}