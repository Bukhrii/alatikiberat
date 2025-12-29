<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f7f8] flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-sm border border-[#dbe0e6]">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-[#111418]">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500">Daftar untuk mengakses sistem inventaris gudang</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <form action="/register" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-1">Nama Lengkap</label>
                <input type="text" name="real_name" value="{{ old('real_name') }}" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" placeholder="Masukkan nama Anda" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" placeholder="Buat username unik" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Password</label>
                <input type="password" name="password" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" placeholder="Minimal 8 karakter" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" placeholder="Ulangi password" required>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">Jenis Akun</label>
                    <select name="role" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" required>
                        <option value="warehouse_admin">Admin Gudang</option>
                        <option value="procurement_manager">Manajer Pembelian</option>
                    </select>
            </div> 
    
            <button type="submit" class="w-full bg-[#136dec] text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition mt-2">
                Register
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-gray-600">
            Sudah punya akun? <a href="/login" class="text-[#136dec] font-bold">Masuk di sini</a>
        </p>
    </div>
</body>
</html>