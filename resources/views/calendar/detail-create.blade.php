@section('namePage', 'Tambah Data Detail Kalender')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto">
        <form action="{{ route('calendar.detail.store', $category) }}" method="POST"
            class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            @csrf

            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-5 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold">Data Detail</p>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->figures }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Header category otomatis diambil dari category ini. User cukup isi data detail.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('calendar.show', $category) }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 cursor-pointer">
                        Simpan Detail
                    </button>
                </div>
            </div>

            <div class="p-6">
                @include('calendar.partials.detail-fields', ['category' => $category, 'detail' => null])
            </div>
        </form>
    </div>
</x-app-layout>
