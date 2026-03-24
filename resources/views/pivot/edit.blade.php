@section('namePage', 'Edit Pivot & Fibonnaci')

@php($errors = $errors ?? new \Illuminate\Support\ViewErrorBag())
@php($backCategory = old('category', $pivot->category))

<x-app-layout>
<<<<<<< Updated upstream
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="card p-6 bg-white rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Edit Data Pivot</h2>

            <form id="pivotForm" action="{{ route('pivot.update', $pivot->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="tanggal" class="block font-medium">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $pivot->tanggal) }}"
                        class="w-full border rounded p-2 @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="open" class="block font-medium">Open</label>
                        <input type="text" name="open" id="open"
                            value="{{ old('open', $pivot->open) }}"
                            class="w-full border rounded p-2 @error('open') border-red-500 @enderror" required>
                        @error('open')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="high" class="block font-medium">High</label>
                        <input type="text" name="high" id="high"
                            value="{{ old('high', $pivot->high) }}"
                            class="w-full border rounded p-2 @error('high') border-red-500 @enderror" required>
                        @error('high')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="low" class="block font-medium">Low</label>
                        <input type="text" name="low" id="low"
                            value="{{ old('low', $pivot->low) }}"
                            class="w-full border rounded p-2 @error('low') border-red-500 @enderror" required>
                        @error('low')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="close" class="block font-medium">Close</label>
                        <input type="text" name="close" id="close"
                            value="{{ old('close', $pivot->close) }}"
                            class="w-full border rounded p-2 @error('close') border-red-500 @enderror" required>
                        @error('close')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="hsiFields" class="grid grid-cols-2 md:grid-cols-3 gap-4" style="display: {{ $pivot->category === 'HSI Daily' || $pivot->category === 'SNI Daily' ? 'grid' : 'none' }};">
                    <div>
                        <label for="chg" class="block font-medium">Chg.</label>
                        <input type="text" name="chg" id="chg" value="{{ old('chg', $pivot->chg) }}"
                            class="w-full border rounded p-2 @error('chg') border-red-500 @enderror" {{ $pivot->category === 'HSI Daily' || $pivot->category === 'SNI Daily' ? 'required' : '' }}>
                        @error('chg')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="volume" class="block font-medium">Volume</label>
                        <input type="text" name="volume" id="volume" value="{{ old('volume', $pivot->volume) }}"
                            class="w-full border rounded p-2 @error('volume') border-red-500 @enderror" {{ $pivot->category === 'HSI Daily' || $pivot->category === 'SNI Daily' ? 'required' : '' }}>
                        @error('volume')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div id="openInterestField">
                        <label for="open_interest" class="block font-medium">Open Interest</label>
                        <input type="text" name="open_interest" id="open_interest" value="{{ old('open_interest', $pivot->open_interest) }}"
                            class="w-full border rounded p-2 @error('open_interest') border-red-500 @enderror" {{ $pivot->category === 'HSI Daily' ? 'required' : '' }}>
                        @error('open_interest')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="category" class="block font-medium">Kategori</label>
                    <select name="category" id="category"
                        class="w-full border rounded p-2 @error('category') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach (['LGD Daily', 'LSI', 'HSI Daily', 'SNI Daily', 'AUD/USD', 'EUR/USD', 'GBP/USD', 'USD/CHF', 'USD/JPY'] as $kategori)
                            <option value="{{ $kategori }}"
                                {{ old('category', $pivot->category) == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="button" onclick="openBackModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali
=======
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if ($errors->any())
            <section
                class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <h2 class="font-semibold">Periksa kembali perubahan yang dibuat.</h2>
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
                        Kembali ke historical data
>>>>>>> Stashed changes
                    </button>

                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Edit Historical Data
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                        Perbarui data pivot tanpa layout yang terlalu padat. Fokus utama tetap pada tanggal, kategori,
                        nilai OHLC, dan status bank holiday.
                    </p>
                </div>

                <button type="button" onclick="openModal('modalSubmit')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>
        </section>

        <form id="pivotForm" action="{{ route('pivot.update', $pivot->id) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')

            @include('pivot.partials.form-fields', ['pivot' => $pivot])

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Simpan Update</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Pastikan perubahan pada kategori, tanggal, dan nilai harga sudah sesuai sebelum disimpan.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <button type="button" onclick="openModal('modalKembali')"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                            Batal
                        </button>
                        <button type="button" onclick="openModal('modalSubmit')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                            <i class="fa-solid fa-check"></i>
                            Update Data
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>

<<<<<<< Updated upstream
    <!-- Modal Konfirmasi Simpan -->
    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
            <p class="mb-6">Apakah Anda yakin ingin menyimpan data ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ya,
                    Simpan</button>
=======
    <div id="modalSubmit" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Simpan perubahan?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data historical akan diperbarui di kategori yang dipilih setelah Anda konfirmasi.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalSubmit')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <button type="submit" form="pivotForm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Ya, simpan
                </button>
>>>>>>> Stashed changes
            </div>
        </div>
    </div>

<<<<<<< Updated upstream
    <!-- Modal Konfirmasi Kembali -->
    <div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Kembali</h3>
            <p class="mb-6">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan hilang.</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <a href="{{ route('pivot.index') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Kembali</a>
=======
    <div id="modalKembali" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Batalkan perubahan?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Perubahan yang belum disimpan akan hilang jika Anda keluar dari halaman edit.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalKembali')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Tetap edit
                </button>
                <a href="{{ route('pivot.index', ['category' => $backCategory]) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Ya, keluar
                </a>
>>>>>>> Stashed changes
            </div>
        </div>
    </div>

<<<<<<< Updated upstream
    <script>
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('pivotForm').submit();
        }

        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }

        // Toggle HSI/SNI fields based on category selection
        document.getElementById('category').addEventListener('change', function() {
            const hsiFields = document.getElementById('hsiFields');
            const openInterestField = document.getElementById('openInterestField');
            const chgInput = document.getElementById('chg');
            const volumeInput = document.getElementById('volume');
            const openInterestInput = document.getElementById('open_interest');

            if (this.value === 'HSI Daily' || this.value === 'SNI Daily') {
                hsiFields.style.display = 'grid';
                chgInput.required = true;
                volumeInput.required = true;

                if (this.value === 'HSI Daily') {
                    openInterestField.style.display = 'block';
                    openInterestInput.required = true;
                } else {
                    openInterestField.style.display = 'none';
                    openInterestInput.required = false;
                }
            } else {
                hsiFields.style.display = 'none';
                chgInput.required = false;
                volumeInput.required = false;
                openInterestInput.required = false;
            }
        });
    </script>
=======
    @include('pivot.partials.form-scripts')
>>>>>>> Stashed changes
</x-app-layout>
