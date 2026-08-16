@extends('layouts.app')
@section('title', 'Edit Menu Item — ' . $menuItem->name)

@push('styles')
    <style>
        .card-header-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--secondary, #0A2647);
        }

        .form-section-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease-in-out;
        }

        .form-section-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        /* FilePond Dropzone Custom Styling */
        .filepond--root {
            font-family: 'Inter', sans-serif;
            margin-bottom: 0;
        }

        .filepond--panel-root {
            background-color: #f8fafc !important;
            border: 2px dashed #cbd5e1 !important;
            border-radius: 12px !important;
            transition: all 0.2s ease;
        }

        .filepond--root:hover .filepond--panel-root {
            border-color: var(--primary, #8B0000) !important;
            background-color: #fff !important;
        }

        .filepond--drop-label {
            color: #475569 !important;
            font-size: 0.85rem !important;
            min-height: 180px !important;
        }

        .filepond--label-action {
            color: var(--primary, #8B0000) !important;
            font-weight: 700 !important;
            text-decoration: underline !important;
        }

        .filepond--item-panel {
            background-color: var(--secondary, #0A2647) !important;
            border-radius: 8px !important;
        }

        .filepond--file-action-button {
            background-color: rgba(139, 0, 0, 0.85) !important;
            cursor: pointer;
        }

        [data-filepond-item-state='processing-complete'] .filepond--item-panel {
            background-color: #10b981 !important;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary, #8B0000);
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.12);
        }

        /* Image Preview Styles */
        #imagePreviewWrapper {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border: 2px dashed #cbd5e1;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        #imagePreviewWrapper:hover {
            border-color: var(--primary, #8B0000);
            background: linear-gradient(135deg, #fff1f2, #fff);
        }

        #imagePreviewWrapper.has-image {
            border: none;
            background: transparent;
        }

        #imagePreviewImg {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            display: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        #imagePreviewWrapper.has-image #imagePreviewImg {
            display: block;
        }

        #imagePreviewWrapper.has-image .preview-placeholder {
            display: none;
        }

        .preview-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            pointer-events: none;
        }

        .remove-preview-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(139,0,0,0.85);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            z-index: 10;
            transition: background 0.2s;
        }

        .remove-preview-btn:hover {
            background: #8B0000;
        }

        #imagePreviewWrapper.has-image .remove-preview-btn {
            display: flex;
        }

        .image-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.35);
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        #imagePreviewWrapper.has-image:hover .image-overlay {
            display: flex;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('menu.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0" style="color:var(--secondary)">Edit: {{ $menuItem->name }}</h4>
                <p class="text-muted small mb-0">Update menu item details, recipe, and stock settings</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('menu.show', $menuItem) }}" class="btn btn-outline-secondary px-3"><i class="bi bi-eye me-1"></i>View</a>
            <button type="submit" form="editMenuItemForm" class="btn btn-primary px-4"><i class="bi bi-check2 me-1"></i>Update Menu Item</button>
        </div>
    </div>

    <form method="POST" action="{{ route('menu.update', $menuItem) }}" enctype="multipart/form-data" id="editMenuItemForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="remove_image" id="removeImageFlag" value="0">

        <div class="row g-4">
            <!-- Left Main Column (Details, Pricing, Ingredients) -->
            <div class="col-lg-8">
                <!-- Card 1: Basic Information -->
                <div class="card form-section-card mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill text-primary"></i> Basic Information
                        </span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small">Item Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menuItem->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $menuItem->description) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">SKU</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-barcode"></i></span>
                                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $menuItem->sku) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Barcode</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-qr-code-scan"></i></span>
                                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $menuItem->barcode) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pricing & Taxes -->
                <div class="card form-section-card mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-currency-dollar text-success"></i> Pricing & Financials
                        </span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Selling Price (৳) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $menuItem->price) }}" min="0" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Cost Price (৳)</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $menuItem->cost_price ?? 0) }}" min="0">
                                </div>
                                <small class="text-muted" style="font-size:0.75rem">Used for profit margin calculation</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount', $menuItem->discount ?? 0) }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Tax Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate', $menuItem->tax_rate ?? 5) }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Recipe & Ingredients Builder -->
                <div class="card form-section-card">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill text-warning"></i> Recipe & Ingredients (Auto Stock Deduction)
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="addIngredientBtn">
                            <i class="bi bi-plus-lg me-1"></i>Add Ingredient
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="ingredientsTable">
                                <thead class="bg-light">
                                    <tr style="font-size: 0.78rem;" class="text-uppercase text-secondary">
                                        <th style="width: 55%;" class="ps-4">Inventory Stock Item</th>
                                        <th style="width: 30%;">Usage Quantity</th>
                                        <th style="width: 15%;" class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ingredientsContainer">
                                    @forelse($menuItem->ingredients as $idx => $ing)
                                        <tr data-index="{{ $idx }}">
                                            <td class="ps-4">
                                                <select name="ingredients[{{ $idx }}][inventory_item_id]" class="form-select form-select-sm ing-select" required>
                                                    <option value="">Select Inventory Stock Item</option>
                                                    @foreach($inventoryItems as $item)
                                                        <option value="{{ $item->id }}" data-unit="{{ $item->unit ?? 'unit' }}" {{ $ing->inventory_item_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }} ({{ $item->unit ?? 'unit' }}) - Stock: {{ $item->quantity }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.001" min="0.001" name="ingredients[{{ $idx }}][quantity]" class="form-control" value="{{ (float)$ing->quantity }}" placeholder="Qty" required>
                                                    <span class="input-group-text ing-unit">{{ $ing->inventoryItem?->unit ?? 'unit' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-ing-btn"><i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="no-ingredients-row">
                                            <td colspan="3" class="text-center text-muted py-4 small">
                                                <i class="bi bi-info-circle fs-6 d-block mb-1 text-secondary"></i>
                                                No ingredients attached yet. Click <strong>"Add Ingredient"</strong> to link stock for automatic deduction upon POS order.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (Media, Serving, Visibility) -->
            <div class="col-lg-4">
                <!-- Card 1: Custom Image Upload -->
                <div class="card form-section-card mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-image text-info"></i> Item Image
                        </span>
                    </div>
                    <div class="card-body pt-0">
                        {{-- Live Preview Box --}}
                        @php $existingImgUrl = $menuItem->image_url; $hasImg = $menuItem->hasMedia('image') || $menuItem->image; @endphp

                        <div id="imagePreviewWrapper"
                             onclick="document.getElementById('imageFileInput').click()"
                             title="Click to change image"
                             class="{{ $hasImg ? 'has-image' : '' }}">

                            <img id="imagePreviewImg"
                                 src="{{ $hasImg ? $existingImgUrl : '#' }}"
                                 alt="Item Image Preview">

                            <div class="image-overlay">
                                <span class="text-white fw-semibold" style="font-size:0.85rem;">
                                    <i class="bi bi-pencil-fill me-1"></i>Change Image
                                </span>
                            </div>

                            <button type="button" class="remove-preview-btn" id="removePreviewBtn"
                                    title="Remove image"
                                    onclick="event.stopPropagation(); clearImagePreview();">
                                <i class="bi bi-x"></i>
                            </button>

                            <div class="preview-placeholder">
                                <i class="bi bi-cloud-arrow-up" style="font-size:2.4rem; color:#94a3b8;"></i>
                                <div style="font-weight:600;color:#475569;font-size:0.875rem;">Click or drag & drop image here</div>
                                <div style="color:#94a3b8;font-size:0.78rem;">JPG, PNG, WebP &middot; Max 4MB</div>
                            </div>
                        </div>

                        {{-- Hidden file input --}}
                        <input type="file" id="imageFileInput" name="image" accept="image/*" style="display:none;">

                        <div class="text-muted mt-2 d-flex align-items-center gap-1" style="font-size:0.75rem">
                            <i class="bi bi-info-circle"></i> Max file size: 4MB. Uploading a new image will replace the current image.
                        </div>
                    </div>
                </div>

                <!-- Card 2: Serving Details -->
                <div class="card form-section-card mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-secondary"></i> Serving Details
                        </span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Prep Time (Minutes)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-stopwatch"></i></span>
                                <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', $menuItem->prep_time) }}" min="1">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold small">Serving Unit</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid"></i></span>
                                <input type="text" name="unit" class="form-control" value="{{ old('unit', $menuItem->unit ?? 'plate') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Visibility & Status -->
                <div class="card form-section-card mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <span class="card-header-title d-flex align-items-center gap-2">
                            <i class="bi bi-sliders text-primary"></i> Visibility & Status
                        </span>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold small">Available on POS</div>
                                <div class="text-muted" style="font-size:0.72rem">Cashiers can select this dish</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="avail" {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold small">Featured Item</div>
                                <div class="text-muted" style="font-size:0.72rem">Highlight on top of POS menu</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div>
                                <div class="fw-semibold small">Active Status</div>
                                <div class="text-muted" style="font-size:0.72rem">Enable or disable item</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="stat" {{ old('status', $menuItem->status) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card form-section-card bg-light border-0">
                    <div class="card-body d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Update Menu Item
                        </button>
                        <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary w-100 py-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        let ingredientIndex = {{ $menuItem->ingredients->count() }};
        const inventoryItems = @json($inventoryItems);

        document.querySelectorAll('#ingredientsContainer .ing-select').forEach(select => {
            select.addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const unit = selectedOpt.getAttribute('data-unit') || 'unit';
                this.closest('tr').querySelector('.ing-unit').textContent = unit;
            });
        });

        document.querySelectorAll('#ingredientsContainer .remove-ing-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                const container = document.getElementById('ingredientsContainer');
                tr.remove();
                if (container.querySelectorAll('tr').length === 0) {
                    container.innerHTML = `
                        <tr class="no-ingredients-row">
                            <td colspan="3" class="text-center text-muted py-4 small">
                                <i class="bi bi-info-circle fs-6 d-block mb-1 text-secondary"></i>
                                No ingredients attached yet. Click <strong>"Add Ingredient"</strong> to link stock for automatic deduction upon POS order.
                            </td>
                        </tr>
                    `;
                }
            });
        });

        document.getElementById('addIngredientBtn')?.addEventListener('click', function() {
            const container = document.getElementById('ingredientsContainer');
            const emptyRow = container.querySelector('.no-ingredients-row');
            if (emptyRow) emptyRow.remove();

            const tr = document.createElement('tr');
            tr.dataset.index = ingredientIndex;

            let options = '<option value="">Select Inventory Stock Item</option>';
            inventoryItems.forEach(item => {
                options += `<option value="${item.id}" data-unit="${item.unit || 'unit'}">${item.name} (${item.unit || 'unit'}) - Stock: ${item.quantity}</option>`;
            });

            tr.innerHTML = `
                <td class="ps-4">
                    <select name="ingredients[${ingredientIndex}][inventory_item_id]" class="form-select form-select-sm ing-select" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.001" min="0.001" name="ingredients[${ingredientIndex}][quantity]" class="form-control" placeholder="Qty" required>
                        <span class="input-group-text ing-unit">unit</span>
                    </div>
                </td>
                <td class="text-end pe-4">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-ing-btn"><i class="bi bi-trash"></i></button>
                </td>
            `;

            container.appendChild(tr);

            tr.querySelector('.ing-select').addEventListener('change', function() {
                const selectedOpt = this.options[this.selectedIndex];
                const unit = selectedOpt.getAttribute('data-unit') || 'unit';
                tr.querySelector('.ing-unit').textContent = unit;
            });

            tr.querySelector('.remove-ing-btn').addEventListener('click', function() {
                tr.remove();
                if (container.querySelectorAll('tr').length === 0) {
                    container.innerHTML = `
                        <tr class="no-ingredients-row">
                            <td colspan="3" class="text-center text-muted py-4 small">
                                <i class="bi bi-info-circle fs-6 d-block mb-1 text-secondary"></i>
                                No ingredients attached yet. Click <strong>"Add Ingredient"</strong> to link stock for automatic deduction upon POS order.
                            </td>
                        </tr>
                    `;
                }
            });

            ingredientIndex++;
        });

        // ── Custom Image Preview Logic ──
        const imageFileInput = document.getElementById('imageFileInput');
        const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');
        const imagePreviewImg = document.getElementById('imagePreviewImg');
        const removeImageFlag = document.getElementById('removeImageFlag');

        // Handle drag & drop on the wrapper
        imagePreviewWrapper.addEventListener('dragover', function(e) {
            e.preventDefault();
            if (!this.classList.contains('has-image')) {
                this.style.borderColor = 'var(--primary, #8B0000)';
            }
        });
        imagePreviewWrapper.addEventListener('dragleave', function() {
            if (!this.classList.contains('has-image')) {
                this.style.borderColor = '';
            }
        });
        imagePreviewWrapper.addEventListener('drop', function(e) {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                imageFileInput.files = dt.files;
                showPreview(file);
            }
        });

        imageFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                showPreview(this.files[0]);
                removeImageFlag.value = '0';
            }
        });

        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreviewImg.src = e.target.result;
                imagePreviewWrapper.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        }

        function clearImagePreview() {
            imagePreviewImg.src = '#';
            imagePreviewWrapper.classList.remove('has-image');
            imageFileInput.value = '';
            removeImageFlag.value = '1';
        }
    </script>
@endpush
