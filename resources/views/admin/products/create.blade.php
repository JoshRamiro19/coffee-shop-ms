@extends('layouts.admin')
@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Create a new menu item')

@section('header-actions')
<a href="{{ route('admin.products.index') }}" class="btn-secondary gap-2">
    <i class="fas fa-arrow-left"></i> Back
</a>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="card p-7">
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            @include('admin.products._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary flex-1 justify-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save mr-2"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
