<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f7f8] flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-sm border border-[#dbe0e6]">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-[#111418]">Selamat Datang</h1>
            <p class="text-sm text-gray-500">Masuk ke sistem inventaris gudang</p>
        </div>

        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold mb-1">Username</label>
                <input type="text" name="username" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" placeholder="Masukkan username Anda" required>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">Password</label>
                <input type="password" name="password" class="w-full p-3 border border-[#dbe0e6] rounded-lg focus:ring-2 focus:ring-[#136dec] outline-none" required>
            </div>
            <button type="submit" class="w-full bg-[#136dec] text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                Login
            </button>
        </form>

        {{-- <p class="text-center mt-6 text-sm text-gray-600">
            Belum punya akun? <a href="/register" class="text-[#136dec] font-bold">Daftar di sini</a>
        </p> --}}
    </div>
</body>
</html>