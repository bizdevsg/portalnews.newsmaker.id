<div id="realTimeClock" class="text-lg font-bold"></div>

<script>
    function updateClock() {
        let now = new Date();
        let timeString = now.toLocaleTimeString("id-ID", { hour12: false }); // Format 24 jam
        document.getElementById("realTimeClock").textContent = timeString;
    }

    // Perbarui setiap detik
    setInterval(updateClock, 1000);
    updateClock(); // Jalankan langsung saat halaman dimuat
</script>