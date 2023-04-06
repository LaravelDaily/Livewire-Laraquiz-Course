<?php

namespace App\Http\Controllers\Admin;

use App\Models\Test;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function __invoke()
    {
        $tests = Test::with(['user', 'quiz'])->withCount('questions')->latest()->paginate();

        return view('admin.tests', compact('tests'));
    }
}
