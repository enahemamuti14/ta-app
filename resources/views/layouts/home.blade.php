<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MUREY CASHIER SYSTEM</title>
    @vite('resources/css/app.css')
    
</head>
<body style="background-color: #DADFE5">
    <div class="relative min-h-screen ">
        <header class="flex bg-white pl-40 pr-20 py-6 justify-center items-center ">
            <div class="flex items-center mr-auto ml-6">
                <!-- Logo -->
                <a href="{{ route('kasir.index') }}" class="text-xl font-bold">    
                    {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-12 h-auto"> --}}
                    <h1 class="">MUREY CASHIER SYSTEM</h1>
                </a>
            </div>

            {{-- <!-- Search Bar -->
            <div class="flex-1 mx-4">
                <form action="">
                <input type="text" placeholder="Search..." class="w-10/12 px-4 py-2 rounded border border-blue-900 text-blue-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </form>
            </div> --}}

            <!-- Profile Menu -->
            <div class="flex ml-72 gap-2 justify-center items-center">
                <button class="flex items-center ">
                    <img src="{{ asset('img/profil.png') }}" alt="Profile" class="w-10 h-10 rounded-full">
                </button>
                <div class=" text-gray-800 text-sm">
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="-mt-1">{{ $user->roles->pluck('name')->implode(', ') }}</p>
                </div>
            </div> 
        </header>
    </div>
    <div class="flex absolute bottom-0 top-0">
        <!-- Sidebar -->
        <aside class="fixed bg-gray-800 text-white min-h-screen">
            <div class="p-4 flex flex-col items-center justify-center">
                <!-- Logo -->
                <a href="{{ route('admin.dashboard') }}" class="mb-4 flex flex-col items-center">
                    <img src="{{ asset('img/home.png') }}" alt="Logo" class="w-12 h-auto">
                </a>

                <!-- Links -->
                <nav class="flex flex-col items-center w-full px-0">
                    <a href="{{ route('penjualan') }}" class="flex flex-col items-center px-4 py-4 text-gray-300 hover:bg-gray-700">
                        <div class="flex items-center justify-center mb-1">
                            <img src="{{ asset('img/laporanpembayaran.png') }}" alt="Home" class="w-6 h-6">
                        </div>
                        <p>Laporan</p>
                        <p>Penjualan</p>
                    </a>
                    <a href="{{ route('pembayaran') }}" class="flex flex-col w-full items-center px-0 py-4 text-gray-300 hover:bg-gray-700">
                        <div class="flex items-center justify-center mb-1">
                            <img src="{{ asset('img/pembayaran.png') }}" alt="Users" class="w-8 h-8">
                        </div>
                        Pembayaran
                    </a>
                    <a href="{{ route('tenants.index') }}" class="flex flex-col w-full items-center px-0 py-4 text-gray-300 hover:bg-gray-700">
                        <div class="flex items-center justify-center mb-1">
                            <img src="{{ asset('img/tenant.png') }}" alt="Settings" class="w-6 h-6">
                        </div>
                        Tenant
                    </a>    
                    <a href="{{ route('users.index') }}" class="flex flex-col w-full items-center px-0 py-4 text-gray-300 hover:bg-gray-700">
                        <div class="flex items-center justify-center mb-1">
                            <img src="{{ asset('img/user.png') }}" alt="Settings" class="w-8 h-8">
                        </div>
                        User
                    </a>    
                </nav>
                <form action="{{ route('logout') }}" method="POST" class="flex flex-col items-center px-0 py-36">
                    @csrf
                    <button type="submit">
                        <img src="{{ asset('img/logout.png') }}" alt="Logout" style="width: 30px; height: 30px;">
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-6 overflow-auto">
            @yield('content')
        </main>
        <main class="flex-1 p-6 overflow-auto">
            @yield('content2')
        </main>
        <main class="flex-1 p-6 overflow-auto">
            @yield('content3')
        </main>
        <main class="flex-1 p-6 overflow-auto">
            @yield('content4')
        </main>
        <main class="flex-1 p-6 overflow-auto">
            @yield('content5')
        </main>
    </div>
</body>
</html>