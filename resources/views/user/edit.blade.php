<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <form id="userForm" action="{{ route('user.update', $user->id) }}" method="POST">
                @method('PUT')
                @csrf

                <div class="inline-flex items-center justify-between w-full mb-7 md:mb-4">
                    {{-- Tombol Kembali --}}
                    <div class="text-left">
                        <button type="button" onclick="toggleModal('modalKembali')"
                            class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-600 dark:text-gray-200">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span class="hidden md:block">Kembali</span>
                        </button>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 text-center">
                        Edit Pengguna
                    </h1>

                    {{-- Tombol Simpan --}}
                    <div class="text-right">
                        <button type="button" onclick="toggleModal('modalSubmit')"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold">
                            <i class="fa-solid fa-save"></i>
                            <span class="hidden md:block">Simpan</span>
                        </button>
                    </div>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username"
                        class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Username</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('username') border-red-500 @enderror"
                        value="{{ old('username', $user->username) }}">
                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Nama
                        Lengkap</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('name') border-red-500 @enderror"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('email') border-red-500 @enderror"
                        value="{{ old('email', $user->email) }}">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Role</label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('role') border-red-500 @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="Superadmin" {{ old('role', $user->role) == 'Superadmin' ? 'selected' : ''
                            }}>Superadmin</option>
                        <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password"
                        class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-2 text-gray-500 cursor-pointer">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal Konfirmasi Submit --}}
                <div id="modalSubmit"
                    class="hidden fixed inset-0 bg-gray-900/50 flex items-center justify-center px-3 z-100">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">Konfirmasi</h2>
                        <p class="dark:text-gray-300">Apakah Anda yakin ingin menyimpan perubahan?</p>
                        <div class="flex justify-end mt-4">
                            <button onclick="toggleModal('modalSubmit')"
                                class="mr-2 bg-gray-400 hover:bg-gray-500 text-white py-2 px-4 rounded-lg">
                                Batal
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                                Ya, Simpan
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
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
    </script>
</x-app-layout>