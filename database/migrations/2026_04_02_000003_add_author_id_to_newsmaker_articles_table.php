<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || Schema::hasColumn('newsmaker_articles', 'author_id')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->foreignId('author_id')
                ->nullable()
                ->after('author')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        $defaultUserId = DB::table('users')->orderBy('id')->value('id');

        DB::table('newsmaker_articles')
            ->select('id', 'author')
            ->orderBy('id')
            ->chunkById(200, function ($articles) use ($defaultUserId) {
                foreach ($articles as $article) {
                    $authorName = trim((string) $article->author);
                    $matchedUserId = null;

                    if ($authorName !== '') {
                        $matchedUserId = DB::table('users')->where('name', $authorName)->value('id')
                            ?? DB::table('users')->where('username', $authorName)->value('id')
                            ?? DB::table('users')->where('email', $authorName)->value('id');
                    }

                    $resolvedUserId = $matchedUserId ?? $defaultUserId;

                    if ($resolvedUserId === null) {
                        continue;
                    }

                    DB::table('newsmaker_articles')
                        ->where('id', $article->id)
                        ->update(['author_id' => $resolvedUserId]);
                }
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || !Schema::hasColumn('newsmaker_articles', 'author_id')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });
    }
};
