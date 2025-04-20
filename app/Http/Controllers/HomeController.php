<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\StudyCircle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with statistics
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'departments' => Department::count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'students' => User::where('role', 'student')->count(),
            'circles' => StudyCircle::count(),
        ];

        return view('welcome', compact('stats'));
    }
} 