<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();
        if ($request->search) $query->where('name', 'like', '%' . $request->search . '%')->orWhere('employee_id', 'like', '%' . $request->search . '%');
        if ($request->role) $query->where('role', $request->role);
        if ($request->status) $query->where('status', $request->status);
        $employees = $query->latest()->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = ['manager', 'cashier', 'waiter', 'kitchen_staff', 'delivery_staff', 'cleaner', 'guard', 'accountant'];
        return view('employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'phone'             => 'required|string',
            'email'             => 'nullable|email',
            'address'           => 'nullable|string',
            'date_of_birth'     => 'nullable|date',
            'gender'            => 'nullable|in:male,female,other',
            'role'              => 'required|string',
            'department'        => 'nullable|string',
            'salary'            => 'required|numeric|min:0',
            'hire_date'         => 'required|date',
            'avatar'            => 'nullable|image|max:2048',
            'nid'               => 'nullable|string|max:20',
            'nid_photo'         => 'nullable|image|max:4096',
            'emergency_contact' => 'nullable|string',
        ]);

        $data['employee_id'] = 'EMP-' . date('y') . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);
        $data['status'] = 'active';

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('employees', 'public');
        }
        if ($request->hasFile('nid_photo')) {
            $data['nid_photo'] = $request->file('nid_photo')->store('employees/nid', 'public');
        }

        Employee::create($data);
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $recentAttendances = $employee->attendances()->orderBy('date','desc')->limit(10)->get();
        return view('employees.show', compact('employee', 'recentAttendances'));
    }

    public function edit(Employee $employee)
    {
        $roles = ['manager', 'cashier', 'waiter', 'kitchen_staff', 'delivery_staff', 'cleaner', 'guard', 'accountant'];
        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:100',
            'phone'             => 'required|string',
            'email'             => 'nullable|email',
            'address'           => 'nullable|string',
            'role'              => 'required|string',
            'department'        => 'nullable|string',
            'salary'            => 'required|numeric|min:0',
            'hire_date'         => 'required|date',
            'date_of_birth'     => 'nullable|date',
            'emergency_contact' => 'nullable|string',
            'status'            => 'required|in:active,inactive,terminated',
            'avatar'            => 'nullable|image|max:2048',
            'nid'               => 'nullable|string|max:20',
            'nid_photo'         => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('avatar')) {
            if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
            $data['avatar'] = $request->file('avatar')->store('employees', 'public');
        }
        if ($request->hasFile('nid_photo')) {
            if ($employee->nid_photo) Storage::disk('public')->delete($employee->nid_photo);
            $data['nid_photo'] = $request->file('nid_photo')->store('employees/nid', 'public');
        }

        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->avatar) Storage::disk('public')->delete($employee->avatar);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }

    // Attendance
    public function attendance(Request $request)
    {
        $date = $request->date ?? today()->toDateString();
        $employees = Employee::where('status', 'active')
            ->with(['attendances' => fn($q) => $q->whereDate('date', $date)])
            ->get();
        return view('employees.attendance_index', compact('employees', 'date'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id', 'date' => 'required|date', 'status' => 'required|in:present,absent,late,half_day,leave', 'check_in' => 'nullable', 'check_out' => 'nullable']);
        Attendance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            $request->only(['status', 'check_in', 'check_out', 'notes'])
        );
        return back()->with('success', 'Attendance marked.');
    }

    public function employeeAttendance(Request $request, Employee $employee)
    {
        $query = $employee->attendances()->orderBy('date', 'desc');
        if ($request->month) {
            $query->whereYear('date', substr($request->month, 0, 4))
                  ->whereMonth('date', substr($request->month, 5, 2));
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $attendances = $query->paginate(31);
        $stats = [
            'present' => $employee->attendances()->where('status', 'present')->count(),
            'absent' => $employee->attendances()->where('status', 'absent')->count(),
            'late' => $employee->attendances()->where('status', 'late')->count(),
            'total_hours' => $employee->attendances()->whereNotNull('working_hours')->sum('working_hours'),
        ];
        $recentAttendances = $employee->attendances()->orderBy('date','desc')->limit(10)->get();
        return view('employees.attendance', compact('employee', 'attendances', 'stats', 'recentAttendances'));
    }

    public function markEmployeeAttendance(Request $request, Employee $employee)
    {
        $action = $request->action;
        $today = today()->toDateString();
        if ($action === 'check_in') {
            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $today],
                ['check_in' => now(), 'status' => 'present']
            );
        } elseif ($action === 'check_out') {
            $att = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();
            if ($att) {
                $hours = $att->check_in ? round($att->check_in->diffInMinutes(now()) / 60, 2) : 0;
                $att->update(['check_out' => now(), 'working_hours' => $hours]);
            }
        }
        return back()->with('success', 'Attendance updated.');
    }
}
