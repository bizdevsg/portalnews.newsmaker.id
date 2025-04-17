@props([
'text' => 'Jam disini berdasarkan waktu di browser anda.',
'mobileId' => 'tooltip-' . uniqid(), // unik untuk tiap instance
])

{{-- Desktop --}}
<div class="hidden md:block">
    <div class="relative flex items-center gap-2">
        <div class="group relative">
            <i class="fa-solid fa-circle-info text-blue-500 cursor-pointer"></i>

            <div
                class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-40 p-2 text-sm text-center text-white bg-gray-800/40 dark:bg-gray-700/40 backdrop-blur-sm rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                {{ $text }}
            </div>
        </div>
    </div>
</div>

{{-- Mobile --}}
<div class="block md:hidden">
    <div class="relative flex items-center gap-2">
        <div class="relative">
            <i class="fa-solid fa-circle-info text-blue-500 cursor-pointer"
                onclick="toggleTooltip('{{ $mobileId }}')"></i>

            <div id="{{ $mobileId }}"
                class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-40 p-2 text-sm text-center text-white bg-gray-800/40 dark:bg-gray-700/40 backdrop-blur-sm rounded-lg shadow-lg opacity-0 invisible transition-opacity duration-200">
                {{ $text }}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleTooltip(id) {
        const tooltip = document.getElementById(id);
        if (!tooltip) return;

        tooltip.classList.toggle("opacity-0");
        tooltip.classList.toggle("invisible");
        tooltip.classList.toggle("opacity-100");
    }
</script>