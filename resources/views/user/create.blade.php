@section('namePage', 'Tambah Pengguna')


<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <form id="userForm" action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="inline-flex items-center justify-between w-full mb-7 md:mb-4">
                    {{-- Tombol Kembali --}}
                    <button type="button" onclick="toggleModal('modalKembali')"
                        class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-600 dark:text-gray-200 cursor-pointer">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span class="hidden md:block">Kembali</span>
                    </button>

                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 text-center">
                        Tambah Pengguna
                    </h1>

                    {{-- Tombol Tambah --}}
                    <button type="button" onclick="toggleModal('modalSubmit')"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold cursor-pointer">
                        <i class="fa-solid fa-plus"></i>
                        <span class="hidden md:block">Tambah</span>
                    </button>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                        Username
                    </label>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('username') is-invalid @enderror"
                        value="{{ old('username') }}">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                        Nama
                    </label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name  ') is-invalid @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                        Email
                    </label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') is-invalid @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                        Role
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer @error('role') is-invalid @enderror">
                        <option class="cursor-pointer" value="">-- Pilih Role --</option>
                        <option class="cursor-pointer" value="Superadmin"
                            {{ old('role') == 'Superadmin' ? 'selected' : '' }}>
                            Superadmin
                        </option>
                        <option class="cursor-pointer" value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option class="cursor-pointer" value="Trainer (Internal)"
                            {{ old('role') == 'Trainer (Internal)' ? 'selected' : '' }}>
                            Trainer (Internal)
                        </option>
                        <option class="cursor-pointer" value="Trainer (External)"
                            {{ old('role') == 'Trainer (External)' ? 'selected' : '' }}>
                            Trainer (External)
                        </option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required minlength="6"
                            placeholder="Masukkan password" autocomplete="new-password"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('password') is-invalid @enderror">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-2 text-gray-500 cursor-pointer">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Modal Konfirmasi Submit --}}
                <div id="modalSubmit"
                    class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100 transition-opacity">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">
                            <i class="fa-solid fa-triangle-exclamation text-red-700"></i> Konfirmasi
                        </h2>
                        <p class="dark:text-gray-300">Apakah Anda yakin ingin menambahkan pengguna ini?</p>
                        <div class="flex justify-end mt-4">
                            <button onclick="toggleModal('modalSubmit')"
                                class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" id="submitButton"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg cursor-pointer">
                                Ya, Tambahkan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalKembali"
        class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100 transition-opacity">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4 dark:text-gray-100"><i
                    class="fa-solid fa-triangle-exclamation text-red-700"></i>
                Konfirmasi</h2>
            <p class="dark:text-gray-300">Apakah Anda yakin ingin kembali? Data tidak akan disimpan.</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal('modalKembali')"
                    class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg cursor-pointer">
                    Batal
                </button>
                <a href="{{ route('user.index') }}"
                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg cursor-pointer">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
            modal.classList.toggle('opacity-100');
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        // Tambahkan efek loading saat tombol submit diklik
        document.getElementById('submitButton').addEventListener('click', function() {
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
            this.disabled = true;
            document.getElementById('userForm').submit();
        });
    </script>
</x-app-layout>
