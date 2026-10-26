{{-- Modal konfirmasi hapus berita + script-nya. Dipasangkan dengan
     partials/articles-section.blade.php (tombol Hapus di situ men-trigger
     modal ini lewat data-delete-form / data-delete-title). --}}
<div id="deleteArticleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4" aria-hidden="true">
    <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900 md:w-[28rem]">
        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hapus Berita</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Artikel <span id="deleteArticleName" class="font-semibold text-slate-900 dark:text-slate-100"></span> akan dihapus.
        </p>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button type="button" id="cancelDeleteArticleButton" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                Batal
            </button>
            <button type="button" id="confirmDeleteArticleButton" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:opacity-70">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = document.getElementById('deleteArticleModal');
        const deleteName = document.getElementById('deleteArticleName');
        const confirmButton = document.getElementById('confirmDeleteArticleButton');
        const cancelButton = document.getElementById('cancelDeleteArticleButton');
        const successAlert = document.getElementById('successAlert');
        const closeSuccessAlert = document.getElementById('closeSuccessAlert');
        const deleteButtons = document.querySelectorAll('.js-delete-article');

        let activeDeleteForm = null;

        const openModal = (formId, title) => {
            const form = document.getElementById(formId);
            if (!deleteModal || !deleteName || !form) {
                return;
            }

            activeDeleteForm = form;
            deleteName.textContent = title || '';
            confirmButton.disabled = false;
            confirmButton.textContent = 'Hapus';
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = () => {
            activeDeleteForm = null;
            deleteModal?.classList.add('hidden');
            deleteModal?.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        };

        deleteButtons.forEach((button) => {
            button.addEventListener('click', () => {
                openModal(button.dataset.deleteForm, button.dataset.deleteTitle);
            });
        });

        confirmButton?.addEventListener('click', () => {
            if (!activeDeleteForm) {
                closeModal();
                return;
            }

            confirmButton.disabled = true;
            confirmButton.textContent = 'Menghapus...';
            activeDeleteForm.submit();
        });

        cancelButton?.addEventListener('click', closeModal);

        deleteModal?.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        closeSuccessAlert?.addEventListener('click', () => {
            if (!successAlert) {
                return;
            }

            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 300);
        });
    });
</script>
