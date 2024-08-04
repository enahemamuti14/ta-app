@extends('layouts.home')

@section('content5')
<div class="absolute bottom-0 top-28 left-44 ">
    <div class="w-screen max-w-6xl mx-auto mt-5 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Ubah Metode Pembayaran</h1>
        <form>
            <div class="mb-4">
                <label for="current-method" class="block text-gray-700 font-semibold mb-2">Metode Pembayaran Saat Ini:</label>
                <div id="current-method" class="bg-gray-200 p-3 rounded-md">Tunai</div>
            </div>

            <div class="mb-4">
                <label for="new-method" class="block text-gray-700 font-semibold mb-2">Metode Pembayaran Baru: </label>
                <select id="new-method" name="new-method" class="block w-full bg-white border border-gray-300 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{-- <option value="credit-card">Kartu Kredit</option>
                    <option value="debit-card">Kartu Debit</option>
                    <option value="paypal">Qris</option>
                    <option value="bank-transfer">Transfer Bank</option> --}}
                    <option value="bank-transfer">Tunai</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white font-semibold p-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Simpan</button>
        </form>
    </div>
</div>
@endsection