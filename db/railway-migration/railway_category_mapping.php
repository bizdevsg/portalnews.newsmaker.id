<?php

/**
 * Mapping kategori dari dump Railway (`news`.`category`, uppercase-ish
 * strings) -> [main_category_name, sub_category_name] di
 * newsmaker_main_categories / newsmaker_sub_categories.
 *
 * Nama yang sudah match dengan kategori existing (dibuat oleh migrasi Joomla
 * sebelumnya, lihat db/joomla-migration/newsmaker_category_mapping.php) akan
 * reuse row yang sudah ada (firstOrCreate by name di
 * MigrateRailwayToNewsmaker). Sisanya dibuatkan main+sub baru.
 *
 * FISCAL, MONETARY, dan ANALYSIS digabung ke kategori gabungannya
 * (Fiscal & Monetary / Analysis & Opinion) karena kelihatannya cuma
 * separuh label dari kategori itu, bukan topik yang benar-benar beda.
 * HANGSENG dan NIKKEI dibuatkan kategori baru karena memang belum ada.
 *
 * Lookup di command dilakukan dengan strtoupper(trim($category)), jadi key
 * di sini harus uppercase.
 */

return [
    'GOLD' => ['main' => 'Gold', 'sub' => 'Gold'],
    'OIL' => ['main' => 'Oil', 'sub' => 'Oil'],
    'US DOLLAR' => ['main' => 'US Dollar', 'sub' => 'US Dollar'],
    'MARKET UPDATE' => ['main' => 'Market Update', 'sub' => 'Market Update'],
    'MARKET ANALISYS' => ['main' => 'Market Analysis', 'sub' => 'Market Analysis'],
    'SILVER' => ['main' => 'Silver', 'sub' => 'Silver'],
    'GLOBAL ECONOMY' => ['main' => 'Global Economy', 'sub' => 'Global Economy'],
    'FISCAL & MONETARY' => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    'FISCAL' => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    'MONETARY' => ['main' => 'Fiscal & Monetary', 'sub' => 'Fiscal & Monetary'],
    'ECONOMY' => ['main' => 'Economy', 'sub' => 'Economy'],
    'GLOBAL' => ['main' => 'Global', 'sub' => 'Global'],
    'ANALYSIS & OPINION' => ['main' => 'Analysis & Opinion', 'sub' => 'Analysis & Opinion'],
    'ANALYSIS' => ['main' => 'Analysis & Opinion', 'sub' => 'Analysis & Opinion'],
    'USD/JPY' => ['main' => 'USD/JPY', 'sub' => 'USD/JPY'],
    'HANGSENG' => ['main' => 'Hang Seng', 'sub' => 'Hang Seng'],
    'NIKKEI' => ['main' => 'Nikkei', 'sub' => 'Nikkei'],
    'EUR/USD' => ['main' => 'EUR/USD', 'sub' => 'EUR/USD'],
    'CRYPTO' => ['main' => 'Crypto', 'sub' => 'Crypto'],
    'GBP/USD' => ['main' => 'GBP/USD', 'sub' => 'GBP/USD'],
    'AUD/USD' => ['main' => 'AUD/USD', 'sub' => 'AUD/USD'],
    'HONGKONG' => ['main' => 'Hong Kong', 'sub' => 'Hong Kong'],
    'USD/CHF' => ['main' => 'USD/CHF', 'sub' => 'USD/CHF'],
    'JAPAN' => ['main' => 'Japan', 'sub' => 'Japan'],
    'EUROPE' => ['main' => 'Europe', 'sub' => 'Europe'],
    'PRECIOUS METALS' => ['main' => 'Precious Metals', 'sub' => 'Precious Metals'],
    'ENERGY' => ['main' => 'Energy', 'sub' => 'Energy'],
    'POLITICS' => ['main' => 'Politics', 'sub' => 'Politics'],
    'CURRENCIES' => ['main' => 'Currencies', 'sub' => 'Currencies'],
    'ASIA' => ['main' => 'Asia', 'sub' => 'Asia'],
];
