@extends('layouts.app')

@section('title', 'New Support Ticket')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border shadow-sm text-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0 text-dark fw-bold">Create New Ticket</h4>
        <p class="text-muted small mb-0">Describe your issue in detail so we can assist you better.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="subject" class="form-label text-muted fw-semibold small text-uppercase">Subject</label>
                        <input type="text" class="form-control form-control-lg bg-light @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Brief description of your issue" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="priority" class="form-label text-muted fw-semibold small text-uppercase">Priority Level</label>
                        <select class="form-select form-select-lg bg-light @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - General question or feedback</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium - Issue that doesn't stop operations</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Critical issue stopping business operations</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label text-muted fw-semibold small text-uppercase">Detailed Description</label>
                        <textarea class="form-control bg-light @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Please provide as much detail as possible about your problem..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="attachment" class="form-label text-muted fw-semibold small text-uppercase">Attachment (Optional)</label>
                        <input class="form-control bg-light @error('attachment') is-invalid @enderror" type="file" id="attachment" name="attachment">
                        <div class="form-text">Max file size: 5MB.</div>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-5">
                        <a href="{{ route('tickets.index') }}" class="btn btn-light border px-4 py-2 text-secondary fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-send"></i> Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card shadow-sm border-0 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-info-circle fs-5"></i>
                    </div>
                    <h5 class="mb-0 text-primary fw-bold">Tips for Faster Help</h5>
                </div>
                <ul class="text-muted small ps-3 mb-0 lh-lg">
                    <li>Be specific about the issue you are facing.</li>
                    <li>If it's an error, describe what you did right before it happened.</li>
                    <li>Mention if this affects all users or just your account.</li>
                    <li>Use "High Priority" only for critical operational blockers.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
