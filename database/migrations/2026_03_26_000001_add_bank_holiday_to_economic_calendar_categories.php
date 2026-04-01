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
        if (!Schema::hasTable('economic_calendar_categories')) {
            return;
        }

        $missingIsBankHoliday = !Schema::hasColumn('economic_calendar_categories', 'isBankHoliday');
        $missingBankHolidayNote = !Schema::hasColumn('economic_calendar_categories', 'bankHolidayNote');

        if ($missingIsBankHoliday || $missingBankHolidayNote) {
            Schema::table('economic_calendar_categories', function (Blueprint $table) use ($missingIsBankHoliday, $missingBankHolidayNote) {
                if ($missingIsBankHoliday) {
                    $table->boolean('isBankHoliday')->default(false)->after('notes');
                }

                if ($missingBankHolidayNote) {
                    $table->text('bankHolidayNote')->nullable()->after('isBankHoliday');
                }
            });
        }

        if (!Schema::hasTable('economic_calendars')) {
            return;
        }

        DB::table('economic_calendar_categories')
            ->select('id')
            ->orderBy('id')
            ->get()
            ->each(function (object $category): void {
                $detail = DB::table('economic_calendars')
                    ->where('economic_calendar_category_id', $category->id)
                    ->orderByDesc('date')
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id')
                    ->first(['isBankHoliday', 'bankHolidayNote']);

                if ($detail === null) {
                    return;
                }

                DB::table('economic_calendar_categories')
                    ->where('id', $category->id)
                    ->update([
                        'isBankHoliday' => (bool) ($detail->isBankHoliday ?? false),
                        'bankHolidayNote' => $detail->bankHolidayNote ?? null,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('economic_calendar_categories')) {
            return;
        }

        Schema::table('economic_calendar_categories', function (Blueprint $table) {
            if (Schema::hasColumn('economic_calendar_categories', 'bankHolidayNote')) {
                $table->dropColumn('bankHolidayNote');
            }

            if (Schema::hasColumn('economic_calendar_categories', 'isBankHoliday')) {
                $table->dropColumn('isBankHoliday');
            }
        });
    }
};
