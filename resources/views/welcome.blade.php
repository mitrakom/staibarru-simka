{{-- filepath: c:\laragon\www\sim-ijazah\resources\views\welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Manajemen Ijazah Universitas Indonesia Timur - Pengelolaan dan pelacakan proses penerbitan ijazah secara terintegrasi">
    <title>SIM-Ijazah | Universitas Indonesia Timur</title>
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
                    <img src="{{ asset('/images/logo.jpeg') }}" alt="Logo Universitas Indonesia Timur" class="h-12 w-auto">
                    {{-- <img src="https://via.placeholder.com/40x40.png?text=UIT" alt="Logo Universitas Indonesia Timur" class="h-12 w-auto"> --}}
                    <div>
                        <h1 class="text-2xl font-bold text-blue-700">SIM-Ijazah</h1>
                        <p class="text-xs text-gray-500">Universitas Indonesia Timur</p>
                    </div>
                </div>
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Selamat datang, {{ Auth::user()->name }}</span>
                        <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700 transition">
                            Dashboard
                        </a>
                    </div>
                @else
                    <a href="/login" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow">
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-6">
            <div class="md:w-1/2 text-center md:text-left z-10">
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight drop-shadow-lg">
                    Sistem Informasi<br class="hidden md:block"> Manajemen Ijazah
                </h2>
                <p class="text-lg md:text-xl mb-10 opacity-90">
                    Solusi digital terpadu untuk pengelolaan dan pelacakan proses penerbitan ijazah secara efisien, transparan, dan akuntabel di Universitas Indonesia Timur.
                </p>
                @auth
                    <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-white text-blue-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold inline-block">
                        Akses Dashboard
                    </a>
                @else
                    <a href="/login" class="bg-white text-blue-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold inline-block">
                        Masuk ke Aplikasi
                    </a>
                @endauth
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0 flex justify-center z-10">
                <!-- Ilustrasi SVG modern, bebas digunakan -->
                <img src="{{ asset('images/Certification.gif') }}" alt="Ilustrasi Ijazah" class="w-80 h-auto drop-shadow-xl rounded-lg bg-white bg-opacity-20 p-2">
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

            <!-- Section: Cek Status Ijazah -->
            <section class="py-16 px-6 bg-gradient-to-br from-blue-50 to-indigo-100">
                <div class="max-w-6xl mx-auto">
                    <!-- Header Section -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <h3 class="text-3xl font-bold mb-4 text-gray-800">Cek Status Ijazah</h3>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                            Pantau progres penerbitan ijazah Anda secara real-time. Masukkan NIM untuk melihat status terkini dan timeline proses verifikasi.
                        </p>
                    </div>

                </div>
            </section>

            <!-- Informasi Fitur -->
            <section class="py-16 px-6 bg-white">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-12 text-gray-800">Fitur Unggulan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 hover:shadow-md transition">
                            <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-search-location text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-blue-700 text-center">Tracking Real-Time</h4>
                            <p class="text-gray-600 text-center">
                                Pantau status dan lokasi dokumen ijazah secara real-time. Mahasiswa dan operator dapat melihat posisi terakhir dan progres dokumen dengan detail timestamp.
                            </p>
                        </div>
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 hover:shadow-md transition">
                            <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-check-double text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-blue-700 text-center">Verifikasi Multi-Unit</h4>
                            <p class="text-gray-600 text-center">
                                Sistem verifikasi bertingkat yang melibatkan seluruh unit kerja terkait. Prodi, Rektorat, BAUK, BAAK, dan Yayasan dapat memvalidasi dokumen sesuai dengan kewenangan masing-masing.
                            </p>
                        </div>
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 hover:shadow-md transition">
                            <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-3 text-blue-700 text-center">Dokumentasi Digital</h4>
                            <p class="text-gray-600 text-center">
                                Seluruh dokumen dan catatan terkait ijazah disimpan dalam sistem digital yang aman. Kemudahan akses dan pencarian data historis dengan keamanan tingkat tinggi.
                            </p>
                        </div>
                    </div>
                </div>
            </section>



            <!-- Statistik & Informasi -->
            <section class="py-16 px-6 bg-blue-700 text-white">
                <div class="max-w-6xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-12">Statistik SIM-Ijazah</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                        <div>
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <p class="opacity-80">Ijazah Terbit per Tahun</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">5</div>
                            <p class="opacity-80">Unit Kerja Terintegrasi</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">99%</div>
                            <p class="opacity-80">Tingkat Akurasi Data</p>
                        </div>
                        <div>
                            <div class="text-4xl font-bold mb-2">3x</div>
                            <p class="opacity-80">Proses Otomatis & Efisien</p>
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
                            "SIM-Ijazah telah mengubah proses administratif yang dulu memakan waktu berhari-hari menjadi hanya beberapa klik. Transparansi dan kemudahan pelacakan sangat membantu mahasiswa dalam memantau progres dokumen mereka."
                        </p>
                        {{-- <div>
                            <p class="font-semibold text-blue-700">Dr. Hamid Pattimura</p>
                            <p class="text-sm text-gray-500">Kepala BAAK Universitas Indonesia Timur</p>
                        </div> --}}
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <section class="py-16 px-6 bg-gray-50">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-2xl font-bold text-center mb-10 text-gray-800">Pertanyaan Umum</h3>
                    
                    <div class="space-y-6">
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-blue-700 mb-2">Berapa lama proses penerbitan ijazah?</h4>
                            <p class="text-gray-600">Dengan SIM-Ijazah, proses penerbitan ijazah dapat diselesaikan dalam waktu 7-14 hari kerja, tergantung pada kelengkapan berkas dan verifikasi dari seluruh unit terkait.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-blue-700 mb-2">Bagaimana cara memantau status ijazah?</h4>
                            <p class="text-gray-600">Mahasiswa dapat memantau status ijazah dengan login ke dashboard mahasiswa. Seluruh tahapan dan status terakhir akan tampil secara real-time dengan catatan dari setiap unit yang telah memverifikasi.</p>
                        </div>
                        
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <h4 class="font-semibold text-blue-700 mb-2">Apa saja persyaratan pengajuan ijazah?</h4>
                            <p class="text-gray-600">Persyaratan utama meliputi: status kelulusan yang sudah disahkan, bebas tanggungan keuangan, pas foto terbaru, scan KTP, dan dokumen akademik pendukung lainnya sesuai kebijakan prodi.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="py-16 px-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-center">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-3xl font-bold mb-6">Siap Menggunakan SIM-Ijazah?</h3>
                    <p class="text-lg opacity-90 mb-8">
                        Dapatkan kemudahan dan efisiensi dalam pengelolaan dokumen ijazah dengan sistem terintegrasi.
                    </p>
                    @auth
                        <a href="{{ Auth::user()->hasRole('mahasiswa') ? '/mahasiswa' : '/app' }}" class="bg-white text-blue-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold">
                            Akses Dashboard Anda
                        </a>
                    @else
                        <a href="/login" class="bg-white text-blue-700 px-8 py-3 rounded-lg shadow-lg hover:bg-gray-100 transition font-semibold">
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
                        <h4 class="text-lg font-semibold mb-4">Tentang SIM-Ijazah</h4>
                        <p class="text-gray-400 text-sm">
                            SIM-Ijazah adalah sistem informasi manajemen yang dikembangkan untuk memudahkan dan mengoptimalkan proses penerbitan ijazah di Universitas Indonesia Timur.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                        <ul class="text-gray-400 text-sm space-y-2">
                            {{-- <li class="flex items-center"><i class="fas fa-phone mr-2"></i> (0411) 123-456</li> --}}
                            <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i>Jl. Rappocini Raya No 171-173, Makassar</li>
                            <li class="flex items-center"><i class="fas fa-globe mr-2"></i>https://uit.ac.id</li>
                            <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> info@uit.ac.id</li>
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
                    <p>&copy; {{ date('Y') }} SIM-Ijazah - Universitas Indonesia Timur. Dikembangkan oleh CV. Mitranet Komputindo.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>