@section('namePage', 'Tambah TikTok')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-6xl mx-auto">
        <div class="card p-6 bg-white rounded shadow dark:bg-slate-800 dark:text-slate-100">
            <h2 class="text-xl font-semibold mb-4">Tambah Data TikTok</h2>

            <form id="tiktokForm" action="{{ route('tiktok.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="block font-medium text-slate-700 dark:text-slate-200">Judul</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full border rounded p-2 bg-white text-slate-900 placeholder-slate-400 dark:bg-slate-900 dark:text-slate-100 dark:border-slate-700 dark:placeholder-slate-400 @error('title') border-red-500 @enderror"
                        placeholder="Masukkan judul TikTok" required>
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="embed_code" class="block font-medium text-slate-700 dark:text-slate-200">Embed Code</label>
                    <textarea name="embed_code" id="embed_code" rows="6"
                        class="w-full border rounded p-2 bg-white text-slate-900 placeholder-slate-400 dark:bg-slate-900 dark:text-slate-100 dark:border-slate-700 dark:placeholder-slate-400 @error('embed_code') border-red-500 @enderror"
                        placeholder="Tempel embed code TikTok di sini" required>{{ old('embed_code') }}</textarea>
                    @error('embed_code')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="backup_video_url" class="block font-medium text-slate-700 dark:text-slate-200">Backup Video URL</label>
                    <input type="text" name="backup_video_url" id="backup_video_url" value="{{ old('backup_video_url') }}"
                        class="w-full border rounded p-2 bg-white text-slate-900 placeholder-slate-400 dark:bg-slate-900 dark:text-slate-100 dark:border-slate-700 dark:placeholder-slate-400 @error('backup_video_url') border-red-500 @enderror"
                        placeholder="/storage/tiktok_backups/filename.mp4">
                    @error('backup_video_url')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="button" onclick="openBackModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali
                    </button>

                    <button type="button" onclick="openSubmitModal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md dark:bg-slate-800 dark:text-slate-100">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
            <p class="mb-6 text-slate-600 dark:text-slate-300">Apakah Anda yakin ingin menyimpan data ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Batal</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ya,
                    Simpan</button>
            </div>
        </div>
    </div>

    <div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md dark:bg-slate-800 dark:text-slate-100">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Kembali</h3>
            <p class="mb-6 text-slate-600 dark:text-slate-300">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan hilang.</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Batal</button>
                <a href="{{ route('tiktok.index') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Kembali</a>
            </div>
        </div>
    </div>

    <script>
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('tiktokForm').submit();
        }

        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
