@extends('layouts.admin')
@section('title', 'Add Employee')
@section('page-title', 'Add Employee')
@section('page-subtitle', 'Add a new team member')

@section('header-actions')
<a href="{{ route('admin.employees.index') }}" class="btn-secondary gap-2">
    <i class="fas fa-arrow-left"></i> Back
</a>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="card p-7">
        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf
            @include('admin.employees._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.employees.index') }}" class="btn-secondary flex-1 justify-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Add Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
