<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex flex-col gap-8 h-full">

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $cards = [
            ['title' => 'Jumlah Kategori', 'count' => $widget['category'] ?? '0', 'icon' => 'fa-layer-group', 'color' =>
            '#F59E0B', 'link' => route('kategori.index')],
            ['title' => 'Jumlah Berita', 'count' => $widget['berita'] ?? '0', 'icon' => 'fa-newspaper', 'color' =>
            '#3B82F6', 'link' => route('kategori.index')],
            ['title' => 'Jumlah Admin', 'count' => ($widget['admin'] ?? '0') . ' Admin', 'icon' => 'fa-users', 'color'
            => '#10B981', 'link' => route('user.index')],
            ['title' => 'Jumlah Superadmin', 'count' => ($widget['superadmin'] ?? '0') . ' Superadmin', 'icon' =>
            'fa-user-shield', 'color' => '#8B5CF6', 'link' => route('user.index')],
            ];
            @endphp

            @foreach ($cards as $card)
            <a href="{{ $card['link'] }}"
                class="flex flex-col gap-4 border-l-[6px] bg-white dark:bg-gray-800 p-5 md:p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:scale-[1.02]"
                style="border-left-color: {{ $card['color'] }};">

                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-full shadow-md" style="background-color: {{ $card['color'] }}33;">
                        <i class="fa-solid {{ $card['icon'] }} text-2xl" style="color: {{ $card['color'] }};"></i>
                    </div>
                    <h1 class="font-semibold text-lg text-gray-700 dark:text-gray-300">{{ $card['title'] }}</h1>
                </div>

                <div class="flex flex-col gap-2">
                    <p class="text-gray-500 dark:text-gray-400 uppercase text-sm">
                        Total {{ strtolower($card['title']) }} yang tersedia
                    </p>
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-none">{{ $card['count'] }}</h2>
                </div>
            </a>
            @endforeach
        </div>

        <div class="flex flex-col gap-3 items-center md:items-start">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-200 text-center md:text-left">Trading
                Widgets
            </h1>
            <div class="h-2 w-25 bg-blue-400 rounded-full"></div>
        </div>

        <!-- TradingView Widgets (Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
            $tradingSymbols = [
            ['symbol' => 'OANDA:XAUUSD', 'title' => 'Gold'],
            ['symbol' => 'ACTIVTRADES:SILVER', 'title' => 'Silver'],
            ['symbol' => 'MARKETSCOM:OIL', 'title' => 'Oil'],
            ];
            @endphp

            @foreach ($tradingSymbols as $trading)
            <div class="shadow-lg rounded-lg overflow-hidden border-1 border-gray-800">
                <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <script type="text/javascript"
                        src="https://s3.tradingview.com/external-embedding/embed-widget-mini-symbol-overview.js" async>
                        {
                                "symbol": "{{ $trading['symbol'] }}",
                                "width": "100%",
                                "height": "200",
                                "locale": "en",
                                "dateRange": "1D",
                                "colorTheme": "light",
                                "isTransparent": false,
                                "autosize": true,
                                "largeChartUrl": ""
                            }
                    </script>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex flex-col gap-3 items-center md:items-start">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-200 text-center md:text-left">
                Trading Chart
            </h1>
            <div class="h-2 w-25 bg-blue-400 rounded-full"></div>
        </div>

        <!-- Trading Chart Besar -->
        <div class="tradingview-widget-container rounded-lg border-1 border-gray-800">
            <div class="tradingview-widget-container__widget"></div>
            <div class="tradingview-widget-copyright"></div>
            <script type="text/javascript"
                src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                {
                    "width": "100%",
                    "height": "700",
                    "symbol": "OANDA:XAUUSD",
                    "interval": "D",
                    "timezone": "Etc/UTC",
                    "theme": "light",
                    "style": "1",
                    "locale": "en",
                    "allow_symbol_change": true,
                    "support_host": "https://www.tradingview.com"
                }
            </script>
        </div>
    </div>
</x-app-layout>