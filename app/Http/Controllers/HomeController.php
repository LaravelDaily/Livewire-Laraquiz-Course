<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $query = Quiz::withCount(['questions'])
            ->has('questions')
            ->when(auth()->guest() || ! auth()->user()->is_admin, function($query) {
                return $query->where('published', 1);
            })
            ->orderBy('id')
            ->get();

        $public = $query->where('public', true);
        $registered = $query->where('public', false);

        return view('home', compact('public', 'registered'));
    }

    public function show(Quiz $quiz, $slug = null)
    {
        return view('front.quizzes.show', compact( 'quiz'));
    }
}
