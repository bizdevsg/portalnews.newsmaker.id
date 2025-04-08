<x-app-layout background="bg-white dark:bg-gray-900">
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <div class="max-w-2xl m-auto mt-16">

            <div class="text-center px-4">
                <div class="inline-flex mb-8">
                    <img src="{{ asset('images/58379218_9432378.svg') }}" width="176" height="176"
                        alt="403 illustration" />
                </div>
                <div class="mb-6 text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Oops... Anda tidak memiliki izin untuk mengakses halaman ini.
                </div>
                <a href="{{ route('dashboard') }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    Kembali ke Beranda
                </a>
            </div>

        </div>

    </div>
</x-app-layout>