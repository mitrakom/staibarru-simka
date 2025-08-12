{{-- filepath: c:\laragon\www\sim-ijazah\resources\views\auth\login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKA - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
        /* Pastikan tombol login terlihat dengan styling tambahan */
        .btn-login {
            background-color: #f59e0b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 600;
            width: 100%;
            text-align: center;
            display: block;
            transition: background-color 0.3s;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #d97706;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header dengan navigasi ke halaman utama -->
    <header class="bg-white shadow-sm py-3">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="/" class="flex items-center space-x-2 text-blue-700 hover:text-blue-900 transition">
                <i class="fas fa-home text-lg"></i>
                <span class="font-medium">Kembali ke Beranda</span>
            </a>
            <div class="text-sm text-gray-600">
                STAI-Al Gazali Barru
            </div>
        </div>
    </header>

    <!-- Main content -->
    <div class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">SIMKA</h1>
                <p class="text-gray-600">Silahkan login untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input id="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" required autocomplete="current-password">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="ml-2 block text-sm text-gray-700" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    {{-- <div>
                        <a href="/lupa-password" class="text-sm text-blue-600 hover:text-blue-800 transition">
                            Lupa Password?
                        </a>
                    </div> --}}
                </div>

                <div class="flex items-center justify-center">
                   <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </button>
                </div>
            </form>
            
            <!-- Link ke halaman utama di bagian bawah -->
            <div class="mt-6 text-center">
                <a href="/" class="text-blue-600 hover:text-blue-800 transition flex items-center justify-center space-x-1">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Halaman Utama</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer dengan informasi -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-600">
        <div class="container mx-auto px-4">
            <p>&copy; {{ date('Y') }} SIMKA - STAI-Al Gazali Barru</p>
        </div>
    </footer>
</body>
</html>