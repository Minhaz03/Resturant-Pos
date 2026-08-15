@extends('layouts.app')
@section('title', 'Add Menu Item')

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
                <h4 class="fw-bold mb-0" style="color:var(--secondary)">Add Menu Item</h4>
                <p class="text-muted small mb-0">Create a new dish or beverage for your POS menu</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('menu.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
            <button type="submit" form="createMenuItemForm" class="btn btn-primary px-4"><i class="bi bi-check2 me-1"></i>Save Menu Item</button>
        </div>
    </div>

    <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data" id="createMenuItemForm">
        @csrf
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
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Grilled Chicken Burger" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                <textarea name="description" class="form-control" rows="3" placeholder="Provide a tasty description for cashiers or digital menus...">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">SKU</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-barcode"></i></span>
                                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="e.g. BURG-001">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Barcode</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-qr-code-scan"></i></span>
                                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}" placeholder="Scan or enter barcode...">
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
                                    <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00" min="0" required>
                                </div>
                                @error('price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Cost Price (৳)</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}" placeholder="0.00" min="0">
                                </div>
                                <small class="text-muted" style="font-size:0.75rem">Used for profit margin calculation</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="discount" class="form-control" value="{{ old('discount', 0) }}" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Tax Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate', 5) }}" min="0" max="100">
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
                                    <tr class="no-ingredients-row">
                                        <td colspan="3" class="text-center text-muted py-4 small">
                                            <i class="bi bi-info-circle fs-6 d-block mb-1 text-secondary"></i>
                                            No ingredients attached yet. Click <strong>"Add Ingredient"</strong> to link stock for automatic deduction upon POS order.
                                        </td>
                                    </tr>
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
                        <input type="file" id="menuItemImage" name="image" accept="image/*">
                        <div class="text-muted mt-2 d-flex align-items-center gap-1" style="font-size:0.75rem">
                            <i class="bi bi-info-circle"></i> Max file size: 4MB. (JPG, PNG, WebP)
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
                                <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', 15) }}" min="1">
                                <span class="input-group-text">min</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold small">Serving Unit</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-grid"></i></span>
                                <input type="text" name="unit" class="form-control" value="{{ old('unit', 'plate') }}" placeholder="e.g. plate, bowl, portion">
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
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="avail" {{ old('is_available', 1) ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold small">Featured Item</div>
                                <div class="text-muted" style="font-size:0.72rem">Highlight on top of POS menu</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="feat" {{ old('is_featured') ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div>
                                <div class="fw-semibold small">Active Status</div>
                                <div class="text-muted" style="font-size:0.72rem">Enable or disable item</div>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="stat" {{ old('status', 1) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card form-section-card bg-light border-0">
                    <div class="card-body d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Save Menu Item
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
        let ingredientIndex = 0;
        const inventoryItems = @json($inventoryItems);

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

        // Initialize FilePond Dropzone with custom styling
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        FilePond.create(document.querySelector('#menuItemImage'), {
            allowMultiple: false,
            maxFileSize: '4MB',
            acceptedFileTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            labelIdle: `
                <div class="py-3 text-center">
                    <div class="mb-2">
                        <i class="bi bi-cloud-arrow-up-fill text-primary" style="font-size: 2.2rem;"></i>
                    </div>
                    <div style="font-weight:600;color:#334155;font-size:0.9rem">Drag & Drop Dish Image</div>
                    <div style="color:#64748b;font-size:0.8rem" class="mt-1">or <span class="filepond--label-action">Browse from device</span></div>
                </div>
            `,
            imagePreviewHeight: 180,
            server: null,
            instantUpload: false,
            storeAsFile: true,
        });
    </script>
@endpush
