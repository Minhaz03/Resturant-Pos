@extends('layouts.app')
@section('title', 'Add Category')

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    <style>
        .filepond--root {
            font-family: 'Inter', sans-serif;
        }

        .filepond--panel-root {
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 10px;
        }

        .filepond--drop-label {
            color: #64748b;
            font-size: 0.875rem;
            height: 250px;
            line-height: 20px;
        }

        .filepond--label-action {
            color: var(--primary, #8B0000);
            font-weight: 600;
            text-decoration: underline;
        }

        .filepond--item-panel {
            background: var(--secondary, #0A2647);
        }

        .filepond--file-action-button {
            background: rgba(139, 0, 0, 0.85);
        }

        [data-filepond-item-state='processing-complete'] .filepond--item-panel {
            background: #1a7f5a;
        }

        [data-filepond-item-state='error'] .filepond--item-panel,
        [data-filepond-item-state='aborted'] .filepond--item-panel {
            background: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary"><i
                class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add Category</h4>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data"
                        id="categoryCreateForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Image</label>
                            <input type="file" id="categoryImage" name="image" accept="image/*" class="filepond-input">
                            <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                                <i class="bi bi-info-circle me-1"></i>Drag & drop or click to upload. Max 4MB. JPG, PNG,
                                GIF, WebP supported.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}"
                                min="0">
                        </div>
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                                {{ old('status', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        const inputElement = document.querySelector('#categoryImage');
        const pond = FilePond.create(inputElement, {
            allowMultiple: false,
            maxFileSize: '4MB',
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            labelIdle: '<i class="bi bi-cloud-arrow-up" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Drag & Drop your image</span><br><span style="color:#6b7280;font-size:0.82rem"> or <span class="filepond--label-action">Browse</span></span>',
            imagePreviewHeight: 160,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 800,
            imageResizeTargetHeight: 800,
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
            server: null,
            instantUpload: false,
            storeAsFile: true,
        });
    </script>
@endpush
