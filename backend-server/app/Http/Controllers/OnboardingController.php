<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function welcome()
    {
        return view('onboarding.step-0');
    }

    public function dataSources()
    {
        return view('onboarding.step-1');
    }

    public function dataSourcesWebsite()
    {
        return view('onboarding.step-2');
    }

    public function dataSourcesPdf()
    {
        return view('onboarding.step-2-pdf');
    }

    public function config()
    {
        return view('onboarding.step-3');
    }

    public function done()
    {
        return view('onboarding.step-4');
    }

    public function dataSourcesCodebase()
    {
        return view('onboarding.step-2-codebase');
    }
}
