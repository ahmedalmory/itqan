<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\StudyCircle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\CsvController;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Traits\CsvOperations;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use CsvOperations;

    /**
     * Get required headers for CSV import
     */
    protected function getRequiredHeaders(): array
    {
        return [
            'Name',
            'Email'
        ];
    }

    /**
     * Get default values for optional fields
     */
    protected function getDefaultValues(): array
    {
        return [
            'Phone' => null,
            'Role' => 'student',
            'Country' => 'Saudi Arabia',
            'is_active' => true,
            'password' => 'password123'
        ];
    }

    /**
     * Get column mapping for CSV import
     */
    protected function getColumnMap(): array
    {
        return [
            'Name' => 'name',
            'Email' => 'email',
            'Phone' => 'phone',
            'Role' => 'role',
            'Country' => 'country_name',
            'Password' => 'password'
        ];
    }

    /**
     * Process a single record from CSV
     */
    protected function processRecord(array $record)
    {
        // Find or create country
        $country = Country::firstOrCreate(
            ['name' => $record['country_name']],
            ['alt_name' => $record['country_name']]
        );

        // Validate role
        $validRoles = ['student', 'teacher', 'supervisor', 'department_admin', 'super_admin'];
        if (!in_array($record['role'], $validRoles)) {
            throw new \Exception("Invalid role: {$record['role']}");
        }

        // Create user
        return User::create([
            'name' => $record['name'],
            'email' => $record['email'],
            'phone' => $record['phone'],
            'role' => $record['role'],
            'country_id' => $country->id,
            'password' => Hash::make($record['password'])
        ]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::with('country');

        // Apply filters
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        if ($request->has('country_id') && $request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        $headers = [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Role',
            'Country',
            'Created At'
        ];

        $filename = 'users_' . date('Y-m-d') . '.csv';

        $callback = function() use ($users, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($users as $user) {
                $row = [
                    'ID' => $user->id,
                    'Name' => $user->name,
                    'Email' => $user->email,
                    'Phone' => $user->phone,
                    'Role' => $user->role,
                    'Country' => $user->country ? $user->country->name : 'N/A',
                    'Created At' => $user->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        $user = auth()->user();
        
        // Department admins can't see super_admin or other department_admins
        if ($user->isDepartmentAdmin()) {
            $query->where(function($q) {
                $q->where('role', '!=', 'super_admin')
                  ->where(function($subQ) {
                      $subQ->where('role', '!=', 'department_admin')
                           ->orWhere('id', auth()->id()); // They can see themselves
                  });
            });
            
            // Department admins can only see users in their departments
            $departmentIds = $user->adminDepartments()->pluck('departments.id');
            
            // Filter users based on their circles' departments
            $query->where(function($q) use ($departmentIds) {
                // Users associated with circles that belong to these departments
                $q->whereHas('circles', function($subQ) use ($departmentIds) {
                    $subQ->whereIn('department_id', $departmentIds);
                });
            });
        }
        
        // Apply filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $users = $query->with('department')->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $studyCircles = StudyCircle::all();
        $circles = StudyCircle::all();
        
        return view('admin.users.create', compact('departments', 'studyCircles', 'circles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|string|in:male,female',
            'age' => 'nullable|integer|min:0', // Added age validation
            'national_id' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,supervisor,teacher,student',
            'study_circle_id' => 'nullable|required_if:role,student|exists:study_circles,id',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        
        User::create($validatedData);
        
        return redirect()->route('admin.users.index')
            ->with('success', t('User created successfully'));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load(['department', 'studyCircle']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        $studyCircles = StudyCircle::all();
        
        return view('admin.users.edit', compact('user', 'departments', 'studyCircles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string|in:male,female',
            'age' => 'nullable|integer|min:0', // Added age validation
            'national_id' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,supervisor,teacher,student',
            'study_circle_id' => 'nullable|required_if:role,student|exists:study_circles,id',
            'is_active' => 'nullable|boolean',
        ]);
        
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        
        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        
        // Clear department and study circle if role doesn't require them
        if (!in_array($validatedData['role'], ['supervisor', 'teacher', 'student'])) {
            $validatedData['department_id'] = null;
        }
        
        if ($validatedData['role'] !== 'student') {
            $validatedData['study_circle_id'] = null;
        }
        
        $user->update($validatedData);
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', t('User updated successfully'));
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', t('You cannot delete your own account'));
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', t('User deleted successfully'));
    }

    /**
     * Import users from CSV file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        if (!$request->hasFile('csv_file')) {
            return back()->with('error', t('Please select a CSV file to import'));
        }

        $validator = \Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $file = $request->file('csv_file');
            $path = $file->getRealPath();

            // Read CSV file
            $csvContent = file_get_contents($path);
            if (empty($csvContent)) {
                throw new \Exception(t('The CSV file is empty'));
            }

            $records = array_map('str_getcsv', explode("\n", $csvContent));
            if (count($records) < 2) {
                throw new \Exception(t('The CSV file must contain at least one user record'));
            }
            
            // Remove header row and empty rows
            $headers = array_map('trim', array_filter($records[0]));
            unset($records[0]);
            $records = array_filter($records);
            
            // Validate required headers
            $requiredHeaders = $this->getRequiredHeaders();
            $missingHeaders = array_diff($requiredHeaders, $headers);
            
            if (!empty($missingHeaders)) {
                throw new \Exception(t('Missing required columns') . ': ' . implode(', ', $missingHeaders));
            }

            $successCount = 0;
            $errors = [];
            $defaultValues = $this->getDefaultValues();
            
            foreach ($records as $index => $record) {
                try {
                    if (count($record) !== count($headers)) {
                        throw new \Exception(t('Invalid number of columns in row') . ' ' . ($index + 1));
                    }

                    $data = array_combine($headers, array_map('trim', $record));
                    
                    // Validate required fields
                    if (empty($data['Name']) || empty($data['Email'])) {
                        throw new \Exception(t('Name and Email are required'));
                    }

                    // Validate email
                    if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
                        throw new \Exception(t('Invalid email format') . ': ' . $data['Email']);
                    }

                    // Check for duplicate email
                    if (User::where('email', $data['Email'])->exists()) {
                        throw new \Exception(t('Email already exists') . ': ' . $data['Email']);
                    }

                    // Merge with default values for optional fields
                    $userData = array_merge($defaultValues, array_filter($data));

                    // Validate role if provided
                    $validRoles = ['student', 'teacher', 'supervisor', 'department_admin'];
                    if (!in_array($userData['Role'], $validRoles)) {
                        $userData['Role'] = $defaultValues['Role']; // Reset to default if invalid
                    }

                    // Find or create country
                    $country = Country::firstOrCreate(
                        ['name' => $userData['Country']],
                        ['alt_name' => $userData['Country']]
                    );

                    // Create user
                    User::create([
                        'name' => $userData['Name'],
                        'email' => $userData['Email'],
                        'phone' => $userData['Phone'],
                        'role' => $userData['Role'],
                        'country_id' => $country->id,
                        'password' => Hash::make($userData['password']),
                        'is_active' => $userData['is_active']
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = t('Row') . ' ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }

            DB::commit();

            $messages = [
                t('Default values used for missing fields') . ':',
                '- ' . t('Role') . ': ' . $defaultValues['Role'],
                '- ' . t('Country') . ': ' . $defaultValues['Country'],
                '- ' . t('Password') . ': ' . $defaultValues['password']
            ];
            $defaultsMessage = implode("\n", $messages);

            if (!empty($errors)) {
                $errorMessage = implode("\n", $errors);
                if ($successCount > 0) {
                    return back()
                        ->with('warning', t('Imported') . ' ' . $successCount . ' ' . t('users with some errors') . ":\n" . $errorMessage)
                        ->with('info', $defaultsMessage);
                }
                return back()->with('error', t('Import failed with errors') . ":\n" . $errorMessage);
            }

            return back()
                ->with('success', t('Successfully imported') . ' ' . $successCount . ' ' . t('users'))
                ->with('info', $defaultsMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', t('Import failed') . ': ' . $e->getMessage());
        }
    }
} 