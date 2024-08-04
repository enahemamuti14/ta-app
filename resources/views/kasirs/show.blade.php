<!-- resources/views/kasir/show.blade.php -->

@extends('layouts.kasir')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">{{ $tenant->namatenant }}</h1>
    <div class="bg-white shadow-md rounded-lg p-4">
        <img class="w-full h-48 object-cover mb-4" src="{{ $tenant->image_url }}" alt="{{ $tenant->namatenant }}">
        <p class="text-gray-600">{{ $tenant->description }}</p>
        <h2 class="text-xl font-semibold mt-4">Menu</h2>
        <ul class="list-disc list-inside">
            @foreach($tenant->menus as $menu)
            <li>{{ $menu->name }} - Rp{{ number_format($menu->price, 2) }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
