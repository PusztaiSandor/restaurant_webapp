<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Feedback;

class ContactController extends Controller
{
    public function send(Request $request)
{
    // 🔒 Csak bejelentkezett user szerepkörű felhasználó küldhet visszajelzést
    if (!Auth::check() || Auth::user()->role !== 'user') {
        return redirect()->route('login')->with('error', 'A visszajelzéshez be kell jelentkezned user szerepkörben.');
    }

    // ✅ Validáció
    $request->validate([
        'type' => 'required|in:message,rating',
        'rating' => 'nullable|integer|min:1|max:5',
        'subject' => 'nullable|string|max:150',
        'content' => 'required|string',
    ]);

    // 💾 Mentés
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

public function adminFeedbacks(Request $request)
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403);
    }

    $query = \App\Models\Feedback::with('user');

    // Szűrés típus szerint
    if ($request->filled('type') && in_array($request->type, ['message', 'rating'])) {
        $query->where('type', $request->type);
    }

    // Rendezés
    if ($request->sort === 'date_asc') {
        $query->orderBy('created_at', 'asc');
    } elseif ($request->sort === 'date_desc') {
        $query->orderBy('created_at', 'desc');
    } elseif ($request->sort === 'rating_desc') {
        $query->orderBy('rating', 'desc');
    } elseif ($request->sort === 'rating_asc') {
        $query->orderBy('rating', 'asc');
    } else {
        $query->latest(); // alapértelmezett: legfrissebb elöl
    }

    $feedbacks = $query->get();

    return view('admin.feedbacks', compact('feedbacks'));
}

}
