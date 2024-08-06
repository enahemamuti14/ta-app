@extends('layouts.home')

@section('content')
<div class="absolute bottom-0 top-28 left-44 ">
    <div class="w-screen max-w-6xl">

        <!-- Tombol Kembali -->
        <a href="{{ route('users.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Kembali</a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{--Form create  --}}
        <form action="{{ route('users.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama :</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email :</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select name="role" id="role" class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 {{ old('role') != 3 ? 'hidden' : '' }}">
                <label for="tenant_id" class="block text-gray-700 text-sm font-bold mb-2">Tenant:</label>
                <select name="tenant_id" id="tenant_id" class="mt-1 block w-full shadow appearance-none border rounded py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Tenant</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->tenant_id ? 'selected' : '' }}>{{ $tenant->namatenant }}</option>
                    @endforeach
                    <pre>{{ print_r($tenants) }}</pre>

                </select>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password :</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Tambah User
                </button>
            </div>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const roleSelect = document.getElementById('role');
                const tenantSelect = document.getElementById('tenant_id');
                
                function toggleTenantDropdown() {
                    if (roleSelect.value == '3') { // Misalnya ID 3 untuk tenant
                        tenantSelect.parentElement.classList.remove('hidden');
                    } else {
                        tenantSelect.parentElement.classList.add('hidden');
                    }
                }
            
                // Inisialisasi tampilan dropdown tenant
                toggleTenantDropdown();
            
                // Tambahkan event listener untuk role
                roleSelect.addEventListener('change', toggleTenantDropdown);
            });
            </script>
            
    </div>
</div>
@endsection
