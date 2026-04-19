<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::withTrashed()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->orderBy('name')
            ->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:employees,email',
            'phone'     => 'nullable|string|max:20',
            'role'      => 'required|in:barista,cashier,manager,admin',
            'shift'     => 'required|in:morning,afternoon,evening,full_day',
            'salary'    => 'nullable|numeric|min:0',
            'hired_at'  => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Employee::create($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee added successfully!');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => "required|email|unique:employees,email,{$employee->id}",
            'phone'     => 'nullable|string|max:20',
            'role'      => 'required|in:barista,cashier,manager,admin',
            'shift'     => 'required|in:morning,afternoon,evening,full_day',
            'salary'    => 'nullable|numeric|min:0',
            'hired_at'  => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $employee->update($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete(); // Soft delete
        return redirect()->route('admin.employees.index')->with('success', 'Employee removed.');
    }

    public function restore($id)
    {
        Employee::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.employees.index')->with('success', 'Employee restored.');
    }
}
