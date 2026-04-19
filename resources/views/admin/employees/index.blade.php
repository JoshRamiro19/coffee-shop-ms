@extends('layouts.admin')
@section('title', 'Employees')
@section('page-title', 'Employees')
@section('page-subtitle', 'Manage your team')

@section('header-actions')
<a href="{{ route('admin.employees.create') }}" class="btn-primary gap-2">
    <i class="fas fa-user-plus"></i> Add Employee
</a>
@endsection

@section('content')
<div class="card overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex gap-3 flex-wrap">
        <form method="GET" class="flex gap-3 flex-1 flex-wrap">
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}"
                class="form-input py-2 w-64">
            <select name="role" class="form-input py-2 w-36">
                <option value="">All Roles</option>
                @foreach(['barista','cashier','manager','admin'] as $r)
                <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary py-2">Filter</button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.employees.index') }}" class="btn-secondary py-2">Clear</a>
            @endif
        </form>
    </div>

    <table>
        <thead><tr>
            <th>Employee</th><th>Role</th><th>Shift</th><th>Phone</th><th>Hired</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($employees as $emp)
            <tr class="{{ $emp->trashed() ? 'opacity-60' : '' }}">
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-caramel to-mocha flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($emp->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $emp->name }}</p>
                            <p class="text-xs text-gray-400">{{ $emp->email }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    @php $roleColors = ['admin'=>'badge-red','manager'=>'badge-purple','barista'=>'badge-orange','cashier'=>'badge-blue']; @endphp
                    <span class="status-badge {{ $roleColors[$emp->role] ?? 'badge-gray' }} capitalize">{{ $emp->role }}</span>
                </td>
                <td class="capitalize text-gray-600">{{ str_replace('_', ' ', $emp->shift) }}</td>
                <td class="text-gray-500">{{ $emp->phone ?? '—' }}</td>
                <td class="text-gray-400 text-sm">{{ $emp->hired_at?->format('M d, Y') ?? '—' }}</td>
                <td>
                    @if($emp->trashed())
                        <span class="status-badge badge-red">Deleted</span>
                    @elseif($emp->is_active)
                        <span class="status-badge badge-green">Active</span>
                    @else
                        <span class="status-badge badge-gray">Inactive</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($emp->trashed())
                    <form action="{{ route('admin.employees.restore', $emp->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="text-green-500 text-sm px-2 py-1 border border-green-200 rounded-lg hover:bg-green-50">
                            <i class="fas fa-undo mr-1"></i> Restore
                        </button>
                    </form>
                    @else
                    <div class="flex gap-2 justify-end">
                        <a href="{{ route('admin.employees.edit', $emp) }}" class="text-blue-500 text-sm px-2 py-1 border border-blue-200 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.employees.destroy', $emp) }}" method="POST"
                              onsubmit="return confirm('Remove {{ $emp->name }}? They will be soft-deleted.')">
                            @csrf @method('DELETE')
                            <button class="btn-danger text-xs px-2 py-1">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-gray-400 py-10">No employees found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $employees->links() }}</div>
</div>
@endsection
