<?php

/**
 * Mapping kategori Joomla (s3cur3_categories.id, extension=com_content)
 * -> [main_category_name, sub_category_name] di newsmaker_main_categories /
 * newsmaker_sub_categories.
 *
 * Nama yang sudah match dengan kategori existing (Oil, Gold Corner, Market
 * Analysis, Silver, Gold, Economy, Global Economy) akan reuse row yang sudah
 * ada (firstOrCreate by name di MigrateJoomlaToNewsmaker). Sisanya dibuatkan
 * main+sub baru dengan nama yang sama persis (ikut pola main==sub yang sudah
 * dipakai untuk 7 kategori existing).
 *
 * Kategori yang SENGAJA di-skip (tidak ada di array ini) — sama seperti
 * migrasi ke beritas: Uncategorised(2), ABOUT(105), UTILITIES(98),
 * System(151), TIKTOK(150), YOUTUBE(149) — konten kosong / halaman statis.
 */

return [
    // ── Reuse kategori existing ────────────────────────────────────
    131 => ['main' => 'Gold', 'sub' => 'Gold'],
    124 => ['main' => 'Gold', 'sub' => 'Gold'],
    142 => ['main' => 'Silver', 'sub' => 'Silver'],
    144 => ['main' => 'Silver', 'sub' => 'Silver'],
    132 => ['main' => 'Oil', 'sub' => 'Oil'],
    85  => ['main' => 'Gold Corner', 'sub' => 'Gold Corner'],
    83  => ['main' => 'Market Analysis', 'sub' => 'Market Analysis'],
    79  => ['main' => 'Economy', 'sub' => 'Economy'],
    97  => ['main' => 'Global Economy', 'sub' => 'Global Economy'],

    // ── Main+sub baru (nama sama, ikut pola existing) ──────────────
    78  => ['main' => 'Market Update', 'sub' => 'Market Update'],
    139 => ['main' => 'US Dollar', 'sub' => 'US Dollar'],
    129 => ['main' => 'Hong Kong', 'sub' => 'Hong Kong'],
    125 => ['main' => 'Hong Kong', 'sub' => 'Hong Kong'],
    128 => ['main' => 'Japan', 'sub' => 'Japan'],
    81  => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    91  => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    92  => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    135 => ['main' => 'USD/JPY', 'sub' => 'USD/JPY'],
    82  => ['main' => 'Global', 'sub' => 'Global'],
    134 => ['main' => 'EUR/USD', 'sub' => 'EUR/USD'],
    137 => ['main' => 'AUD/USD', 'sub' => 'AUD/USD'],
    138 => ['main' => 'GBP/USD', 'sub' => 'GBP/USD'],
    146 => ['main' => 'Analysis & Opinion', 'sub' => 'Analysis & Opinion'],
    155 => ['main' => 'Crypto', 'sub' => 'Crypto'],
    136 => ['main' => 'USD/CHF', 'sub' => 'USD/CHF'],
    156 => ['main' => 'Market Academy', 'sub' => 'Market Academy'],
    104 => ['main' => 'Currencies', 'sub' => 'Currencies'],
    102 => ['main' => 'Precious Metals', 'sub' => 'Precious Metals'],
    88  => ['main' => 'Asia', 'sub' => 'Asia'],
    103 => ['main' => 'Energy', 'sub' => 'Energy'],
    86  => ['main' => 'News', 'sub' => 'News'],
    93  => ['main' => 'Politics', 'sub' => 'Politics'],
    90  => ['main' => 'Europe', 'sub' => 'Europe'],
    84  => ['main' => 'Announcement', 'sub' => 'Announcement'],
    89  => ['main' => 'United States', 'sub' => 'United States'],
    87  => ['main' => 'Commodity', 'sub' => 'Commodity'],
];
