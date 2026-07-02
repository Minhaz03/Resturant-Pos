@extends('layouts.app')
@section('title','Employee Details')
@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">{{ $employee->name }}</h4>
    <span class="badge {{ $employee->status=='active'?'bg-success':'bg-secondary' }} ms-2">{{ ucfirst($employee->status) }}</span>
    <div class="ms-auto d-flex gap-2">
        @can('edit employees')<a href="{{ route('employees.edit',$employee) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i>Edit</a>@endcan
        <a href="{{ route('employees.employee-attendance',$employee) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-calendar-check me-1"></i>Attendance</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center">
                @if($employee->avatar)
                    <img src="{{ asset('storage/' . $employee->avatar) }}" alt="Avatar" class="rounded-circle mb-3 object-fit-cover mx-auto" style="width:80px;height:80px;border: 3px solid var(--secondary)">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;background:var(--secondary);color:#fff;font-size:2rem;font-weight:700">{{ strtoupper(substr($employee->name,0,1)) }}</div>
                @endif
                <h5 class="fw-bold mb-1">{{ $employee->name }}</h5>
                <span class="badge bg-light text-dark mb-2">{{ ucfirst(str_replace('_',' ',$employee->role)) }}</span>
                <p class="text-muted small mb-1">{{ $employee->employee_id }}</p>
                <p class="text-muted small">{{ $employee->department ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-semibold">Today's Attendance</div>
            <div class="card-body">
                @if($employee->today_attendance)
                    @php $att = $employee->today_attendance; @endphp
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Check In</span><span class="fw-semibold text-success">{{ $att->check_in?->format('h:i A') ?? '—' }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Check Out</span><span class="fw-semibold {{ $att->check_out?'text-danger':'text-muted' }}">{{ $att->check_out?->format('h:i A') ?? 'In Progress' }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Hours</span><span class="fw-semibold">{{ $att->working_hours ?? '—' }}</span></div>
                @else
                    <p class="text-muted small text-center mb-2">No attendance today</p>
                @endif
                <form method="POST" action="{{ route('employees.mark-attendance-individual',$employee) }}" class="mt-3">@csrf
                    <div class="d-grid gap-2">
                        @if(!$employee->today_attendance)
                            <button type="submit" name="action" value="check_in" class="btn btn-success btn-sm"><i class="bi bi-box-arrow-in-right me-1"></i>Check In</button>
                        @elseif(!$employee->today_attendance->check_out)
                            <button type="submit" name="action" value="check_out" class="btn btn-warning btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Check Out</button>
                        @else
                            <span class="btn btn-outline-secondary btn-sm disabled">Completed</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header fw-semibold">Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="text-muted small">Phone</label><p class="fw-semibold mb-0">{{ $employee->phone ?? '—' }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Email</label><p class="fw-semibold mb-0">{{ $employee->email ?? '—' }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Date of Birth</label><p class="fw-semibold mb-0">{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</p></div>
                    <div class="col-md-6">
                        <label class="text-muted small">NID</label>
                        <p class="fw-semibold mb-0">
                            {{ $employee->nid ?? '—' }}
                            @if($employee->nid_photo)
                                <a href="{{ asset('storage/' . $employee->nid_photo) }}" target="_blank" class="ms-2 badge bg-light text-primary border"><i class="bi bi-file-earmark-image me-1"></i>View Photo</a>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6"><label class="text-muted small">Hire Date</label><p class="fw-semibold mb-0">{{ $employee->hire_date->format('d M Y') }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Salary</label><p class="fw-semibold mb-0">৳{{ number_format($employee->salary,0) }}</p></div>
                    <div class="col-md-6"><label class="text-muted small">Emergency Contact</label><p class="fw-semibold mb-0">{{ $employee->emergency_contact ?? '—' }}</p></div>
                    <div class="col-12"><label class="text-muted small">Address</label><p class="fw-semibold mb-0">{{ $employee->address ?? '—' }}</p></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Recent Attendance</span>
                <a href="{{ route('employees.employee-attendance',$employee) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Date</th><th>Check In</th><th>Check Out</th><th>Hours</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentAttendances as $att)
                            <tr>
                                <td>{{ $att->date->format('d M Y') }}</td>
                                <td>{{ $att->check_in?->format('h:i A') ?? '—' }}</td>
                                <td>{{ $att->check_out?->format('h:i A') ?? '—' }}</td>
                                <td>{{ $att->working_hours ?? '—' }}</td>
                                <td><span class="badge {{ match($att->status){'present'=>'bg-success','absent'=>'bg-danger','late'=>'bg-warning text-dark','half_day'=>'bg-info',default=>'bg-secondary'} }}">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">No attendance records</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
