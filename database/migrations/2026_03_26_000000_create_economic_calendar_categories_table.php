<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('economic_calendar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('country', 50);
            $table->enum('impact', ['Low', 'Medium', 'High']);
            $table->string('figures');
            $table->text('sources');
            $table->text('measures')->nullable();
            $table->text('usual_effect')->nullable();
            $table->text('frequency')->nullable();
            $table->text('next_released')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('isBankHoliday')->default(false);
            $table->text('bankHolidayNote')->nullable();
            $table->text('why_trader_care')->nullable();
            $table->timestamps();

            $table->unique(['country', 'impact', 'figures'], 'economic_calendar_categories_unique');
        });

        Schema::table('economic_calendars', function (Blueprint $table) {
            $table->foreignId('economic_calendar_category_id')
                ->nullable()
                ->after('id')
                ->constrained('economic_calendar_categories')
                ->cascadeOnDelete();
        });

        $categoryIds = [];

        DB::table('economic_calendars')
            ->orderByDesc('date')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get()
            ->each(function (object $calendar) use (&$categoryIds): void {
                $key = implode('|', [
                    trim((string) $calendar->country),
                    trim((string) $calendar->impact),
                    trim((string) $calendar->figures),
                ]);

                if (!isset($categoryIds[$key])) {
                    $timestamp = now();

                    $categoryIds[$key] = DB::table('economic_calendar_categories')->insertGetId([
                        'country' => $calendar->country,
                        'impact' => $calendar->impact,
                        'figures' => $calendar->figures,
                        'sources' => $calendar->sources,
                        'measures' => $calendar->measures,
                        'usual_effect' => $calendar->usual_effect,
                        'frequency' => $calendar->frequency,
                        'next_released' => $calendar->next_released,
                        'notes' => $calendar->notes,
                        'isBankHoliday' => (bool) ($calendar->isBankHoliday ?? false),
                        'bankHolidayNote' => $calendar->bankHolidayNote ?? null,
                        'why_trader_care' => $calendar->why_trader_care,
                        'created_at' => $calendar->created_at ?? $timestamp,
                        'updated_at' => $calendar->updated_at ?? $timestamp,
                    ]);
                }

                DB::table('economic_calendars')
                    ->where('id', $calendar->id)
                    ->update([
                        'economic_calendar_category_id' => $categoryIds[$key],
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('economic_calendars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('economic_calendar_category_id');
        });

        Schema::dropIfExists('economic_calendar_categories');
    }
};
