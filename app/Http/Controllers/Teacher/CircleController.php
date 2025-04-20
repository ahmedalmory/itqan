<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Circle\UpdateCircleRequest;
use App\Models\StudyCircle;
use App\Services\CircleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircleController extends Controller
{
    /**
     * @var CircleService
     */
    protected $circleService;

    /**
     * Create a new controller instance.
     *
     * @param CircleService $circleService
     * @return void
     */
    public function __construct(CircleService $circleService)
    {
        $this->circleService = $circleService;
        $this->middleware('auth');
        $this->middleware('role:teacher');
    }

    /**
     * Display a listing of the teacher's circles.
     */
    public function index()
    {
        $user = Auth::user();
        $circles = $user->taughtCircles()->with('department')->get();
        
        return view('teacher.circles.index', compact('circles'));
    }

    /**
     * Display the specified circle details.
     */
    public function show(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the teacher can only view their own circles
        if ($circle->teacher_id !== $user->id) {
            return abort(403, 'You do not have permission to view this circle.');
        }
        
        $circle->load(['department', 'supervisor', 'students']);
        
        // Get students for this circle with their points
        $students = $circle->students()
            ->with(['studentPoints' => function ($query) use ($circle) {
                $query->where('circle_id', $circle->id);
            }])
            ->paginate(15);
            
        return view('teacher.circles.show', compact('circle', 'students'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the teacher can only edit their own circles
        if ($circle->teacher_id !== $user->id) {
            return abort(403, 'You do not have permission to edit this circle.');
        }
        
        // Teacher can only edit limited circle info
        return view('teacher.circles.edit', compact('circle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCircleRequest $request, StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the teacher can only update their own circles
        if ($circle->teacher_id !== $user->id) {
            return abort(403, 'You do not have permission to update this circle.');
        }
        
        try {
            $circle = $this->circleService->updateCircle($circle, $request->validated());
            
            return redirect()->route('teacher.circles.show', $circle)
                ->with('success', 'Circle updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to update circle: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the students in the specified circle.
     */
    public function students(StudyCircle $circle)
    {
        $user = Auth::user();
        
        // Ensure the teacher can only view their own circles
        if ($circle->teacher_id !== $user->id) {
            return abort(403, 'You do not have permission to view this circle.');
        }
        
        // Get students for this circle with their points and recent reports
        $students = $circle->students()
            ->with([
                'studentPoints' => function ($query) use ($circle) {
                    $query->where('circle_id', $circle->id);
                },
                'dailyReports' => function ($query) {
                    $query->orderBy('report_date', 'desc')->limit(5);
                }
            ])
            ->paginate(15);
            
        return view('teacher.circles.students', compact('circle', 'students'));
    }
} 