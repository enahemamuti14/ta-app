@extends('layouts.home')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
    <h1 class="text-2xl font-bold mb-4 text-center">DATA USER</h1>
    <div class="flex mb-4">
     <!-- Pencarian Data User -->
        <div class="w-full flex-1">
            <input type="text" id="searchUser" placeholder="Search users..." class="w-11/12 px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <a href="{{ route('users.create')}}" class="flex-1">
            <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah User</button>
        </a>
    </div>

    <!-- Contoh tabel user -->
    <div class="bg-white shadow rounded-md px-2 py-4 flex-1">
        <table class="w-screen max-w-5xl bg-white text-center">
                <thead>
                    <tr class="text-center">
                        <th class="py-2 border-b">ID</th>
                        <th class="py-2 border-b">Name</th>
                        <th class="py-2 border-b">Email</th>
                        <th class="py-2 border-b">Role</th>
                        <th class="py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="items-center justify-center">
                            <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b">
                                @foreach ($user->roles as $role)
                                    <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-sm">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="py-4 px-4 border-b flex gap-4 items-center justify-center">
                                <a href="{{ route('users.edit', $user->id) }}">
                                    <button class="bg-blue-500 text-white px-4 py-1 rounded">Edit</button>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>
@endsection