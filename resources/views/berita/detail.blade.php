<x-app-layout>
    @php
        $images = collect($berita->images ?? [])->values();
        $ptTitles = collect([
            'SG' => $berita->title_sg,
            'RFB' => $berita->title_rfb,
            'KPF' => $berita->title_kpf,
            'EWF' => $berita->title_ewf,
            'BPF' => $berita->title_bpf,
        ])->filter(fn($title) => filled($title));
        $isUpdated = $berita->updated_at && $berita->updated_at->ne($berita->created_at);
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-6 w-full max-w-9xl mx-auto">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('berita.index', $kategori->slug) }}"
                    class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white text-gray-700 shadow-sm ring-1 ring-gray-200 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <span
                            class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1.5 font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-200">
                            {{ $kategori->name }}
                        </span>
                        <span
                            class="inline-flex items-center rounded-full bg-white px-3 py-1.5 font-medium text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y, H:i') }}
                        </span>
                        @if ($images->isNotEmpty())
                            <span
                                class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1.5 font-medium text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/20 dark:text-amber-200 dark:ring-amber-700/50">
                                {{ $images->count() }} Gambar
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <a href="{{ route('berita.edit', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                <i class="fa-solid fa-pen-to-square"></i>
                Edit Berita
            </a>
        </div>

        <div>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                {{ $berita->title }}
            </h1>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="space-y-6">
                @if ($images->isNotEmpty())
                    <section
                        class="overflow-hidden rounded-[28px] border border-white/70 bg-white shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] dark:border-gray-800 dark:bg-gray-900">
                        <div class="swiper newsDetailSwiper">
                            <div class="swiper-wrapper">
                                @foreach ($images as $image)
                                    <div class="swiper-slide">
                                        <div class="relative">
                                            <img src="{{ asset($image) }}" alt="Gambar berita {{ $loop->iteration }}"
                                                loading="lazy" class="h-[300px] w-full object-cover sm:h-[420px]">
                                            <div
                                                class="absolute inset-x-0 bottom-0 flex items-center justify-between bg-linear-to-t from-slate-950/85 via-slate-950/30 to-transparent px-5 py-5 text-white">
                                                <div>
                                                    <p
                                                        class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-100/80">
                                                        Visual {{ $loop->iteration }}
                                                    </p>
                                                    <p class="mt-2 text-sm text-slate-200">
                                                        {{ basename($image) }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium backdrop-blur">
                                                    {{ $loop->iteration }}/{{ $images->count() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="swiper-pagination !bottom-4"></div>

                            @if ($images->count() > 1)
                                <div
                                    class="swiper-button-prev !left-4 !h-11 !w-11 rounded-full !bg-slate-950/55 !text-white after:!text-sm">
                                </div>
                                <div
                                    class="swiper-button-next !right-4 !h-11 !w-11 rounded-full !bg-slate-950/55 !text-white after:!text-sm">
                                </div>
                            @endif
                        </div>
                    </section>
                @endif

                <article
                    class="rounded-[28px] border border-white/70 bg-white p-6 shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] sm:p-8 dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="mb-6 flex items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 dark:text-sky-300">
                                Article Body
                            </p>
                            <h2 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                                Isi Berita
                            </h2>
                        </div>
                    </div>

                    <div
                        class="prose prose-slate max-w-none text-gray-700 prose-headings:text-gray-900 prose-a:text-sky-700 prose-strong:text-gray-900 lg:prose-lg dark:prose-invert dark:text-gray-300">
                        {!! $berita->content !!}
                    </div>
                </article>
            </div>

            <aside class="space-y-6">
                <section
                    class="rounded-[28px] border border-white/70 bg-white p-5 shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 dark:text-sky-300">
                        Informasi
                    </p>
                    <h2 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                        Metadata
                    </h2>

                    <div class="mt-5 space-y-4">
                        <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800/80">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                Kategori
                            </p>
                            <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $kategori->name }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800/80">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                Dipublikasikan
                            </p>
                            <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y - H:i') }}
                            </p>
                        </div>

                        @if ($isUpdated)
                            <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800/80">
                                <p
                                    class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                    Diperbarui
                                </p>
                                <p class="mt-1 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($berita->updated_at)->translatedFormat('l, d F Y - H:i') }}
                                </p>
                            </div>
                        @endif

                        <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800/80">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                Slug
                            </p>
                            <p class="mt-1 break-all text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $berita->slug }}
                            </p>
                        </div>
                    </div>
                </section>

                @if ($ptTitles->isNotEmpty())
                    <section
                        class="rounded-[28px] border border-white/70 bg-white p-5 shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 dark:text-sky-300">
                            Variasi Judul
                        </p>
                        <h2 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                            Judul Per PT
                        </h2>

                        <div class="mt-5 space-y-3">
                            @foreach ($ptTitles as $label => $title)
                                <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800/80">
                                    <p
                                        class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                        {{ $label }}
                                    </p>
                                    <p class="mt-1 text-sm leading-6 text-gray-700 dark:text-gray-200">
                                        {{ $title }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
<<<<<<< Updated upstream

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
                        <strong>Kategori:</strong> <span>{{ $kategori->name }}</span>
                    </p>
                </div>
                {!! $berita->content !!}
            </div>
=======
                    </section>
                @endif
            </aside>
>>>>>>> Stashed changes
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const swiperElement = document.querySelector('.newsDetailSwiper');

            if (!swiperElement) {
                return;
            }

            const slideCount = swiperElement.querySelectorAll('.swiper-slide').length;
            const paginationEl = swiperElement.querySelector('.swiper-pagination');
            const nextEl = swiperElement.querySelector('.swiper-button-next');
            const prevEl = swiperElement.querySelector('.swiper-button-prev');

            new Swiper(swiperElement, {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: slideCount > 1,
                speed: 500,
                autoplay: slideCount > 1 ? {
                    delay: 4000,
                    disableOnInteraction: false,
                } : false,
                pagination: paginationEl ? {
                    el: paginationEl,
                    clickable: true,
                } : undefined,
                navigation: nextEl && prevEl ? {
                    nextEl,
                    prevEl,
                } : undefined,
            });
        });
    </script>
</x-app-layout>
