<x-app-layout>
    <div class="mx-4 sm:mx-6 lg:mx-8 my-8">
        <form action="{{ route('calendar.update', $calendar->id) }}" method="POST"
            class="flex flex-col gap-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf
            @method('PUT')

            <div class="flex justify-between items-center">
                <button type="button" onclick="toggleModal('modalKembali')"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-100 cursor-pointer">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
                </button>

                <h1 class="text-xl px-4 md:px-0 text-center md:text-2xl text-gray-800 dark:text-gray-100 font-bold">Edit
                    Kalender Ekonomi
                </h1>

                <button type="button" onclick="toggleModal('modalSubmit')"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden md:block">Simpan</span>
                </button>
            </div>

            {{-- Form Fields --}}
            <div class="flex flex-col gap-4">
                {{-- Figures --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="figures" class="font-medium text-gray-700 dark:text-gray-100">Peristiwa</label>
                    <input type="text" name="figures" id="figures" required
                        value="{{ old('figures', $calendar->figures) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('figures')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Sources --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="sources" class="font-medium text-gray-700 dark:text-gray-100">Sources</label>
                    <input type="text" name="sources" id="sources" value="{{ old('sources', $calendar->sources) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('sources')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Measures --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="measures" class="font-medium text-gray-700 dark:text-gray-100">Measures</label>
                    <input type="text" name="measures" id="measures" value="{{ old('measures', $calendar->measures) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('measures')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Usual Effect --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="usual_effect" class="font-medium text-gray-700 dark:text-gray-100">Usual Effect</label>
                    <input type="text" name="usual_effect" id="usual_effect"
                        value="{{ old('usual_effect', $calendar->usual_effect) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('usual_effect')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Frequency --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="frequency" class="font-medium text-gray-700 dark:text-gray-100">Frequency</label>
                    <input type="text" name="frequency" id="frequency"
                        value="{{ old('frequency', $calendar->frequency) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('frequency')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Next Released --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="next_released" class="font-medium text-gray-700 dark:text-gray-100">Next
                        Released</label>
                    <input type="text" name="next_released" id="next_released"
                        value="{{ old('next_released', $calendar->next_released) }}"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    @error('next_released')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="notes" class="font-medium text-gray-700 dark:text-gray-100">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">{{ old('notes', $calendar->notes) }}</textarea>
                    @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Why Traders Care --}}
                <div class="form-group flex flex-col gap-2">
                    <label for="why_traders_care" class="font-medium text-gray-700 dark:text-gray-100">Why Traders
                        Care</label>
                    <textarea name="why_traders_care" id="why_traders_care" rows="3"
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">{{ old('why_traders_care', $calendar->why_traders_care) }}</textarea>
                    @error('why_traders_care')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Date and Time --}}
                <div class="flex w-full gap-4">
                    <div class="w-full flex flex-col gap-2">
                        <label for="date" class="font-medium text-gray-700 dark:text-gray-100">Tanggal</label>
                        <input type="date" name="date" id="date"
                            value="{{ old('date', \Carbon\Carbon::parse($calendar->date)->format('Y-m-d')) }}"
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                        @error('date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full flex flex-col gap-2">
                        <label for="time" class="font-medium text-gray-700 dark:text-gray-100">Waktu</label>
                        <input type="text" name="time" id="time" placeholder="Contoh: 13:00/10th"
                            value="{{ old('time', $calendar->time) }}"
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                        @error('time')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Impact --}}
                <div class="flex flex-col gap-2">
                    <label for="impact" class="font-medium text-gray-700 dark:text-gray-100">Impact</label>
                    <select name="impact" id="impact" required
                        class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                        <option value="">Pilih Impact</option>
                        <option value="Low" {{ old('impact', $calendar->impact) == 'Low' ? 'selected' : '' }}>Low
                        </option>
                        <option value="Medium" {{ old('impact', $calendar->impact) == 'Medium' ? 'selected' : ''
                            }}>Medium</option>
                        <option value="High" {{ old('impact', $calendar->impact) == 'High' ? 'selected' : '' }}>High
                        </option>
                    </select>
                    @error('impact')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-medium text-gray-700 dark:text-gray-100">Negara</label>
                    <div class="relative">
                        <button type="button" id="dropdownButton"
                            class="w-full flex justify-between items-center px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:outline-none transition-all cursor-pointer">
                            <span class="flex items-center gap-2">
                                @php
                                $countries = [
                                ['codeCountry' => 'AUD', 'code' => 'AU' , 'name' => 'Australia'],
                                ['codeCountry' => 'CAD', 'code' => 'CA', 'name' => 'Canada'],
                                ['codeCountry' => 'CHF', 'code' => 'CH' , 'name' => 'Switzerland'],
                                ['codeCountry' => 'CHN', 'code' => 'CN', 'name' => 'China'],
                                ['codeCountry' => 'EUR', 'code' => 'EU' , 'name' => 'European Union'],
                                ['codeCountry' => 'GBP', 'code' => 'GB', 'name' => 'United Kingdom'],
                                ['codeCountry' => 'IDN', 'code' => 'ID' , 'name' => 'Indonesia'],
                                ['codeCountry' => 'JPN', 'code' => 'JP', 'name' => 'Japan'],
                                ['codeCountry' => 'US', 'code' => 'US' , 'name' => 'United States'],
                                ];

                                $selected = collect($countries)->firstWhere('codeCountry', old('country',
                                $calendar->country));
                                @endphp
                                <img src="https://flagsapi.com/{{ $selected['code'] }}/shiny/24.png">
                                {{ $selected['codeCountry'] }} - {{ $selected['name'] }}
                            </span>
                            <svg class="w-4 h-4 ml-2 transition-transform duration-300" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 12a1 1 0 01-.7-.3l-3-3a1 1 0 011.4-1.4L10 9.6l2.3-2.3a1 1 0 111.4 1.4l-3 3a1 1 0 01-.7.3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="hidden" name="country" id="selectedCountry"
                            value="{{ old('country', $calendar->country) }}">
                        <ul id="dropdownList"
                            class="absolute z-10 mt-2 w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-md shadow-md hidden max-h-60 overflow-y-auto"
                            role="listbox">
                            @php
                            $countries = [
                            ['codeCountry' => 'AUD', 'code' => 'AU' , 'name' => 'Australia'],
                            ['codeCountry' => 'CAD', 'code' => 'CA', 'name' => 'Canada'],
                            ['codeCountry' => 'CHF', 'code' => 'CH' , 'name' => 'Switzerland'],
                            ['codeCountry' => 'CHN', 'code' => 'CN', 'name' => 'China'],
                            ['codeCountry' => 'EUR', 'code' => 'EU' , 'name' => 'European Union'],
                            ['codeCountry' => 'GBP', 'code' => 'GB', 'name' => 'United Kingdom'],
                            ['codeCountry' => 'IDN', 'code' => 'ID' , 'name' => 'Indonesia'],
                            ['codeCountry' => 'JPN', 'code' => 'JP', 'name' => 'Japan'],
                            ['codeCountry' => 'US', 'code' => 'US' , 'name' => 'United States'],
                            ];
                            @endphp
                            @foreach ($countries as $country)
                            @if ($country['code'] == 'EU')
                            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-2 text-gray-800 dark:text-gray-100"
                                data-value="{{ $country['codeCountry'] }}" role="option" tabindex="0">
                                <img src="https://flagcdn.com/w40/{{ strtolower($country['code']) }}.png"
                                    class="w-5 h-4" alt="{{ $country['name'] }}">
                                {{ $country['codeCountry'] }} - {{ $country['name'] }}
                            </li>
                            @else
                            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center gap-2 text-gray-800 dark:text-gray-100"
                                data-value="{{ $country['codeCountry'] }}" role="option" tabindex="0">
                                <img src="https://flagsapi.com/{{ $country['code'] }}/shiny/24.png">
                                {{ $country['codeCountry'] }} - {{ $country['name'] }}
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                    @error('country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full flex flex-col gap-2">
                        <label for="previous" class="font-medium text-gray-700 dark:text-gray-100">Previous</label>
                        <input type="text" name="previous" id="previous"
                            value="{{ old('previous', $calendar->previous) }}"
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2">
                        @error('previous')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full flex flex-col gap-2">
                        <label for="forecast" class="font-medium text-gray-700 dark:text-gray-100">Forecast</label>
                        <input type="text" name="forecast" id="forecast"
                            value="{{ old('forecast', $calendar->forecast) }}"
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2">
                        @error('forecast')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full flex flex-col gap-2">
                        <label for="actual" class="font-medium text-gray-700 dark:text-gray-100">Actual</label>
                        <input type="text" name="actual" id="actual" value="{{ old('actual', $calendar->actual) }}"
                            class="rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 px-3 py-2">
                        @error('actual')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Modal Submit --}}
            <div id="modalSubmit"
                class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">Konfirmasi</h2>
                    <p class="dark:text-gray-300">Apakah Anda yakin ingin menyimpan perubahan ini?</p>
                    <div class="flex justify-end mt-4">
                        <button onclick="toggleModal('modalSubmit')"
                            class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg cursor-pointer">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg cursor-pointer">Ya,
                            Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Kembali --}}
    <div id="modalKembali"
        class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">Konfirmasi</h2>
            <p class="dark:text-gray-300">Apakah Anda yakin ingin kembali? Perubahan tidak akan disimpan.</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal('modalKembali')"
                    class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg cursor-pointer">Batal</button>
                <a href="{{ route('calendar.index') }}"
                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg cursor-pointer">Ya, Kembali</a>
            </div>
        </div>
    </div>

    {{-- Dropdown Script --}}
    <script>
        const button = document.getElementById("dropdownButton");
        const list = document.getElementById("dropdownList");
        const icon = button.querySelector("svg");
        const hiddenInput = document.getElementById("selectedCountry");

        button.addEventListener("click", () => {
            list.classList.toggle("hidden");
            icon.classList.toggle("rotate-180");
        });

        list.querySelectorAll("li").forEach(item => {
            item.addEventListener("click", () => {
                button.querySelector("span").innerHTML = item.innerHTML;
                hiddenInput.value = item.dataset.value;
                list.classList.add("hidden");
                icon.classList.remove("rotate-180");
            });
        });

        document.addEventListener("click", (e) => {
            if (!button.contains(e.target) && !list.contains(e.target)) {
                list.classList.add("hidden");
                icon.classList.remove("rotate-180");
            }
        });
    </script>
    {{-- Script Modal --}}
    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>