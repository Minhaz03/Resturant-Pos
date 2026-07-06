@extends('layouts.app')
@section('title', 'Edit Menu Item')

@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<style>
    .filepond--root { font-family: 'Inter', sans-serif; }
    .filepond--panel-root { background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 10px; }
    .filepond--drop-label { color: #64748b; font-size: 0.875rem; }
    .filepond--label-action { color: var(--primary, #8B0000); font-weight: 600; text-decoration: underline; }
    .filepond--item-panel { background: var(--secondary, #0A2647); }
    .filepond--file-action-button { background: rgba(139,0,0,0.85); }
    [data-filepond-item-state='processing-complete'] .filepond--item-panel { background: #1a7f5a; }
    [data-filepond-item-state='error'] .filepond--item-panel,
    [data-filepond-item-state='aborted'] .filepond--item-panel { background: #dc2626; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $menuItem->name }}</h4>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('menu.update',$menuItem) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name',$menuItem->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id',$menuItem->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description',$menuItem->description) }}</textarea></div>
                        <div class="col-md-4"><label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price',$menuItem->price) }}" required></div>
                        <div class="col-md-4"><label class="form-label">Cost Price (৳)</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price',$menuItem->cost_price) }}"></div>
                        <div class="col-md-4"><label class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount',$menuItem->discount) }}"></div>
                        <div class="col-md-4"><label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate',$menuItem->tax_rate) }}"></div>
                        <div class="col-md-4"><label class="form-label">Prep Time (min)</label>
                            <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time',$menuItem->prep_time) }}"></div>
                        <div class="col-md-4"><label class="form-label">Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit',$menuItem->unit) }}"></div>
                        <div class="col-md-6"><label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku',$menuItem->sku) }}"></div>
                        <div class="col-md-6"><label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode',$menuItem->barcode) }}"></div>

                        {{-- FilePond Image Upload --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Item Image</label>
                            <input type="file" id="menuItemImage" name="image" accept="image/*">
                            <div class="text-muted" style="font-size:0.78rem;margin-top:4px">
                                <i class="bi bi-info-circle me-1"></i>Upload a new image to replace the current one. Max 4MB.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="avail" {{ old('is_available',$menuItem->is_available)?'checked':'' }}>
                                <label class="form-check-label" for="avail">Available</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured',$menuItem->is_featured)?'checked':'' }}>
                                <label class="form-check-label" for="feat">Featured</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="stat" {{ old('status',$menuItem->status)?'checked':'' }}>
                                <label class="form-check-label" for="stat">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Update</button>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary">Cancel</a>
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

    @php
        $existingImageUrl = $menuItem->image_url;
        $hasImage = $menuItem->hasMedia('image') || $menuItem->image;
    @endphp

    const pondConfig = {
        allowMultiple: false,
        maxFileSize: '4MB',
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
        labelIdle: '<i class="bi bi-cloud-arrow-up" style="font-size:1.5rem;color:var(--primary)"></i><br><span style="font-weight:600;color:#374151">Drag & Drop item image</span><br><span style="color:#6b7280;font-size:0.82rem"> or <span class="filepond--label-action">Browse</span></span>',
        imagePreviewHeight: 180,
        server: null,
        instantUpload: false,
        storeAsFile: true,
        onremovefile: function() {
            document.getElementById('removeImageFlag').value = '1';
        },
        onaddfile: function() {
            document.getElementById('removeImageFlag').value = '0';
        },
    };

    @if($hasImage)
    pondConfig.files = [{
        source: '{{ $existingImageUrl }}',
        options: {
            type: 'local',
            file: {
                name: 'current-image.jpg',
                size: 0,
                type: 'image/jpeg',
            },
            metadata: { poster: '{{ $existingImageUrl }}' },
        },
    }];
    pondConfig.server = {
        load: (source, load, error, progress, abort, headers) => {
            fetch(source)
                .then(res => res.blob())
                .then(blob => load(blob))
                .catch(e => error(e));
            return { abort: () => abort() };
        },
    };
    @endif

    FilePond.create(document.querySelector('#menuItemImage'), pondConfig);
</script>
@endpush
