@extends('layouts.app')
@section('title','Settings')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Restaurant Settings</h4>
</div>
<div class="row g-4">
    <div class="col-lg-3">
        <div class="list-group" id="settingsTabs">
            <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list"><i class="bi bi-shop me-2"></i>General</a>
            <a href="#business-hours" class="list-group-item list-group-item-action" data-bs-toggle="list"><i class="bi bi-clock me-2"></i>Business Hours</a>
            <a href="#tax-loyalty" class="list-group-item list-group-item-action" data-bs-toggle="list"><i class="bi bi-percent me-2"></i>Tax & Loyalty</a>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general">
                <div class="card"><div class="card-header fw-semibold">General Information</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">@csrf @method('PUT')
                        <input type="hidden" name="section" value="general">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">Restaurant Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name',$settings->name ?? '') }}" required></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Tagline</label><input type="text" name="tagline" class="form-control" value="{{ old('tagline',$settings->tagline ?? '') }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$settings->phone ?? '') }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email',$settings->email ?? '') }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Currency Symbol</label><input type="text" name="currency" class="form-control" value="{{ old('currency',$settings->currency ?? '৳') }}"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Currency Code</label><input type="text" name="currency_code" class="form-control" value="{{ old('currency_code',$settings->currency_code ?? 'BDT') }}" maxlength="3"></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">Timezone</label><input type="text" name="timezone" class="form-control" value="{{ old('timezone',$settings->timezone ?? 'Asia/Dhaka') }}"></div>
                            <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address',$settings->address ?? '') }}</textarea></div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Logo</label>
                                @if($settings?->logo)<img src="{{ $settings->logo_url }}" alt="Logo" class="d-block mb-2" style="height:60px;object-fit:contain">@endif
                                <input type="file" name="logo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Receipt Footer</label>
                                <textarea name="receipt_footer" class="form-control" rows="3">{{ old('receipt_footer',$settings->receipt_footer ?? '') }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Save General Settings</button>
                    </form>
                </div></div>
            </div>

            <div class="tab-pane fade" id="business-hours">
                <div class="card"><div class="card-header fw-semibold">Business Hours</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}">@csrf @method('PUT')
                        <input type="hidden" name="section" value="hours">
                        <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Day</th><th>Open</th><th>Open Time</th><th>Close Time</th></tr></thead>
                            <tbody>
                                @foreach($businessHours as $hour)
                                <tr>
                                    <td class="fw-semibold">{{ $hour->day_of_week }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="hours[{{ $hour->id }}][is_open]" value="1" {{ $hour->is_open?'checked':'' }}>
                                        </div>
                                        <input type="hidden" name="hours[{{ $hour->id }}][id]" value="{{ $hour->id }}">
                                    </td>
                                    <td><input type="time" name="hours[{{ $hour->id }}][open_time]" class="form-control form-control-sm" value="{{ $hour->open_time }}" style="max-width:120px"></td>
                                    <td><input type="time" name="hours[{{ $hour->id }}][close_time]" class="form-control form-control-sm" value="{{ $hour->close_time }}" style="max-width:120px"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Business Hours</button>
                    </form>
                </div></div>
            </div>

            <div class="tab-pane fade" id="tax-loyalty">
                <div class="card"><div class="card-header fw-semibold">Tax & Loyalty Settings</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.update') }}">@csrf @method('PUT')
                        <input type="hidden" name="section" value="tax_loyalty">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">Default Tax Rate (%)</label><input type="number" name="tax_rate" class="form-control" value="{{ old('tax_rate',$settings->tax_rate ?? 0) }}" step="0.01" min="0" max="100"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Tax Name</label><input type="text" name="tax_name" class="form-control" value="{{ old('tax_name',$settings->tax_name ?? 'VAT') }}"></div>
                            <div class="col-12"><hr><h6 class="fw-bold text-muted">Loyalty Program</h6></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Points per ৳100 Spent</label><input type="number" name="loyalty_points_per_100" class="form-control" value="{{ old('loyalty_points_per_100',$settings->loyalty_points_per_100 ?? 1) }}" min="0"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Points Value (৳ per point)</label><input type="number" name="loyalty_point_value" class="form-control" value="{{ old('loyalty_point_value',$settings->loyalty_point_value ?? 1) }}" step="0.01" min="0"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Min Points to Redeem</label><input type="number" name="min_redeem_points" class="form-control" value="{{ old('min_redeem_points',$settings->min_redeem_points ?? 100) }}" min="0"></div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="loyalty_enabled" id="loyaltyEnabled" value="1" {{ old('loyalty_enabled',$settings->loyalty_enabled ?? true)?'checked':'' }}>
                                    <label class="form-check-label" for="loyaltyEnabled">Enable Loyalty Program</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Save Tax & Loyalty</button>
                    </form>
                </div></div>
            </div>
        </div>
    </div>
</div>
@endsection
