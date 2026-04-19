{{-- Shared form partial for create/edit --}}
@php $p = $product ?? null; @endphp

<div class="space-y-5">
    <div>
        <label class="form-label">Product Name <span class="text-red-400">*</span></label>
        <input type="text" name="name" value="{{ old('name', $p?->name) }}"
            class="form-input" placeholder="e.g. Caramel Macchiato" required>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Category <span class="text-red-400">*</span></label>
            <select name="category" class="form-input" required>
                @foreach(['beverage','food','snack','merchandise'] as $cat)
                <option value="{{ $cat }}" {{ old('category', $p?->category) === $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Price (₱) <span class="text-red-400">*</span></label>
            <input type="number" name="price" value="{{ old('price', $p?->price) }}"
                class="form-input" placeholder="0.00" step="0.01" min="0" required>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Stock Qty <span class="text-red-400">*</span></label>
            <input type="number" name="stock" value="{{ old('stock', $p?->stock ?? 0) }}"
                class="form-input" min="0" required>
        </div>
        <div>
            <label class="form-label">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $p?->low_stock_threshold ?? 10) }}"
                class="form-input" min="0">
            <p class="text-xs text-gray-400 mt-1">Alert when stock falls below this</p>
        </div>
    </div>

    <div>
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input resize-none"
            placeholder="Optional description...">{{ old('description', $p?->description) }}</textarea>
    </div>

    <div class="flex items-center gap-3 bg-foam p-4 rounded-xl">
        <input type="checkbox" name="is_available" id="is_available" value="1"
            {{ old('is_available', $p?->is_available ?? true) ? 'checked' : '' }}
            class="w-4 h-4 accent-caramel">
        <div>
            <label for="is_available" class="text-sm font-medium text-gray-700 cursor-pointer">Available for Order</label>
            <p class="text-xs text-gray-400">Uncheck to temporarily hide from order menu</p>
        </div>
    </div>
</div>
