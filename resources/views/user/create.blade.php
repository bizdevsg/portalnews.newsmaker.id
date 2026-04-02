@section('namePage', 'Tambah Pengguna')

<x-app-layout>
    @php
        $inputClass =
            'w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500 dark:focus:border-blue-400 dark:focus:ring-blue-400/30';
    @endphp

    <div class="relative px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="pointer-events-none absolute inset-x-0 top-4 h-44 bg-gradient-to-r from-sky-400/20 via-blue-500/20 to-cyan-400/20 blur-3xl">
        </div>

        <div class="relative grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
            <section
                class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white/95 shadow-xl backdrop-blur dark:border-slate-700/70 dark:bg-slate-900/90">
                <form id="userForm" action="{{ route('user.store') }}" method="POST">
                    @csrf

                    <header
                        class="flex flex-col gap-4 border-b border-slate-200/70 bg-gradient-to-r from-slate-50 to-sky-50 px-6 py-5 dark:border-slate-700/60 dark:from-slate-900 dark:to-slate-800 sm:flex-row sm:items-center sm:justify-between">
                        <button type="button" onclick="toggleModal('modalKembali', true)"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                            Kembali
                        </button>

                        <div class="text-left sm:text-center">
                            <h1 class="text-xl font-bold text-slate-900 dark:text-slate-100 md:text-2xl">Tambah Pengguna</h1>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Lengkapi data akun baru untuk tim.</p>
                        </div>

                        <button type="button" onclick="toggleModal('modalSubmit', true)"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <i class="fa-solid fa-plus"></i>
                            Simpan Pengguna
                        </button>
                    </header>

                    <div class="grid gap-5 p-6 md:p-8">
                        <div>
                            <label for="username"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">
                                Username
                            </label>
                            <input type="text" id="username" name="username" required placeholder="contoh: admin.portal"
                                value="{{ old('username') }}"
                                class="{{ $inputClass }} @error('username') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500/60 @enderror">
                            @error('username')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">
                                Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}"
                                class="{{ $inputClass }} @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500/60 @enderror">
                            @error('name')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">
                                Email
                            </label>
                            <input type="email" id="email" name="email" required placeholder="nama@domain.com"
                                value="{{ old('email') }}"
                                class="{{ $inputClass }} @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500/60 @enderror">
                            @error('email')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">
                                Role
                            </label>
                            <select id="role" name="role" required
                                class="{{ $inputClass }} cursor-pointer @error('role') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500/60 @enderror">
                                <option value="">-- Pilih Role --</option>
                                <option value="Superadmin" {{ old('role') == 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Trainer (Internal)" {{ old('role') == 'Trainer (Internal)' ? 'selected' : '' }}>
                                    Trainer (Internal)
                                </option>
                                <option value="Trainer (External)" {{ old('role') == 'Trainer (External)' ? 'selected' : '' }}>
                                    Trainer (External)
                                </option>
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password"
                                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">
                                Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" required minlength="6"
                                    placeholder="Minimal 6 karakter" autocomplete="new-password"
                                    class="{{ $inputClass }} pr-11 @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/20 dark:border-rose-500/60 @enderror">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                    <i id="eyeIcon" class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">Gunakan kombinasi huruf, angka, dan simbol.</p>
                            @error('password')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </form>
            </section>

            <aside class="space-y-5">
                <div
                    class="rounded-3xl border border-slate-200/70 bg-white/90 p-5 shadow-lg backdrop-blur dark:border-slate-700/70 dark:bg-slate-900/80">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">Panduan</h2>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
                            Username sebaiknya unik dan mudah diingat.
                        </li>
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
                            Email aktif diperlukan untuk reset password.
                        </li>
                        <li class="flex gap-2">
                            <i class="fa-solid fa-circle-check mt-1 text-[10px] text-emerald-500"></i>
                            Pilih role sesuai otorisasi tim.
                        </li>
                    </ul>
                </div>

                <div
                    class="rounded-3xl border border-slate-200/70 bg-gradient-to-br from-slate-900 via-blue-900 to-cyan-800 p-5 text-white shadow-lg">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-blue-100/80">Akses Role</p>
                    <div class="mt-3 space-y-2 text-sm">
                        <p><span class="font-semibold">Superadmin:</span> kontrol penuh sistem.</p>
                        <p><span class="font-semibold">Admin:</span> kelola operasional harian.</p>
                        <p><span class="font-semibold">Trainer:</span> akses sesuai kebutuhan training.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div id="modalSubmit" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                <i class="fa-solid fa-circle-question mr-1.5 text-blue-600"></i>
                Konfirmasi Simpan
            </h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Data pengguna baru akan disimpan. Lanjutkan?</p>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalSubmit', false)"
                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                    Batal
                </button>
                <button type="button" id="confirmSubmitButton"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>

    <div id="modalKembali" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                <i class="fa-solid fa-triangle-exclamation mr-1.5 text-amber-500"></i>
                Konfirmasi Kembali
            </h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Perubahan belum disimpan. Yakin ingin kembali?</p>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalKembali', false)"
                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                    Batal
                </button>
                <a href="{{ route('user.index') }}"
                    class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateBodyScrollLock() {
            const hasVisibleModal = ['modalSubmit', 'modalKembali'].some((id) => {
                return !document.getElementById(id)?.classList.contains('hidden');
            });

            document.body.classList.toggle('overflow-hidden', hasVisibleModal);
        }

        function toggleModal(id, show = null) {
            const modal = document.getElementById(id);
            if (!modal) {
                return;
            }

            const shouldShow = show === null ? modal.classList.contains('hidden') : show;
            modal.classList.toggle('hidden', !shouldShow);
            modal.classList.toggle('flex', shouldShow);
            updateBodyScrollLock();
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (!passwordField || !eyeIcon) {
                return;
            }

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
                return;
            }

            passwordField.type = 'password';
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }

        document.getElementById('confirmSubmitButton')?.addEventListener('click', function() {
            const button = this;
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            button.disabled = true;
            document.getElementById('userForm')?.submit();
        });

        ['modalSubmit', 'modalKembali'].forEach((id) => {
            document.getElementById(id)?.addEventListener('click', function(event) {
                if (event.target.id === id) {
                    toggleModal(id, false);
                }
            });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key !== 'Escape') {
                return;
            }

            toggleModal('modalSubmit', false);
            toggleModal('modalKembali', false);
        });
    </script>
</x-app-layout>
