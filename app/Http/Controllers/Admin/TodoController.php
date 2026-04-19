<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\Employee;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::with('assignee')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date')
            ->paginate(20);

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total'       => Todo::count(),
            'pending'     => Todo::where('status', 'pending')->count(),
            'in_progress' => Todo::where('status', 'in_progress')->count(),
            'completed'   => Todo::where('status', 'completed')->count(),
        ];

        return view('admin.todos.index', compact('todos', 'employees', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'status'      => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date'    => 'nullable|date',
        ]);

        Todo::create($data);

        return redirect()->route('admin.todos.index')->with('success', 'Task added!');
    }

    public function update(Request $request, Todo $todo)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'status'      => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date'    => 'nullable|date',
        ]);

        $todo->update($data);

        return redirect()->route('admin.todos.index')->with('success', 'Task updated!');
    }

    public function updateStatus(Request $request, Todo $todo)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,completed']);
        $todo->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('admin.todos.index')->with('success', 'Task deleted.');
    }
}
