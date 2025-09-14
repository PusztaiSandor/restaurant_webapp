<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // Visszajelzés küldése (üzenet vagy értékelés).
    // Csak bejelentkezett 'user' szerepkörű felhasználó küldhet visszajelzést.
    public function send(Request $request)
    {
        if (! Auth::check() || Auth::user()->role !== 'user') {
            return redirect()->route('login')->with('error', 'A visszajelzéshez be kell jelentkezned user szerepkörben.');
        }

        $request->validate([
            'type' => 'required|in:message,rating',
            'rating' => 'nullable|required_if:type,rating|integer|min:1|max:5',
            'subject' => 'nullable|string|max:150|regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .,!?()@\\-]*$/u',
            'content' => 'required|string|min:10|max:1000|regex:/^[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 .,!?()@\\-\\n\\r]*$/u',
        ], [
            'type.required' => 'Kérlek válaszd ki a visszajelzés típusát.',
            'type.in' => 'A visszajelzés típusa csak üzenet vagy értékelés lehet.',
            'rating.required_if' => 'Az értékelés kiválasztása kötelező, ha értékelést küldesz.',
            'rating.integer' => 'Az értékelés csak egész szám lehet.',
            'rating.min' => 'Legalább 1 csillagot kell választani.',
            'rating.max' => 'Legfeljebb 5 csillagot lehet választani.',
            'subject.max' => 'A tárgy legfeljebb 150 karakter lehet.',
            'subject.regex' => 'A tárgy csak betűket, számokat és írásjeleket tartalmazhat.',
            'content.required' => 'Az üzenet vagy vélemény megadása kötelező.',
            'content.min' => 'Az üzenet legalább 10 karakter hosszú legyen.',
            'content.max' => 'Az üzenet legfeljebb 1000 karakter lehet.',
            'content.regex' => 'Az üzenet nem tartalmazhat nem engedélyezett karaktereket.',
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

    // Kapcsolat oldal megjelenítése.

    public function index()
    {
        return view('contact');
    }

    // Általános felhasználási feltételek oldal megjelenítése.
    public function terms()
    {
        return view('terms');
    }
    // Admin visszajelzések listázása.

    public function adminFeedbacks(Request $request)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = \App\Models\Feedback::with('user');

        if ($request->filled('type') && in_array($request->type, ['message', 'rating'])) {
            $query->where('type', $request->type);
        }

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
