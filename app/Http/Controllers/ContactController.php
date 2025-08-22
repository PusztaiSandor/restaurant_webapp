<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class ContactController extends Controller
{
    public function send(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'A visszajelzéshez be kell jelentkezned.');
    }

    $request->validate([
        'type' => 'required|in:message,rating',
        'rating' => 'nullable|integer|min:1|max:5',
        'subject' => 'nullable|string|max:150',
        'content' => 'required|string',
    ]);

    Feedback::create([
        'users_id' => Auth::id(),
        'type' => $request->type,
        'rating' => $request->type === 'rating' ? $request->rating : null,
        'subject' => $request->subject,
        'content' => $request->content,
    ]);

    return redirect()->back()->with('success', 'Köszönjük a visszajelzésed!');
}

    public function index()
{
    return view('contact');
}

public function terms()
{
    return view('terms');
}

}
