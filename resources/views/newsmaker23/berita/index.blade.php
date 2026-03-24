@section('namePage', 'Berita Newsmaker 23')

<x-app-layout>
    @include('newsmaker23._ui')
    <div class="nm23 px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
        <div class="nm-hero">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span class="nm-chip">Newsmaker 23</span>
                    <div>
                        <div class="nm-kicker">Berita</div>
                        <h1 class="nm-title text-3xl sm:text-4xl">Semua Berita</h1>
                        <p class="text-sm sm:text-base text-blue-100 max-w-2xl">
                            Lihat semua berita yang sudah dibuat. Gunakan tombol tambah untuk memasukkan berita baru.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('newsmaker23.berita.create') }}" class="nm-btn nm-btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Berita
                    </a>
                    <a href="{{ route('newsmaker23.sub-category.index') }}" class="nm-btn nm-btn-ghost">
                        Sub Category
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="successAlert" class="mt-6 nm-card p-4 border-l-4 border-emerald-500">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-300 text-sm">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="closeAlert()" class="text-emerald-600 dark:text-emerald-300">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        <div class="mt-8">
            @if ($articles->isEmpty())
                <div class="nm-panel p-10 flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="h-48 rounded-lg">
                    <p class="text-lg text-gray-600 dark:text-gray-300">Belum ada berita Newsmaker 23.</p>
                </div>
            @else
                <div class="nm-grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($articles as $article)
                        <div class="nm-card overflow-hidden flex flex-col">
                            <img src="{{ asset($article->image) }}" alt="{{ $article->title_id }}" class="h-48 w-full object-cover">
                            <div class="p-5 flex flex-col gap-3">
                                <div>
                                    <div class="text-sm text-blue-600 dark:text-blue-300 font-semibold">
                                        {{ $article->mainCategory?->name ?? '-' }} / {{ $article->subCategory?->name ?? '-' }}
                                    </div>
                                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $article->title_id }}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $article->title_en }}</p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-semibold">Author:</span> {{ $article->author }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-semibold">Source:</span> {{ $article->source }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: {{ \Illuminate\Support\Str::limit(strip_tags($article->content_id), 90) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    EN: {{ \Illuminate\Support\Str::limit(strip_tags($article->content_en), 90) }}
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <a href="{{ route('newsmaker23.berita.edit', $article->id) }}" class="nm-btn nm-btn-success">
                                        Edit
                                    </a>
                                    <button onclick="showDeleteModal({{ $article->id }}, '{{ $article->title_id }}')" class="nm-btn nm-btn-danger">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="deleteModal" class="fixed inset-0 bg-black/50 flex justify-center items-center hidden z-100 dark:bg-gray-900/75">
            <div class="nm-card p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Konfirmasi Hapus</h2>
                <p class="text-gray-600 dark:text-gray-200">Apakah Anda yakin ingin menghapus berita <b id="articleTitle"></b>?</p>
                <form id="deleteForm" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideDeleteModal()" class="nm-btn nm-btn-ghost">Batal</button>
                        <button type="submit" class="nm-btn nm-btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showDeleteModal(id, title) {
                const modal = document.getElementById("deleteModal");
                const form = document.getElementById("deleteForm");
                const articleTitle = document.getElementById("articleTitle");

                form.action = `{{ route('newsmaker23.berita.destroy', ':id') }}`.replace(':id', id);
                articleTitle.textContent = title;

                modal.classList.remove("hidden");
            }

            function hideDeleteModal() {
                document.getElementById("deleteModal").classList.add("hidden");
            }

            function closeAlert() {
                const alert = document.getElementById("successAlert");
                if (alert) {
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 300);
                }
            }
        </script>
    </div>
</x-app-layout>