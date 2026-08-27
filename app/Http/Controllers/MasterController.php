<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Browsershot\Browsershot;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Category;
use Bouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        $whatsapp = Setting::where('type', 'whatsapp')->first();
        $telegram = Setting::where('type', 'telegram')->first();
        $phone = Setting::where('type', 'phone')->first();
        return view('master_setting.index', compact('whatsapp', 'telegram', 'phone'));
    }
}
