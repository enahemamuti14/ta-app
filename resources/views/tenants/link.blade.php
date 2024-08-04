<!-- resources/views/tenant/link.blade.php -->
@extends('layouts.home')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <h1 class="text-2xl font-bold mb-8 text-center">Hubungkan Tenant dengan User</h1>
    <div class="container w-screen max-w-6xl">
        <form action="{{ route('tenant.link.form', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="tenant_id">Tenant</label>
                <select name="tenant_id" id="tenant_id" class="form-control">
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ $tenant->id == $user->tenant_id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <a href="{{ route('tenant.link.data') }}">
        <button class="relative z-10 bg-blue-500 text-white px-4 py-2 rounded ml-4">TenantUser</button>
    </a>
</div>
@endsection