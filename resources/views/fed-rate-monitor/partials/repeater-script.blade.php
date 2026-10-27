<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('fedRateForm');
        const rowsContainer = document.getElementById('rowsContainer');
        const rowTemplate = document.getElementById('rowTemplate');
        const addRowButton = document.getElementById('addRowButton');
        const pasteInput = document.getElementById('pasteInput');
        const parsePasteButton = document.getElementById('parsePasteButton');
        const parseFeedback = document.getElementById('parseFeedback');
        const probabilityWarning = document.getElementById('probabilityWarning');

        let rowIndex = 0;

        const addRow = (data = {}) => {
            const fragment = rowTemplate.content.cloneNode(true);
            const row = fragment.querySelector('.js-row');
            const index = rowIndex++;

            const fieldMap = {
                target_lower: row.querySelector('.js-target-lower'),
                target_upper: row.querySelector('.js-target-upper'),
                current_probability: row.querySelector('.js-current'),
                previous_day_probability: row.querySelector('.js-prev-day'),
                previous_week_probability: row.querySelector('.js-prev-week'),
            };

            Object.entries(fieldMap).forEach(([field, input]) => {
                input.name = `rows[${index}][${field}]`;
                if (data[field] !== undefined && data[field] !== null) {
                    input.value = data[field];
                }
                input.addEventListener('input', updateProbabilityWarning);
            });

            row.querySelector('.js-remove-row').addEventListener('click', () => {
                row.remove();
                updateProbabilityWarning();
            });

            rowsContainer.appendChild(row);
            updateProbabilityWarning();
        };

        const updateProbabilityWarning = () => {
            const currentInputs = rowsContainer.querySelectorAll('.js-current');
            let total = 0;
            let hasValue = false;

            currentInputs.forEach((input) => {
                const value = parseFloat(input.value);
                if (!Number.isNaN(value)) {
                    total += value;
                    hasValue = true;
                }
            });

            if (!hasValue || Math.abs(total - 100) <= 0.5) {
                probabilityWarning.classList.add('hidden');
                return;
            }

            probabilityWarning.textContent = `Total probabilitas "Current" saat ini ${total.toFixed(1)}%, biasanya harus mendekati 100%. Periksa kembali sebelum menyimpan.`;
            probabilityWarning.classList.remove('hidden');
        };

        const parseLine = (line) => {
            const rangeMatch = line.match(/(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)/);
            if (!rangeMatch) {
                return null;
            }

            const rest = line.slice(rangeMatch.index + rangeMatch[0].length);
            const numbers = (rest.match(/\d+(?:\.\d+)?/g) || []).map(Number);

            return {
                target_lower: rangeMatch[1],
                target_upper: rangeMatch[2],
                current_probability: numbers[0] ?? '',
                previous_day_probability: numbers[1] ?? '',
                previous_week_probability: numbers[2] ?? '',
            };
        };

        addRowButton.addEventListener('click', () => addRow());

        parsePasteButton.addEventListener('click', () => {
            const lines = pasteInput.value.split('\n').map((line) => line.trim()).filter(Boolean);
            const parsedRows = lines.map(parseLine).filter(Boolean);

            if (parsedRows.length === 0) {
                parseFeedback.textContent = 'Tidak ada baris valid yang ditemukan. Pastikan formatnya "lower - upper  current%  prevDay%  prevWeek%".';
                parseFeedback.classList.remove('hidden');
                parseFeedback.classList.add('text-rose-600', 'dark:text-rose-300');
                return;
            }

            rowsContainer.innerHTML = '';
            parsedRows.forEach((row) => addRow(row));

            parseFeedback.textContent = `${parsedRows.length} baris berhasil diisi dari paste.`;
            parseFeedback.classList.remove('hidden', 'text-rose-600', 'dark:text-rose-300');
            parseFeedback.classList.add('text-emerald-600', 'dark:text-emerald-300');
        });

        const initialRows = JSON.parse(document.getElementById('initialRowsData')?.textContent || '[]');
        if (initialRows.length > 0) {
            initialRows.forEach((row) => addRow(row));
        } else {
            addRow();
        }

        form.addEventListener('submit', (event) => {
            if (rowsContainer.querySelectorAll('.js-row').length === 0) {
                event.preventDefault();
                alert('Tambahkan minimal 1 baris probabilitas sebelum menyimpan.');
            }
        });
    });
</script>
