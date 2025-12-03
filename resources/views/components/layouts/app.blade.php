<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Laptop - TOPSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @media print {
            /* Sembunyikan Sidebar, Header, dan elemen input saat mencetak */
            .no-print, .sidebar, .header, .input-section {
                display: none !important;
            }
            /* Pastikan background warna tercetak (misal baris ganjil genap) */
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: white;
            }
            /* Atur ulang lebar konten agar full kertas */
            .main-content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            /* Sembunyikan bayangan kotak agar hemat tinta */
            .shadow-lg, .shadow-sm {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden">
        
        <aside class="sidebar w-64 bg-gray-900 text-white flex flex-col transition-all duration-300">
            <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-gray-800">
                <h2 class="text-xl font-bold tracking-wider">SPK LAPTOP</h2>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-700 rounded-lg text-white shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Dashboard
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</p>
                </div>

                <button onclick="window.print()" class="w-full flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg transition-colors text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </button>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center">A</div>
                    <div>
                        <p class="text-sm font-medium">Admin</p>
                        <p class="text-xs text-gray-400">Online</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <header class="header h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10">
                <h1 class="text-lg font-semibold text-gray-700">Sistem Pendukung Keputusan (TOPSIS)</h1>
                <button class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
            </header>

            <main class="main-content flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>