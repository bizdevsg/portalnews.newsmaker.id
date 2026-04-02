@section('namePage', 'Manajemen Pengguna')

<x-app-layout>
    @php
        $userCollection = method_exists($users, 'getCollection') ? $users->getCollection() : collect($users);
        $totalUsers = method_exists($users, 'total') ? $users->total() : $userCollection->count();
        $startNumber = method_exists($users, 'firstItem') && $users->firstItem() ? $users->firstItem() - 1 : 0;

        $roleStyles = [
            'Superadmin' => 'bg-rose-500/10 text-rose-700 ring-1 ring-inset ring-rose-500/20 dark:bg-rose-500/15 dark:text-rose-300 dark:ring-rose-400/30',
            'Admin' => 'bg-blue-500/10 text-blue-700 ring-1 ring-inset ring-blue-500/20 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-400/30',
            'Trainer (Internal)' => 'bg-emerald-500/10 text-emerald-700 ring-1 ring-inset ring-emerald-500/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-400/30',
            'Trainer (External)' => 'bg-amber-500/10 text-amber-700 ring-1 ring-inset ring-amber-500/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/30',
        ];
    @endphp

    <div class="relative px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div
            class="pointer-events-none absolute inset-x-0 -top-16 h-44 bg-gradient-to-r from-blue-500/20 via-sky-500/20 to-cyan-500/10 blur-3xl">
        </div>

        <div class="relative flex flex-col gap-6">
            <section
                class="overflow-hidden rounded-3xl border border-white/20 bg-gradient-to-br from-slate-900 via-blue-900 to-cyan-800 text-white shadow-xl">
                <div class="px-6 py-7 md:px-8 md:py-8">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-100/80">Management</p>
                            <h1 class="mt-2 text-2xl font-bold md:text-3xl">Daftar Pengguna</h1>
                            <p class="mt-2 max-w-2xl text-sm text-blue-100/80 md:text-base">
                                Kelola akun admin dan trainer dalam satu dashboard yang lebih ringkas.
                            </p>
                        </div>

                        <a href="{{ route('user.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/80">
                            <i class="fa-solid fa-user-plus"></i>
                            Tambah Pengguna
                        </a>
                    </div>

                    <div class="mt-7 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                        <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                            <p class="text-xs uppercase tracking-[0.16em] text-blue-100/70">Total Pengguna</p>
                            <p class="mt-1 text-2xl font-bold">{{ $totalUsers }}</p>
                        </div>

                        @foreach (['Superadmin', 'Admin', 'Trainer (Internal)', 'Trainer (External)'] as $role)
                            <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-[0.16em] text-blue-100/70">{{ $role }}</p>
                                <p class="mt-1 text-2xl font-bold">{{ $userCollection->where('role', $role)->count() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            @if (session('success'))
                <div id="successAlert"
                    class="flex items-start justify-between gap-3 rounded-2xl border border-emerald-300 bg-emerald-50/90 px-4 py-3 text-emerald-900 shadow-sm transition-opacity duration-300 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-base"></i>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="closeAlert()"
                        class="rounded-lg p-1 text-emerald-800 transition hover:bg-emerald-500/10 dark:text-emerald-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            <section
                class="overflow-hidden rounded-3xl border border-slate-200/80 bg-white/95 shadow-lg backdrop-blur dark:border-slate-700/70 dark:bg-slate-900/90">
                <div class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-200/70 px-6 py-4 dark:border-slate-700/60">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">List Pengguna</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Aksi edit/hapus tersedia untuk akun selain milik Anda.</p>
                    </div>
                    <span
                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ $userCollection->count() }} data ditampilkan
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-slate-100/90 text-left text-xs font-semibold uppercase tracking-[0.14em] text-slate-600 backdrop-blur dark:bg-slate-800/90 dark:text-slate-300">
                            <tr>
                                <th class="whitespace-nowrap px-5 py-3">#</th>
                                <th class="whitespace-nowrap px-5 py-3">Username</th>
                                <th class="whitespace-nowrap px-5 py-3">Nama</th>
                                <th class="whitespace-nowrap px-5 py-3">Email</th>
                                <th class="whitespace-nowrap px-5 py-3">Role</th>
                                <th class="whitespace-nowrap px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/80 dark:divide-slate-700/60">
                            @forelse ($userCollection as $item)
                                @php
                                    $badgeClass =
                                        $roleStyles[$item->role] ??
                                        'bg-slate-500/10 text-slate-700 ring-1 ring-inset ring-slate-500/20 dark:bg-slate-500/20 dark:text-slate-300 dark:ring-slate-400/30';
                                @endphp
                                <tr class="bg-white transition hover:bg-sky-50/70 dark:bg-slate-900/40 dark:hover:bg-slate-800/60">
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-500 dark:text-slate-400">
                                        {{ $startNumber + $loop->iteration }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-800 dark:text-slate-100">
                                        {{ $item->username }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-700 dark:text-slate-200">{{ $item->name }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600 dark:text-slate-300">{{ $item->email }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $item->role ?? 'Tidak Diketahui' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if (auth()->user()->id === $item->id)
                                            <a href="{{ route('profile.show') }}"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                                <i class="fa-solid fa-user-gear"></i>
                                                Edit Profil
                                            </a>
                                        @else
                                            <div class="flex flex-col gap-2 sm:flex-row">
                                                <a href="{{ route('user.edit', $item->id) }}"
                                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    onclick="showDeleteModal({{ $item->id }}, @js($item->name))"
                                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                        Belum ada data pengguna.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($users, 'links'))
                    <div class="border-t border-slate-200/70 px-6 py-4 dark:border-slate-700/60">
                        {{ $users->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                <i class="fa-solid fa-triangle-exclamation mr-1.5 text-rose-600"></i>
                Konfirmasi Hapus
            </h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                Pengguna <span id="userName" class="font-semibold text-rose-600 dark:text-rose-400"></span> akan dihapus permanen.
            </p>

            <div class="mt-5 flex justify-end gap-2">
                <button type="button" onclick="hideDeleteModal()"
                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Hapus Pengguna
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(id, name) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            const userName = document.getElementById('userName');

            form.action = `{{ route('user.destroy', ':id') }}`.replace(':id', id);
            userName.textContent = name;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function closeAlert() {
            const alert = document.getElementById('successAlert');
            if (!alert) {
                return;
            }

            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 250);
        }

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target.id === 'deleteModal') {
                hideDeleteModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</x-app-layout>
