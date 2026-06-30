@extends('layouts.app')
@section('title', 'Daily Attendance')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:var(--secondary)">Daily Attendance</h4>
        <p class="text-muted small mb-0">Record and review daily employee attendance</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('employees.attendance') }}" class="d-flex gap-2 align-items-center">
            <label class="fw-semibold mb-0 me-2" style="font-size:0.875rem">Select Date:</label>
            <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" style="max-width:180px" onchange="this.form.submit()">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-arrow-clockwise me-1"></i>Load</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Notes</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    @php 
                        $att = $emp->attendances->first(); 
                    @endphp
                    <tr>
                        <td><span class="fw-semibold">{{ $emp->employee_id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($emp->avatar)
                                <img src="{{ asset('storage/'.$emp->avatar) }}" width="32" height="32" class="rounded-circle" style="object-fit:cover">
                                @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-muted" style="width:32px;height:32px;font-size:0.75rem">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <a href="{{ route('employees.employee-attendance', $emp) }}" class="fw-semibold text-decoration-none" style="color:var(--secondary)">{{ $emp->name }}</a>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_',' ',$emp->role)) }}</span></td>
                        <form method="POST" action="{{ route('employees.mark-attendance') }}">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                            <input type="hidden" name="date" value="{{ $date }}">
                            <td>
                                <select name="status" class="form-select form-select-sm" style="max-width: 120px;" required>
                                    <option value="present" {{ ($att && $att->status == 'present') ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ ($att && $att->status == 'absent') ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ ($att && $att->status == 'late') ? 'selected' : '' }}>Late</option>
                                    <option value="half_day" {{ ($att && $att->status == 'half_day') ? 'selected' : '' }}>Half Day</option>
                                    <option value="leave" {{ ($att && $att->status == 'leave') ? 'selected' : '' }}>Leave</option>
                                </select>
                            </td>
                            <td>
                                <input type="time" name="check_in" class="form-control form-control-sm" value="{{ $att?->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '' }}" style="max-width: 110px;">
                            </td>
                            <td>
                                <input type="time" name="check_out" class="form-control form-control-sm" value="{{ $att?->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '' }}" style="max-width: 110px;">
                            </td>
                            <td>
                                <input type="text" name="notes" class="form-control form-control-sm" value="{{ $att?->notes ?? '' }}" placeholder="Notes..." style="max-width: 180px;">
                            </td>
                            <td class="text-end">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check-lg"></i> Save</button>
                            </td>
                        </form>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No active employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
