<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        @if(session('success'))
        <div
            class="mb-6 rounded-lg border-l-2 border-green-600 bg-green-100 dark:bg-green-900/50 p-4 text-green-800 dark:text-green-200">
            <div class="flex items-center space-x-2 font-semibold">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="bg-white p-3 rounded-2xl">
            <!-- Search Bar -->
            <div class="w-full flex items-center gap-2 mb-5">
                <form action="" method="GET" class="flex items-center gap-2 flex-grow">
                    <input type="text" name="search" placeholder="Cari judul atau penulis eBook..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" />
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition cursor-pointer">
                        Cari
                    </button>
                </form>

                <a href="{{route('ebook.create')}}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
                    Tambah e-Book
                </a>
            </div>

            <!-- Grid Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
                @forelse ($ebooks as $item)
                <a href="{{ route('ebook.show', $item->id) }}"
                    class="relative group overflow-hidden rounded-xl shadow-md block cursor-pointer aspect-[2/3]">
                    <img src="{{ asset($item->cover_image) }}" alt="{{ $item->judul }}"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    <div
                        class="absolute inset-0 bg-black/75 bg-opacity-60 opacity-0 group-hover:opacity-100 transition duration-300 flex flex-col justify-center items-center text-white p-5 text-center">
                        <h3 class="text-lg font-semibold">{{ $item->judul }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ $item->penulis }}, {{$item->tahun_terbit}}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-10 text-gray-500">
                    Tidak ada ebook ditemukan.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>