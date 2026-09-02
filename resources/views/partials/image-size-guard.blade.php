{{-- Cek ukuran gambar di sisi browser sebelum submit, supaya form yang sudah
     diisi tidak ke-reset gara-gara gambar kegedean. Batas 2 MB menyamai aturan
     server (image ... max:2048). --}}
<script>
    (function () {
        const MAX_BYTES = 2 * 1024 * 1024;

        document.querySelectorAll('form').forEach((form) => {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            if (!fileInputs.length) {
                return;
            }

            const clearHint = (input) => {
                input.classList.remove('border-rose-500');
                input.parentElement?.querySelector('.js-image-size-error')?.remove();
            };

            form.addEventListener('submit', (event) => {
                const oversized = [];
                fileInputs.forEach((input) => {
                    const file = input.files && input.files[0];
                    if (file && file.size > MAX_BYTES) {
                        oversized.push(input);
                    }
                });

                if (!oversized.length) {
                    return;
                }

                event.preventDefault();
                document.getElementById('modalSubmit')?.classList.add('hidden');

                oversized.forEach((input) => {
                    input.classList.add('border-rose-500');
                    let hint = input.parentElement?.querySelector('.js-image-size-error');
                    if (!hint && input.parentElement) {
                        hint = document.createElement('p');
                        hint.className = 'js-image-size-error mt-2 text-sm font-medium text-rose-600 dark:text-rose-300';
                        input.insertAdjacentElement('afterend', hint);
                    }
                    if (hint) {
                        const mb = (input.files[0].size / (1024 * 1024)).toFixed(1);
                        hint.textContent = 'Ukuran gambar ' + mb + ' MB melebihi batas 2 MB. Ganti dengan gambar yang lebih kecil — kolom lain tidak perlu diisi ulang.';
                    }
                });

                oversized[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            });

            fileInputs.forEach((input) => {
                input.addEventListener('change', () => clearHint(input));
            });
        });
    })();
</script>
