<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="mb-5">
            <a href="{{ route('berita.index', $kategori->slug) }}"
                class="inline-flex items-center py-2 px-4 gap-3 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 rounded shadow-lg transition">
                <i class="fa-solid fa-chevron-left"></i>
                <span>Back</span>
            </a>
        </div>

        <div class="p-4 rounded-lg shadow">
            {{-- Judul --}}
            <h1 class="text-xl text-center text-blue-500 dark:text-blue-400 font-bold">
                {{ $berita->title }}
            </h1>

            {{-- Foto untuk 5 website berbeda --}}
            @php
            $images = collect(range(1, 5))->map(fn($i) => $berita->{'image' . $i})->filter();
            @endphp

            @if ($images->isNotEmpty())
            <div class="max-w-screen-md mx-auto my-6">
                <div class="swiper mySwiper rounded-lg overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach($images as $image)
                        <div class="swiper-slide flex flex-col items-center">
                            <img src="{{ asset($image) }}" alt="Gambar"
                                class="w-full h-64 md:h-96 object-cover rounded-lg shadow-md lazyload">
                            <div class="text-center mt-3 text-gray-600 dark:text-gray-300">{{ $image }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Navigasi -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            @endif

            {{-- Isi Berita --}}
            <div
                class="text-gray-700 dark:text-gray-300 leading-relaxed gap-5 flex flex-col bg-gray-200 dark:bg-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-1">
                    <span>{{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}</span>
                    <span>|</span>
                    <p class="text-sm bg-gray-500 dark:bg-amber-50 px-3 rounded-full text-white dark:text-gray-500">
                        <strong>Kategori:</strong> <span>{{$kategori->name}}</span>
                    </p>
                </div>
                {!! $berita->content !!}
            </div>
        </div>
    </div>

    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (document.querySelector(".mySwiper .swiper-slide")) {
                var swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    speed: 400,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
            }
        });
    </script>
</x-app-layout>