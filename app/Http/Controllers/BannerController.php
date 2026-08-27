<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use App\Models\Banner;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banner = Banner::all();

        return view('banner.index')->with('banner',$banner);
    }

    public function create()
    {
        return view('banner.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $banner = Banner::create($request->all());

        if (isset($request->file_attachment)) {
                $upload = $this->upload($request->file_attachment, 'banner', $banner->id);
                $request->merge([
                    'file_name'=>$upload['file_name'],
                    'file_path'=>$upload['file_path'],
                    'file_type'=>$upload['file_type']
                ]);
                $banner->file_attachments()->create($request->all());
        }

        return redirect()->route('banner.index')->withSuccess('Data saved');
    }

    public function edit(Banner $banner)
    {
        return view('banner.create')->with('banner',$banner);
    }

    public function update(Request $request, Banner $banner)
    {
        $banner->update($request->all());

        if (isset($request->file_attachment)) {
            if(isset($banner->file_attachments)){
                foreach($banner->file_attachments as $attachment){
                    $attachment->delete();
                }
            }
            $upload = $this->upload($request->file_attachment, 'banner', $banner->id);
            $request->merge([
                'file_name'=>$upload['file_name'],
                'file_path'=>$upload['file_path'],
                    'file_type'=>$upload['file_type']
            ]);
            $banner->file_attachments()->create($request->all());
        }
        return redirect()->route('banner.index')->withSuccess('Data updated');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('banner.index')->withSuccess('Data deleted');
    }

}
