@section('namePage', 'TikTok')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex flex-col gap-4 h-full">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-white font-bold">TikTok</h1>
            <a href="{{ route('tiktok.create') }}"
                class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                <span class="hidden md:block">Tambah TikTok</span>
                <span class="block md:hidden"><i class="fa-solid fa-plus"></i></span>
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg bg-green-100 text-green-800 px-4 py-2 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto md:overflow-x-visible">
            <table
                class="min-w-max md:min-w-full divide-y divide-gray-200 dark:divide-gray-700 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Judul</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Backup Video</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Dibuat</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-gray-500 dark:divide-gray-100 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                    @forelse ($tiktoks as $tiktok)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-4 py-2 text-sm font-semibold">{{ $tiktok->title }}</td>
                            <td class="px-4 py-2 text-sm">
                                {{ $tiktok->backup_video_url ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($tiktok->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                <button type="button" onclick="openDeleteModal({{ $tiktok->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm cursor-pointer">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada data TikTok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Konfirmasi Hapus</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">Apakah kamu yakin ingin menghapus data ini?
                    </p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeDeleteModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded cursor-pointer">Batal</button>
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cursor-pointer">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const deleteForm = document.getElementById('deleteForm');
        const deleteModal = document.getElementById('deleteModal');

        function openDeleteModal(id) {
            deleteForm.action = `{{ url('/tiktok') }}/${id}/delete`;
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>
</x-app-layout>
