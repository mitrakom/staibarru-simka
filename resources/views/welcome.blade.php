{{-- filepath: c:\laragon\www\sim-ijazah\resources\views\welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Manajemen Akademik (SIMKA) STAI Al-Gazali Barru - Platform digital terintegrasi untuk pengelolaan akademik mahasiswa dan dosen">
    <title>SIMKA | STAI Al-Gazali Barru</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @media (max-width: 768px) {
            .timeline-mobile {
                flex-direction: column;
                gap: 1rem;
            }
            .timeline-mobile .absolute {
                display: none;
            }
        }
    </style>

</head>
<body class="bg-gray-50 text-gray-800">
    <div class="min-h-screen flex flex-col justify-between">
        <!-- Header -->
        <header class="bg-white shadow-md sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('/images/logo.jpeg') }}" alt="Logo STAI Al-Gazali Barru" class="h-12 w-auto">
                    {{-- <img src="https://via.placeholder.com/40x40.png?text=STAI" alt="Logo STAI Al-Gazali Barru" class="h-12 w-auto"> --}}
                    <div>
                        <h1 class="text-2xl font-bold text-green-700">SIMKA</h1>
                        <p class="text-xs text-gray-500">STAI Al-Gazali Barru</p>
                    </div>
                </div>
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Selamat datang, {{ Auth::user()->name }}</span>
                        <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-green-600 text-white px-4 py-2 text-sm rounded hover:bg-green-700 transition">
                            Dashboard
                        </a>
                    </div>
                @else
                    <a href="/login" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition flex items-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow">
    <div class="bg-gradient-to-r from-green-700 to-emerald-800 text-white py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6">
            <div class="md:w-1/2 text-center md:text-left z-10">
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight drop-shadow-lg">
                    Sistem Informasi<br class="hidden md:block"> Manajemen Akademik
                </h2>
                <p class="text-lg md:text-xl mb-10 opacity-90">
                    Platform digital terintegrasi untuk pengelolaan akademik yang efisien dan transparan di Sekolah Tinggi Agama Islam Al-Gazali Barru.
                </p>
                @auth
                    <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-white text-green-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold inline-block">
                        Akses Dashboard
                    </a>
                @else
                    <a href="/login" class="bg-white text-green-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold inline-block">
                        Masuk ke Aplikasi
                    </a>
                @endauth
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0 flex justify-center z-10">
                <!-- Ilustrasi SVG modern, bebas digunakan -->
                <img src="{{ asset('images/Certification.gif') }}" alt="Ilustrasi Akademik" class="w-80 h-auto drop-shadow-xl rounded-lg bg-white bg-opacity-20 p-2">
                {{-- Alternatif SVG inline jika ingin tanpa internet:
                <svg ...> ... </svg>
                --}}
            </div>
        </div>
        <!-- Decorative SVG background -->
        <svg class="absolute top-0 right-0 w-96 h-96 opacity-30 pointer-events-none" viewBox="0 0 400 400" fill="none">
            <circle cx="200" cy="200" r="200" fill="#fff" fill-opacity="0.07"/>
        </svg>
    </div>

            <!-- Section: Portal Akademik -->
            <section class="py-16 px-6 bg-gradient-to-br from-green-50 to-emerald-100">
                <div class="max-w-6xl mx-auto">
                    <!-- Header Section -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-full mb-4">
                            <i class="fas fa-graduation-cap text-white text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold mb-4 text-gray-800">Portal Akademik</h3>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Akses berbagai layanan akademik melalui portal yang disesuaikan dengan peran Anda di STAI Al-Gazali Barru.
                        </p>
                    </div>

                    <!-- Portal Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white p-8 rounded-xl shadow-lg border border-green-100 hover:shadow-xl transition-all duration-300">
                            <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center mb-6 mx-auto">
                                <i class="fas fa-user-tie text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-green-700 text-center">Portal Admin</h4>
                            <ul class="text-gray-600 space-y-2 mb-6">
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Manajemen Data Mahasiswa</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Pengaturan Kurikulum</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Laporan Akademik</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Manajemen User</li>
                            </ul>
                        </div>

                        <div class="bg-white p-8 rounded-xl shadow-lg border border-green-100 hover:shadow-xl transition-all duration-300">
                            <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center mb-6 mx-auto">
                                <i class="fas fa-chalkboard-teacher text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-green-700 text-center">Portal Dosen</h4>
                            <ul class="text-gray-600 space-y-2 mb-6">
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Input Nilai Mahasiswa</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Manajemen Kelas</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Monitoring Kehadiran</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Bimbingan Akademik</li>
                            </ul>
                        </div>

                        <div class="bg-white p-8 rounded-xl shadow-lg border border-green-100 hover:shadow-xl transition-all duration-300">
                            <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center mb-6 mx-auto">
                                <i class="fas fa-user-graduate text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold mb-4 text-green-700 text-center">Portal Mahasiswa</h4>
                            <ul class="text-gray-600 space-y-2 mb-6">
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> KRS Online</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Cek Nilai & Transkrip</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Jadwal Kuliah</li>
                                <li class="flex items-center"><i class="fas fa-check-circle text-green-500 mr-2"></i> Status Pembayaran</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Informasi Fitur -->
            <section class="py-16 px-6 bg-white">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-12 text-gray-800">Fitur Unggulan SIMKA</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="bg-green-50 p-6 rounded-lg border border-green-100 hover:shadow-md transition">
                            <div class="bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-green-700 text-center">KRS Online</h4>
                            <p class="text-gray-600 text-center">
                                Sistem Kartu Rencana Studi online yang memudahkan mahasiswa dalam memilih mata kuliah setiap semester dengan validasi otomatis kurikulum dan prasyarat.
                            </p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg border border-green-100 hover:shadow-md transition">
                            <div class="bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-green-700 text-center">Manajemen Nilai</h4>
                            <p class="text-gray-600 text-center">
                                Platform terintegrasi untuk input, monitoring, dan pelaporan nilai mahasiswa. Dosen dapat mengelola nilai dengan mudah dan mahasiswa dapat mengakses hasil studi real-time.
                            </p>
                        </div>
                        <div class="bg-green-50 p-6 rounded-lg border border-green-100 hover:shadow-md transition">
                            <div class="bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-green-700 text-center">Multi-Portal Terintegrasi</h4>
                            <p class="text-gray-600 text-center">
                                Satu sistem yang melayani berbagai pengguna: Admin untuk manajemen, Dosen untuk akademik, dan Mahasiswa untuk layanan mandiri dengan antarmuka yang user-friendly.
                            </p>
                        </div>
                    </div>
                </div>
            </section>



            <!-- Statistik & Informasi -->
            <section class="py-16 px-6 bg-green-700 text-white">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-12">Statistik SIMKA</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-4xl font-bold mb-2">1000+</div>
                            <p class="opacity-80">Mahasiswa Aktif</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">50+</div>
                            <p class="opacity-80">Dosen Pengampu</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">3</div>
                            <p class="opacity-80">Portal Terintegrasi</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">24/7</div>
                            <p class="opacity-80">Akses Online</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonial / Quotes -->
            <section class="py-16 px-6 bg-white">
                <div class="max-w-4xl mx-auto text-center">
                    <h3 class="text-2xl font-bold mb-10 text-gray-800">Testimoni Pengguna</h3>
                    <div class="bg-gray-50 p-8 rounded-lg shadow-sm border border-gray-100">
                        <p class="text-gray-700 text-lg italic mb-6">
                            "SIMKA telah mengubah cara kami mengelola proses akademik di STAI Al-Gazali Barru. Dari KRS online hingga manajemen nilai, semua menjadi lebih efisien dan transparan. Mahasiswa dapat mengakses informasi akademik kapan saja dengan mudah."
                        </p>
                        <div>
                            <p class="font-semibold text-green-700">Tim Akademik</p>
                            <p class="text-sm text-gray-500">STAI Al-Gazali Barru</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section class="py-16 px-6 bg-gray-50">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-10 text-gray-800">Pertanyaan Umum</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-green-700 mb-2">Bagaimana cara mengakses KRS Online?</h4>
                            <p class="text-gray-600">Mahasiswa dapat login ke portal mahasiswa menggunakan NIM dan password yang telah diberikan. Fitur KRS akan tersedia pada masa periode pengisian KRS setiap semester.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-green-700 mb-2">Kapan nilai mahasiswa dapat dilihat?</h4>
                            <p class="text-gray-600">Nilai akan otomatis tersedia di portal mahasiswa setelah dosen menginput dan konfirmasi nilai. Mahasiswa dapat melihat nilai per mata kuliah dan transkrip akademik secara real-time.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-green-700 mb-2">Apa saja fitur yang tersedia untuk dosen?</h4>
                            <p class="text-gray-600">Portal dosen menyediakan fitur input nilai, manajemen kelas, monitoring kehadiran mahasiswa, dan akses ke data akademik mahasiswa yang diampu.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="py-16 px-6 bg-gradient-to-r from-green-600 to-emerald-700 text-white text-center">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-3xl font-bold mb-6">Siap Menggunakan SIMKA?</h3>
                    <p class="text-lg opacity-90 mb-8">
                        Nikmati kemudahan dan efisiensi dalam pengelolaan akademik dengan sistem terintegrasi STAI Al-Gazali Barru.
                    </p>
                    @auth
                        <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-white text-green-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold">
                            Akses Dashboard Anda
                        </a>
                    @else
                        <a href="/login" class="bg-white text-green-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold">
                            Login Sekarang
                        </a>
                    @endauth
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-10">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-8">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Tentang SIMKA</h4>
                        <p class="text-gray-400 text-sm">
                            SIMKA adalah sistem informasi manajemen akademik yang dikembangkan untuk memudahkan dan mengoptimalkan proses akademik di Sekolah Tinggi Agama Islam Al-Gazali Barru.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                        <ul class="text-gray-400 text-sm space-y-2">
                            <li class="flex items-center"><i class="fas fa-phone mr-2"></i> (0427) 21234</li>
                            <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i>Jl. H. M. Yunus No. 57, Barru, Sulawesi Selatan</li>
                            <li class="flex items-center"><i class="fas fa-globe mr-2"></i>https://stai-algazali-barru.ac.id</li>
                            <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> info@stai-algazali-barru.ac.id</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                        <ul class="text-gray-400 text-sm space-y-2">
                            <li><a href="#" class="hover:text-white">Panduan Pengguna</a></li>
                            <li><a href="#" class="hover:text-white">FAQ</a></li>
                            <li><a href="#" class="hover:text-white">Hubungi Administrator</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} SIMKA - STAI Al-Gazali Barru. Dikembangkan oleh CV. Mitranet Komputindo.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>