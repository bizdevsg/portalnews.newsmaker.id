<script>
    function openModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[data-modal]').forEach(function(modal) {
                modal.classList.add('hidden');
            });
        }
    });

    document.querySelectorAll('[data-modal]').forEach(function(modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

    const pivotForm = document.getElementById('pivotForm');
    const bankHolidayCheckbox = document.getElementById('isBankHoliday');
    const ohlcContainer = document.getElementById('ohlcFields');
    const holidayDescription = document.getElementById('bankHolidayDescription');
    const ohlcInputs = Array.from(document.querySelectorAll('[data-ohlc-input]'));

    function syncHolidayState() {
        if (!bankHolidayCheckbox) {
            return;
        }

        const isHoliday = bankHolidayCheckbox.checked;

        if (ohlcContainer) {
            ohlcContainer.classList.toggle('hidden', isHoliday);
        }

        if (holidayDescription) {
            holidayDescription.classList.toggle('hidden', !isHoliday);
        }

        ohlcInputs.forEach(function(input) {
            input.disabled = isHoliday;
            input.required = !isHoliday;
        });
    }

    bankHolidayCheckbox?.addEventListener('change', syncHolidayState);
    syncHolidayState();

    pivotForm?.addEventListener('submit', function() {
        if (bankHolidayCheckbox?.checked) {
            ohlcInputs.forEach(function(input) {
                input.disabled = false;
                input.required = false;
            });
        }
    });
</script>
