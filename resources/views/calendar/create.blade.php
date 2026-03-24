@section('namePage', 'Tambah Kalender Ekonomi')

@php($errors = $errors ?? new \Illuminate\Support\ViewErrorBag())

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if ($errors->any())
            <section
                class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <h2 class="font-semibold">Periksa kembali form sebelum menyimpan.</h2>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <button type="button" onclick="openModal('modalKembali')"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke kalender
                    </button>

                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Tambah Kalender Ekonomi
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                        Masukkan event baru dengan struktur form yang lebih ringkas supaya lebih nyaman dipakai di desktop
                        maupun mobile.
                    </p>
                </div>

                <button type="button" onclick="openModal('modalSubmit')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-plus"></i>
                    Simpan Event
                </button>
            </div>
        </section>

        <form id="calendarForm" action="{{ route('calendar.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf

            @include('calendar.partials.form-fields', ['calendar' => null, 'isEdit' => false])

<<<<<<< Updated upstream
                <h1 class="text-xl md:text-2xl text-gray-800 dark:text-gray-100 font-bold">Kalender Ekonomi</h1>

                <button type="button" onclick="toggleModal('modalSubmit')"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden md:block">Tambah</span>
                </button>
            </div>

            {{-- Form Fields --}}
            <div class="flex flex-col gap-4">
                {{-- Figures --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="figures" class="font-medium text-gray-700 dark:text-gray-100">Peristiwa</label>
                    <input type="text" name="figures" id="figures" required
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                {{-- Sources --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="sources" class="font-medium text-gray-700 dark:text-gray-100">Sources</label>
                    <input type="text" name="sources" id="sources"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('sources')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Measures --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="measures" class="font-medium text-gray-700 dark:text-gray-100">Measures</label>
                    <input type="text" name="measures" id="measures"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('measures')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Usual Effect --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="usual_effect" class="font-medium text-gray-700 dark:text-gray-100">Usual Effect</label>
                    <input type="text" name="usual_effect" id="usual_effect"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                {{-- Frequency --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="frequency" class="font-medium text-gray-700 dark:text-gray-100">Frequency</label>
                    <input type="text" name="frequency" id="frequency"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                {{-- Next Released --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="next_released" class="font-medium text-gray-700 dark:text-gray-100">Next
                        Released</label>
                    <input type="text" name="next_released" id="next_released"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                </div>

                {{-- Notes --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="notes" class="font-medium text-gray-700 dark:text-gray-100">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"></textarea>
                </div>

                {{-- Why Traders Care --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="why_traders_care" class="font-medium text-gray-700 dark:text-gray-100">Why Traders
                        Care</label>
                    <textarea name="why_traders_care" id="why_traders_care" rows="3"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500"></textarea>
                </div>

                {{-- Tanggal dan Waktu --}}
                <div class="flex w-full flex-col md:flex-row gap-4">
                    <div class="form-group w-full flex flex-col gap-2">
                        <label for="date" class="font-medium text-gray-700 dark:text-gray-100">Tanggal</label>
                        <input type="date" name="date" id="date" required
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
=======
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Siap Disimpan</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Pastikan peristiwa, impact, dan sumber sudah sesuai sebelum event ditambahkan.
                        </p>
>>>>>>> Stashed changes
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <button type="button" onclick="openModal('modalKembali')"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                            Batal
                        </button>
                        <button type="button" onclick="openModal('modalSubmit')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <div id="modalSubmit" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Simpan event baru?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Event akan langsung masuk ke daftar kalender setelah disimpan.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalSubmit')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <button type="submit" form="calendarForm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-check"></i>
                    Ya, simpan
                </button>
            </div>
        </div>
    </div>

    <div id="modalKembali" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Tinggalkan halaman ini?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data yang belum disimpan akan hilang jika Anda kembali ke daftar kalender.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalKembali')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Tetap di sini
                </button>
                <a href="{{ route('calendar.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Ya, kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);

            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);

            if (modal) {
                modal.classList.add('hidden');
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('[data-modal]').forEach(function(modal) {
                    modal.classList.add('hidden');
                });
            }
        });

        document.querySelectorAll('[data-modal]').forEach(function(modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
