<!-- resources/views/tenant/index.blade.php -->
@extends('layouts.home')

@section('content')
<div class="absolute bottom-0 top-28 left-44 mt-5">
<div class="container">
    <h1>Daftar Tenant</h1>
    @if($tenant)
    <h2>{{ $tenant->name }}</h2>
    <ul>
        @foreach($tenant->users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>
@else
    <p>Tidak ada tenant yang terkait dengan pengguna ini.</p>
@endif  
</div>
@endsection
