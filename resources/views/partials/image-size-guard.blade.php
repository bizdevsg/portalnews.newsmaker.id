{{-- Validasi gambar di sisi browser sebelum submit, supaya form yang sudah
     diisi tidak ke-reset. Batas 2 MB menyamai aturan server (image ... max:2048).
     Pada form tambah, semua kolom gambar wajib diisi dulu sebelum form dikirim,
     jadi gambar yang sudah dipilih tidak hilang gara-gara ada gambar lain yang
     belum diisi atau kegedean. Pada form edit, kolom gambar yang dibiarkan
     kosong berarti memakai gambar lama. --}}
<script>
    (function () {
        const MAX_BYTES = 2 * 1024 * 1024;

        document.querySelectorAll('form').forEach((form) => {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            if (!fileInputs.length) {
                return;
            }

            const methodField = form.querySelector('input[name="_method"]');
            const isEditForm = !!methodField && /^(put|patch)$/i.test(methodField.value);

            const clearHint = (input) => {
                input.classList.remove('border-rose-500');
                input.parentElement?.querySelector('.js-image-size-error')?.remove();
            };

            const showHint = (input, message) => {
                input.classList.add('border-rose-500');
                let hint = input.parentElement?.querySelector('.js-image-size-error');
                if (!hint && input.parentElement) {
                    hint = document.createElement('p');
                    hint.className = 'js-image-size-error mt-2 text-sm font-medium text-rose-600 dark:text-rose-300';
                    input.insertAdjacentElement('afterend', hint);
                }
                if (hint) {
                    hint.textContent = message;
                }
            };

            form.addEventListener('submit', (event) => {
                const problems = [];

                fileInputs.forEach((input) => {
                    const file = input.files && input.files[0];

                    if (!file) {
                        if (input.hasAttribute('required') || !isEditForm) {
                            problems.push([input, 'Gambar ini belum dipilih. Lengkapi dulu — gambar lain yang sudah dipilih tidak akan hilang.']);
                        }
                        return;
                    }

                    if (file.size > MAX_BYTES) {
                        const mb = (file.size / (1024 * 1024)).toFixed(1);
                        problems.push([input, 'Ukuran gambar ' + mb + ' MB melebihi batas 2 MB. Ganti dengan gambar yang lebih kecil — kolom lain tidak perlu diisi ulang.']);
                    }
                });

                if (!problems.length) {
                    return;
                }

                event.preventDefault();
                document.getElementById('modalSubmit')?.classList.add('hidden');

                problems.forEach(([input, message]) => showHint(input, message));
                problems[0][0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            });

            fileInputs.forEach((input) => {
                input.addEventListener('change', () => clearHint(input));
            });
        });
    })();
</script>
