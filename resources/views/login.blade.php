<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <title>MUREY CASHIER SYSTEM</title>
</head>
<body style="background-color: #0D1321" class="flex justify-center items-center h-screen">
  {{-- <div class="container py-10 px-8 bg-white w-full max-w-lg rounded-lg shadow-md bg-blend-normal mb-2 flex flex-col justify-center">
    <div class="tittle">
      <h2 class="text-gray-500 text-4xl font-bold mb-2 text-center">MUREY SYSTEM</h2>
      <p class="text-gray-400 mb-6 text-center">Silakan Login Terlebih Dahulu Menggunakan Akun Anda</p>
    </div>

    @if ($errors->any())
      <div class="mb-4 text-red-500">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif


    <div class="form">
      <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-400 font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full p-2 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-400 font-semibold mb-2">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password" class="w-full p-2 border border-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <p class="text-gray-400 mb-6 text-center">
          Login Sebagai 
          <a href="/home" class="text-blue-900 font-bold underline">Tenant</a>
          atau
          <a href="{{ route('adminLogin') }}"  class="text-blue-900 font-bold underline"> Admin</a>
        </p>
        <button type="submit" class="w-full bg-blue-900 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            Login
        </button>
      </form>
    </div>
  </div> --}}
  <div class="container mx-auto">
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-center mt-4 text-gray-700">CASHIER SYSTEM</h2>
        <p class="text-sm text-center mb-6 mt-0 text-gray-400">Silahkan Masukkan akun anda</p>
        <form method="POST" action="{{ route('login.submit') }}" class="">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 p-2 block w-full border rounded-md" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="mt-1 p-2 block w-full border rounded-md" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md">Login</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>