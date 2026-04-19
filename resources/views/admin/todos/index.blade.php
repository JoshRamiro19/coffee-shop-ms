@extends('layouts.admin')
@section('title', 'To-Do')
@section('page-title', 'To-Do List')
@section('page-subtitle', 'Task management for the team')

@section('header-actions')
<button onclick="openModal()" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Task
</button>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-7">
    <div class="card p-4 text-center">
        <p class="font-display text-2xl text-gray-800">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Total Tasks</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-yellow-400">
        <p class="font-display text-2xl text-yellow-600">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Pending</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-blue-400">
        <p class="font-display text-2xl text-blue-600">{{ $stats['in_progress'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">In Progress</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-green-400">
        <p class="font-display text-2xl text-green-600">{{ $stats['completed'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">Completed</p>
    </div>
</div>

{{-- Filters --}}
<div class="mb-5 flex gap-3 flex-wrap">
    <form method="GET" class="flex gap-3 flex-wrap">
        <select name="status" class="form-input py-2 w-36" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['pending','in_progress','completed'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
        <select name="priority" class="form-input py-2 w-36" onchange="this.form.submit()">
            <option value="">All Priority</option>
            @foreach(['urgent','high','medium','low'] as $pr)
            <option value="{{ $pr }}" {{ request('priority') == $pr ? 'selected' : '' }}>{{ ucfirst($pr) }}</option>
            @endforeach
        </select>
        @if(request('status') || request('priority'))
        <a href="{{ route('admin.todos.index') }}" class="btn-secondary py-2">Clear</a>
        @endif
    </form>
</div>

{{-- Task list --}}
<div class="card overflow-hidden">
    <table>
        <thead><tr>
            <th>Task</th><th>Priority</th><th>Assigned To</th><th>Due Date</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($todos as $todo)
            <tr class="{{ $todo->status === 'completed' ? 'opacity-60' : '' }}">
                <td>
                    <div>
                        <p class="font-medium text-gray-800 {{ $todo->status === 'completed' ? 'line-through' : '' }}">{{ $todo->title }}</p>
                        @if($todo->description)
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-sm">{{ $todo->description }}</p>
                        @endif
                    </div>
                </td>
                <td>
                    @php $prColors = ['urgent'=>'badge-red','high'=>'badge-orange','medium'=>'badge-yellow','low'=>'badge-green']; @endphp
                    <span class="status-badge {{ $prColors[$todo->priority] ?? 'badge-gray' }} capitalize">{{ $todo->priority }}</span>
                </td>
                <td class="text-gray-500 text-sm">{{ $todo->assignee?->name ?? '—' }}</td>
                <td>
                    @if($todo->due_date)
                    <span class="text-sm {{ $todo->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                        {{ $todo->isOverdue() ? '⚠️ ' : '' }}{{ $todo->due_date->format('M d, Y') }}
                    </span>
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td>
                    <select onchange="updateStatus({{ $todo->id }}, this.value)"
                        class="text-xs border rounded-lg px-2 py-1.5 {{ $todo->status === 'completed' ? 'text-green-600 border-green-200' : ($todo->status === 'in_progress' ? 'text-blue-600 border-blue-200' : 'text-gray-500 border-gray-200') }}">
                        <option value="pending"     {{ $todo->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $todo->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ $todo->status === 'completed'   ? 'selected' : '' }}>Completed</option>
                    </select>
                </td>
                <td class="text-right">
                    <div class="flex gap-2 justify-end">
                        <button onclick="openEdit({{ json_encode($todo) }}, {{ json_encode($todo->assignee?->name) }})"
                            class="text-blue-500 text-sm px-2 py-1 border border-blue-200 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.todos.destroy', $todo) }}" method="POST"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger text-xs px-2 py-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-gray-400 py-10">
                <i class="fas fa-check-double text-2xl mb-2 block"></i>
                No tasks found. Add one!
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $todos->links() }}</div>
</div>

{{-- ADD MODAL --}}
<div class="modal-overlay" id="todoModal">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 animate-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-display text-lg text-gray-800" id="modalTitle">Add Task</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">×</button>
        </div>
        <form id="todoForm" method="POST" action="{{ route('admin.todos.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" id="todoTitle" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" id="todoDesc" rows="2" class="form-input resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" id="todoPriority" class="form-input">
                            @foreach(['low','medium','high','urgent'] as $p)
                            <option value="{{ $p }}">{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" id="todoStatus" class="form-input">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" id="todoAssigned" class="form-input">
                            <option value="">— Unassigned —</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="todoDue" class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 px-6 pb-6">
                <button type="button" onclick="closeModal()" class="btn-secondary flex-1 justify-center">Cancel</button>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save mr-2"></i> Save Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Add Task';
    document.getElementById('todoForm').action = '{{ route("admin.todos.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('todoTitle').value = '';
    document.getElementById('todoDesc').value = '';
    document.getElementById('todoPriority').value = 'medium';
    document.getElementById('todoStatus').value = 'pending';
    document.getElementById('todoAssigned').value = '';
    document.getElementById('todoDue').value = '';
    document.getElementById('todoModal').classList.add('show');
}

function openEdit(todo, assigneeName) {
    document.getElementById('modalTitle').textContent = 'Edit Task';
    document.getElementById('todoForm').action = `/admin/todos/${todo.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('todoTitle').value = todo.title;
    document.getElementById('todoDesc').value = todo.description || '';
    document.getElementById('todoPriority').value = todo.priority;
    document.getElementById('todoStatus').value = todo.status;
    document.getElementById('todoAssigned').value = todo.assigned_to || '';
    document.getElementById('todoDue').value = todo.due_date || '';
    document.getElementById('todoModal').classList.add('show');
}

function closeModal() {
    document.getElementById('todoModal').classList.remove('show');
}

function updateStatus(id, status) {
    fetch(`/admin/todos/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ status })
    }).then(() => location.reload());
}

document.getElementById('todoModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
