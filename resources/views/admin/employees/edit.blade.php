@extends('layouts.admin')
@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')
@section('page-subtitle', 'Update team member details')

@section('header-actions')
<a href="{{ route('admin.employees.index') }}" class="btn-secondary gap-2">
    <i class="fas fa-arrow-left"></i> Back
</a>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="card p-7">
        <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.employees._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.employees.index') }}" class="btn-secondary flex-1 justify-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
