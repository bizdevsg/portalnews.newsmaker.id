-- Plain SQL import template without JSON functions.
-- Fill tmp_economic_payload_seed with one row per payload item.

START TRANSACTION;

DROP TEMPORARY TABLE IF EXISTS tmp_economic_payload_seed;
CREATE TEMPORARY TABLE tmp_economic_payload_seed (
    country VARCHAR(50) NOT NULL,
    impact ENUM('Low', 'Medium', 'High') NOT NULL,
    figures VARCHAR(255) NOT NULL,
    sources TEXT NOT NULL,
    measures TEXT NULL,
    usual_effect TEXT NULL,
    frequency TEXT NULL,
    next_released TEXT NULL,
    notes LONGTEXT NULL,
    why_trader_care LONGTEXT NULL,
    detail_date DATE NOT NULL,
    detail_time VARCHAR(10) NULL,
    previous_value VARCHAR(100) NULL,
    forecast_value VARCHAR(100) NULL,
    actual_value VARCHAR(100) NULL,
    isBankHoliday TINYINT(1) NOT NULL DEFAULT 0,
    bankHolidayNote TEXT NULL
);

INSERT INTO tmp_economic_payload_seed (
    country,
    impact,
    figures,
    sources,
    measures,
    usual_effect,
    frequency,
    next_released,
    notes,
    why_trader_care,
    detail_date,
    detail_time,
    previous_value,
    forecast_value,
    actual_value,
    isBankHoliday,
    bankHolidayNote
) VALUES
(
    'US',
    'Low',
    'Construction Spending m/m',
    'Biro Sensus',
    'Perubahan total jumlah pengeluaran dari pengembang rumah yang dibelanjakan untuk proyek-proyek konstruksi.',
    'Aktual > Perkiraan = Positif',
    'Rilis bulanan, sekitar 30 hari setelah bulan berjalan',
    'No Information',
    NULL,
    NULL,
    '2026-03-23',
    '21:00',
    '0.8%',
    '0.1%',
    '-0.3%',
    0,
    NULL
),
(
    'EUR',
    'Low',
    'Consumer Confidence',
    'Komisi Statistik Wilayah Euro (Eurostat)',
    'Tingkat indeks difusi berdasarkan konsumen yang disurvei;',
    'Aktual > Perkiraan = Positif',
    'Rilis bulanan, sekitar 22 hari setelah bulan berjalan',
    'No Information',
    'Di atas 0 menunjukkan optimisme, di bawah menunjukkan pesimisme. Ada 2 versi laporan ini yang dirilis dengan selang waktu sekitar satu minggu - Flash dan Final. Rilisan Flash, yang pertama kali dilaporkan oleh sumber tersebut pada bulan Januari 2010, merupakan rilis paling awal dan cenderung memiliki dampak paling besar. Final tidak dilaporkan karena kurang penting;',
    'Kepercayaan finansial merupakan indikator utama belanja konsumen, yang menyumbang sebagian besar aktivitas perekonomian secara keseluruhan;',
    '2026-03-23',
    '22:00',
    '-12',
    '-15',
    '-16',
    0,
    NULL
);

-- Add the remaining payload rows above using the same pattern.

INSERT INTO economic_calendar_categories (
    country,
    impact,
    figures,
    sources,
    measures,
    usual_effect,
    frequency,
    next_released,
    notes,
    isBankHoliday,
    bankHolidayNote,
    why_trader_care,
    created_at,
    updated_at
)
SELECT DISTINCT
    seed.country,
    seed.impact,
    seed.figures,
    seed.sources,
    seed.measures,
    seed.usual_effect,
    seed.frequency,
    seed.next_released,
    seed.notes,
    seed.isBankHoliday,
    seed.bankHolidayNote,
    seed.why_trader_care,
    NOW(),
    NOW()
FROM tmp_economic_payload_seed AS seed
ON DUPLICATE KEY UPDATE
    sources = VALUES(sources),
    measures = VALUES(measures),
    usual_effect = VALUES(usual_effect),
    frequency = VALUES(frequency),
    next_released = VALUES(next_released),
    notes = VALUES(notes),
    isBankHoliday = VALUES(isBankHoliday),
    bankHolidayNote = VALUES(bankHolidayNote),
    why_trader_care = VALUES(why_trader_care),
    updated_at = NOW();

DELETE ec
FROM economic_calendars AS ec
JOIN economic_calendar_categories AS cat
    ON cat.id = ec.economic_calendar_category_id
JOIN (
    SELECT DISTINCT country, impact, figures
    FROM tmp_economic_payload_seed
) AS src
    ON src.country = cat.country
   AND src.impact = cat.impact
   AND src.figures = cat.figures;

INSERT INTO economic_calendars (
    economic_calendar_category_id,
    date,
    time,
    country,
    impact,
    figures,
    previous,
    forecast,
    actual,
    sources,
    measures,
    usual_effect,
    frequency,
    next_released,
    notes,
    isBankHoliday,
    bankHolidayNote,
    why_trader_care,
    created_at,
    updated_at
)
SELECT
    cat.id,
    seed.detail_date,
    seed.detail_time,
    cat.country,
    cat.impact,
    cat.figures,
    seed.previous_value,
    seed.forecast_value,
    seed.actual_value,
    cat.sources,
    cat.measures,
    cat.usual_effect,
    cat.frequency,
    cat.next_released,
    cat.notes,
    cat.isBankHoliday,
    cat.bankHolidayNote,
    cat.why_trader_care,
    NOW(),
    NOW()
FROM tmp_economic_payload_seed AS seed
JOIN economic_calendar_categories AS cat
    ON cat.country = seed.country
   AND cat.impact = seed.impact
   AND cat.figures = seed.figures
ORDER BY seed.detail_date DESC, seed.detail_time DESC, cat.id DESC;

COMMIT;
