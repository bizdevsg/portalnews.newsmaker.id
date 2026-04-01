-- MySQL 8.0+ import query for economic calendar payloads.
-- Schema target:
--   1. economic_calendar_categories
--   2. economic_calendars
--
-- Usage:
--   1. Put the raw JSON payload into a file, for example:
--      C:/laragon/tmp/calendar_payload.json
--   2. Set @payload with LOAD_FILE() or from your SQL client/app.
--   3. Run this script.

SET @payload = LOAD_FILE( 'C:/Users/User/Downloads/api.json' );

SELECT JSON_VALID(@payload) AS payload_is_valid;

START TRANSACTION;

DROP TEMPORARY TABLE IF EXISTS tmp_economic_payload_items;

CREATE TEMPORARY TABLE tmp_economic_payload_items AS
SELECT
    jt.row_no,
    JSON_UNQUOTE(
        JSON_EXTRACT(jt.raw_item, '$.date')
    ) AS base_date,
    CASE
        WHEN JSON_UNQUOTE(
            JSON_EXTRACT(jt.raw_item, '$.time')
        ) LIKE '% %' THEN SUBSTRING_INDEX(
            JSON_UNQUOTE(
                JSON_EXTRACT(jt.raw_item, '$.time')
            ),
            ' ',
            -1
        )
        ELSE JSON_UNQUOTE(
            JSON_EXTRACT(jt.raw_item, '$.time')
        )
    END AS raw_release_time,
    TRIM(
        JSON_UNQUOTE(
            JSON_EXTRACT(jt.raw_item, '$.currency')
        )
    ) AS country,
    CASE CHAR_LENGTH(
            TRIM(
                JSON_UNQUOTE(
                    JSON_EXTRACT(jt.raw_item, '$.impact')
                )
            )
        )
        WHEN 1 THEN 'Low'
        WHEN 2 THEN 'Medium'
        WHEN 3 THEN 'High'
        ELSE 'Low'
    END AS impact,
    TRIM(
        JSON_UNQUOTE(
            JSON_EXTRACT(jt.raw_item, '$.event')
        )
    ) AS figures,
    COALESCE(
        NULLIF(
            TRIM(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        jt.raw_item,
                        '$.details.sources'
                    )
                )
            ),
            ''
        ),
        '-'
    ) AS sources,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.measures'
                )
            )
        ),
        ''
    ) AS measures,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.usualEffect'
                )
            )
        ),
        ''
    ) AS usual_effect,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.frequency'
                )
            )
        ),
        ''
    ) AS frequency,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.nextReleased'
                )
            )
        ),
        ''
    ) AS next_released,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.notes'
                )
            )
        ),
        ''
    ) AS notes,
    NULLIF(
        TRIM(
            JSON_UNQUOTE(
                JSON_EXTRACT(
                    jt.raw_item,
                    '$.details.whyTraderCare'
                )
            )
        ),
        ''
    ) AS why_trader_care,
    NULLIF(
        NULLIF(
            TRIM(
                JSON_UNQUOTE(
                    JSON_EXTRACT(jt.raw_item, '$.previous')
                )
            ),
            ''
        ),
        '-'
    ) AS current_previous,
    NULLIF(
        NULLIF(
            TRIM(
                JSON_UNQUOTE(
                    JSON_EXTRACT(jt.raw_item, '$.forecast')
                )
            ),
            ''
        ),
        '-'
    ) AS current_forecast,
    NULLIF(
        NULLIF(
            TRIM(
                JSON_UNQUOTE(
                    JSON_EXTRACT(jt.raw_item, '$.actual')
                )
            ),
            ''
        ),
        '-'
    ) AS current_actual,
    COALESCE(
        JSON_LENGTH(
            JSON_EXTRACT(
                jt.raw_item,
                '$.details.history'
            )
        ),
        0
    ) AS history_count,
    jt.raw_item
FROM JSON_TABLE(
        CAST(@payload AS JSON), '$.data[*]' COLUMNS (
            row_no FOR ORDINALITY, raw_item JSON PATH '$'
        )
    ) AS jt;

DROP TEMPORARY TABLE IF EXISTS tmp_economic_payload_normalized;

CREATE TEMPORARY TABLE tmp_economic_payload_normalized AS
SELECT
    row_no,
    base_date,
    CASE
        WHEN raw_release_time REGEXP '^[0-9]{2}\\.[0-9]{2}$' THEN REPLACE(raw_release_time, '.', ':')
        WHEN raw_release_time IN ('All Day', 'Tentative') THEN raw_release_time
        WHEN raw_release_time = '' THEN NULL
        ELSE raw_release_time
    END AS release_time,
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
    current_previous,
    current_forecast,
    current_actual,
    history_count,
    raw_item
FROM tmp_economic_payload_items;

INSERT INTO
    economic_calendar_categories (
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
    country,
    impact,
    figures,
    sources,
    measures,
    usual_effect,
    frequency,
    next_released,
    notes,
    CASE
        WHEN LOWER(figures) LIKE '%bank holiday%' THEN 1
        ELSE 0
    END AS isBankHoliday,
    CASE
        WHEN LOWER(figures) LIKE '%bank holiday%' THEN figures
        ELSE NULL
    END AS bankHolidayNote,
    why_trader_care,
    NOW(),
    NOW()
FROM
    tmp_economic_payload_normalized
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

DROP TEMPORARY TABLE IF EXISTS tmp_economic_payload_history;

CREATE TEMPORARY TABLE tmp_economic_payload_history AS
SELECT
    item.country,
    item.impact,
    item.figures,
    hist.history_date AS detail_date,
    CASE
        WHEN hist.history_date = item.base_date THEN item.release_time
        ELSE NULL
    END AS detail_time,
    NULLIF(
        NULLIF(
            TRIM(hist.history_previous),
            ''
        ),
        '-'
    ) AS previous_value,
    NULLIF(
        NULLIF(
            TRIM(hist.history_forecast),
            ''
        ),
        '-'
    ) AS forecast_value,
    NULLIF(
        NULLIF(TRIM(hist.history_actual), ''),
        '-'
    ) AS actual_value
FROM
    tmp_economic_payload_normalized AS item
    JOIN JSON_TABLE(
        item.raw_item,
        '$.details.history[*]' COLUMNS (
            history_date VARCHAR(20) PATH '$.date',
            history_previous VARCHAR(100) PATH '$.previous',
            history_forecast VARCHAR(100) PATH '$.forecast',
            history_actual VARCHAR(100) PATH '$.actual'
        )
    ) AS hist;

INSERT INTO
    tmp_economic_payload_history (
        country,
        impact,
        figures,
        detail_date,
        detail_time,
        previous_value,
        forecast_value,
        actual_value
    )
SELECT
    item.country,
    item.impact,
    item.figures,
    item.base_date AS detail_date,
    item.release_time AS detail_time,
    item.current_previous AS previous_value,
    item.current_forecast AS forecast_value,
    item.current_actual AS actual_value
FROM
    tmp_economic_payload_normalized AS item
WHERE
    item.history_count = 0;

DELETE ec
FROM
    economic_calendars AS ec
    JOIN economic_calendar_categories AS cat ON cat.id = ec.economic_calendar_category_id
    JOIN (
        SELECT DISTINCT
            country,
            impact,
            figures
        FROM
            tmp_economic_payload_normalized
    ) AS src ON src.country = cat.country
    AND src.impact = cat.impact
    AND src.figures = cat.figures;

INSERT INTO
    economic_calendars (
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
    cat.id AS economic_calendar_category_id,
    STR_TO_DATE(hist.detail_date, '%Y-%m-%d') AS date,
    hist.detail_time AS time,
    cat.country,
    cat.impact,
    cat.figures,
    hist.previous_value,
    hist.forecast_value,
    hist.actual_value,
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
FROM
    tmp_economic_payload_history AS hist
    JOIN economic_calendar_categories AS cat ON cat.country = hist.country
    AND cat.impact = hist.impact
    AND cat.figures = hist.figures
ORDER BY
    date DESC,
    time DESC,
    economic_calendar_category_id DESC;

COMMIT;

SELECT COUNT(*) AS imported_categories
FROM
    economic_calendar_categories AS cat
    JOIN (
        SELECT DISTINCT
            country,
            impact,
            figures
        FROM
            tmp_economic_payload_normalized
    ) AS src ON src.country = cat.country
    AND src.impact = cat.impact
    AND src.figures = cat.figures;

SELECT COUNT(*) AS imported_details
FROM
    economic_calendars AS ec
    JOIN economic_calendar_categories AS cat ON cat.id = ec.economic_calendar_category_id
    JOIN (
        SELECT DISTINCT
            country,
            impact,
            figures
        FROM
            tmp_economic_payload_normalized
    ) AS src ON src.country = cat.country
    AND src.impact = cat.impact
    AND src.figures = cat.figures;
