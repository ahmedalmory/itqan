<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Department;
use App\Models\Country;
use App\Models\StudyCircle;
use App\Models\CircleStudent;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('guest')->except(['logout', 'selectCircle', 'storeCircle']);
        $this->middleware('auth')->only(['logout', 'selectCircle', 'storeCircle']);
    }

    /**
     * Show the registration form
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        $countries = Country::orderBy('Order')->get();
        return view('auth.register', compact('countries'));
    }

    /**
     * Show the login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        // Create user using service
        $user = $this->authService->createUser($request->validated());
        
        // Log the user in
        Auth::login($user);
        
        // Redirect to circle selection for students or dashboard for other roles
        if ($user->role === 'student') {
            return redirect()->route('select-circle');
        }
        
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Handle a login request to the application.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        $user = Auth::user();
        
        // Redirect based on user role
        if ($user->isStudent() && $user->circles()->doesntExist()) {
            return redirect()->route('select-circle');
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Show the circle selection form for newly registered students
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function selectCircle()
    {
        $user = Auth::user();
        
        // Only students should see this page
        if (!$user->isStudent()) {
            return $this->redirectBasedOnRole($user);
        }
        
        // If student already belongs to a circle, redirect to dashboard
        if ($user->circles()->exists()) {
            return redirect()->route('student.dashboard');
        }
        
        // Get list of departments that match the student's gender
        $departments = Department::where('student_gender', $user->gender)
            ->where('registration_open', true)
            ->get();
            
        return view('auth.select-circle', compact('departments'));
    }
    
    /**
     * Store the selected circle for a student
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCircle(Request $request)
    {
        $request->validate([
            'circle_id' => 'required|exists:study_circles,id'
        ]);
        
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            return $this->redirectBasedOnRole($user);
        }
        
        // Check if student is already in a circle
        if ($user->circles()->exists()) {
            return redirect()->route('student.dashboard')
                ->with('error', t('already_enrolled_in_circle'));
        }
        
        // Check if circle exists and has space
        $circle = StudyCircle::findOrFail($request->circle_id);
        
        if ($circle->students()->count() >= $circle->max_students) {
            return back()->with('error', t('circle_full'));
        }
        
        // Enroll student in circle
        CircleStudent::create([
            'circle_id' => $circle->id,
            'student_id' => $user->id
        ]);
        
        return redirect()->route('student.dashboard')
            ->with('success', t('enrolled_successfully'));
    }
    
    /**
     * Redirect user based on their role
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectBasedOnRole($user)
    {
        // Redirect based on role
        switch ($user->role) {
            case 'super_admin':
            case 'department_admin':
                return redirect()->route('admin.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'supervisor':
                return redirect()->route('supervisor.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            default:
                return redirect()->route('home');
        }
    }
} 