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

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('[data-modal]').forEach(modal => {
            modal.classList.add('hidden');
        });
    });
</script>
