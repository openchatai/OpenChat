<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingWebsiteController extends Controller
{
    public function index()
    {
        return view('marketing.index');
    }

    public function pricing()
    {
        return view('marketing.pricing');
    }

    public function privacyPolicy()
    {
        return view('marketing.privacy-policy');
    }
}
