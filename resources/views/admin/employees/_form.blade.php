{{-- resources/views/admin/employees/_form.blade.php --}}
@php $e = $employee ?? null; @endphp
<div class="space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Full Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $e?->name) }}"
                class="form-input" required placeholder="e.g. Maria Santos">
        </div>
        <div>
            <label class="form-label">Email <span class="text-red-400">*</span></label>
            <input type="email" name="email" value="{{ old('email', $e?->email) }}"
                class="form-input" required placeholder="maria@brewhouse.com">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $e?->phone) }}"
                class="form-input" placeholder="+63 912 345 6789">
        </div>
        <div>
            <label class="form-label">Role <span class="text-red-400">*</span></label>
            <select name="role" class="form-input" required>
                @foreach(['barista','cashier','manager','admin'] as $r)
                <option value="{{ $r }}" {{ old('role', $e?->role) === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Shift <span class="text-red-400">*</span></label>
            <select name="shift" class="form-input" required>
                @foreach(['morning','afternoon','evening','full_day'] as $s)
                <option value="{{ $s }}" {{ old('shift', $e?->shift) === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Monthly Salary (₱)</label>
            <input type="number" name="salary" value="{{ old('salary', $e?->salary) }}"
                class="form-input" placeholder="0.00" step="0.01" min="0">
        </div>
    </div>

    <div>
        <label class="form-label">Hire Date</label>
        <input type="date" name="hired_at" value="{{ old('hired_at', $e?->hired_at?->format('Y-m-d')) }}"
            class="form-input">
    </div>

    <div class="flex items-center gap-3 bg-foam p-4 rounded-xl">
        <input type="checkbox" name="is_active" id="is_active" value="1"
            {{ old('is_active', $e?->is_active ?? true) ? 'checked' : '' }}
            class="w-4 h-4 accent-caramel">
        <div>
            <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Active Employee</label>
            <p class="text-xs text-gray-400">Uncheck to mark as inactive (still on record)</p>
        </div>
    </div>
</div>
