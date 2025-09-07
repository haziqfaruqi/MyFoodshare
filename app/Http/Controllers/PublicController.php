<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:general,support,partnership,feedback,media',
        ]);

        // Here you would typically send an email or store the message
        // For now, we'll just redirect with a success message
        
        return back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');
    }
}