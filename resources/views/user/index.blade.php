<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex flex-col gap-6 h-full">
        <!-- Dashboard actions -->
        <div class="flex justify-between items-center">
            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Daftar Pengguna</h1>

            {{-- Button Tambah --}}
            <a href="{{ route('user.create') }}"
                class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                Tambah Pengguna
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
        <div id="successAlert" class="w-full">
            <div
                class="border-l-4 border-green-600 p-4 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-between shadow-md transition-opacity duration-300">
                <div class="flex items-center gap-2 text-green-800 dark:text-green-300 text-sm sm:text-base">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="closeAlert()"
                    class="p-1 text-green-800 dark:text-green-300 hover:text-green-900 dark:hover:text-green-400 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        @endif

        <div class="overflow-x-auto bg-white dark:bg-gray-700 shadow-md rounded-lg">
            <table class="w-full border-collapse border border-gray-200 dark:border-gray-700">
                <thead class="bg-blue-300 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">#</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">Username</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">Name</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">Email</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">Role</th>
                        <th class="px-4 py-2 border border-gray-200 dark:border-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach ($users as $index => $item)
                    <tr>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-center">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">{{ $item->username }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">{{ $item->name }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">{{ $item->email }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700">{{ $item->role }}</td>
                        <td class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-center">
                            @if (auth()->user()->id === $item->id)
                            <div class="flex">
                                <a href="{{ route('profile.show') }}"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                    Edit Profil
                                </a>
                            </div>
                            @else
                            <div class="flex gap-2">
                                <a href="{{ route('user.edit', $item->id) }}"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                    Edit
                                </a>

                                <!-- Tombol untuk membuka modal -->
                                <button onclick="showDeleteModal({{ $item->id }}, '{{ $item->name }}')"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal Konfirmasi Hapus -->
            <div id="deleteModal" class="fixed inset-0 bg-gray-900/50 flex items-center justify-center hidden z-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">Konfirmasi Hapus</h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">
                        Apakah Anda yakin ingin menghapus pengguna <span id="userName"
                            class="font-bold text-red-500"></span>?
                    </p>

                    <div class="flex justify-end gap-3 mt-4">
                        <button onclick="hideDeleteModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg cursor-pointer">
                            Batal
                        </button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function showDeleteModal(id, name) {
            const modal = document.getElementById("deleteModal");
            const form = document.getElementById("deleteForm");
            const userName = document.getElementById("userName");
    
            // Update action form dengan mengganti placeholder ":id" dengan ID pengguna yang dipilih
            form.action = `{{ route('user.destroy', ':id') }}`.replace(':id', id);
            
            // Tampilkan nama pengguna di modal
            userName.textContent = name;
    
            // Tampilkan modal
            modal.classList.remove("hidden");
        }
    
        function hideDeleteModal() {
            document.getElementById("deleteModal").classList.add("hidden");
        }
    </script>
    <script>
        function closeAlert() {
            const alert = document.getElementById("successAlert");
            if (alert) {
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 300);
            }
        }
    </script>
</x-app-layout>