@section('namePage', '404 Not Found')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="max-w-2xl m-auto mt-16">
            <div class="text-center px-4">
                <div class="inline-flex mb-8">
                    <img class="dark:hidden" src="{{ asset('images/404-illustration.svg') }}" width="176" height="176"
                        alt="Ilustrasi 404" />
                    <img class="hidden dark:block" src="{{ asset('images/404-illustration-dark.svg') }}" width="176"
                        height="176" alt="Ilustrasi 404 Dark" />
                </div>
                <div class="mb-6 text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Hmm... Halaman ini tidak ditemukan. Coba cari halaman lain!
                </div>
                <a href="{{ route('dashboard') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
