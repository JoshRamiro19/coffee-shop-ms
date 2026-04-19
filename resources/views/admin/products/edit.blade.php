@extends('layouts.admin')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', 'Update product details')

@section('header-actions')
<a href="{{ route('admin.products.index') }}" class="btn-secondary gap-2">
    <i class="fas fa-arrow-left"></i> Back
</a>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="card p-7">
        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.products._form')
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary flex-1 justify-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
