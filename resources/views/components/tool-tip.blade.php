{{-- Dekstop --}}
<div class="hidden md:block">
    <div class="relative flex items-center gap-2">
        <!-- Icon Info -->
        <div class="group relative">
            <i class="fa-solid fa-circle-info text-blue-500 cursor-pointer"></i>

            <!-- Tooltip (Posisi ke bawah) -->
            <div
                class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-40 p-2 text-sm text-center text-white bg-gray-800 dark:bg-gray-700 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                Jam disini berdasarkan waktu di browser anda.
            </div>
        </div>
    </div>
</div>

{{-- Mobile --}}
<div class="block md:hidden">
    <div class="relative flex items-center gap-2">
        <!-- Icon Info -->
        <div class="relative">
            <i class="fa-solid fa-circle-info text-blue-500 cursor-pointer" onclick="toggleTooltip()"></i>

            <!-- Tooltip (Posisi ke bawah) -->
            <div id="tooltip"
                class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-40 p-2 text-sm text-center text-white bg-gray-800 dark:bg-gray-700 rounded-lg shadow-lg opacity-0 invisible transition-opacity duration-200">
                Jam disini berdasarkan waktu di browser anda.
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTooltip() {
        let tooltip = document.getElementById("tooltip");
        tooltip.classList.toggle("opacity-100");
        tooltip.classList.toggle("invisible");
    }
</script>