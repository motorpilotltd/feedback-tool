<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * idea_spam, comment_spam and idea_tag shipped with only a primary key — no
     * foreign keys, indexes or unique constraints. That allowed duplicate rows
     * (inflating spam counts and duplicating tagged ideas) and made the per-row
     * withCount aggregates and joins full-scan those tables.
     *
     * This adds the missing unique constraints and indexes on every engine, and
     * foreign keys on MySQL only (SQLite cannot add foreign keys via ALTER
     * TABLE, and the test suite runs on SQLite). Existing duplicate and orphaned
     * rows are removed first so the constraints can be applied to live data.
     */
    public function up(): void
    {
        $this->dedupe('idea_spam', ['idea_id', 'user_id']);
        $this->removeOrphans('idea_spam', ['idea_id' => 'ideas', 'user_id' => 'users']);
        Schema::table('idea_spam', function (Blueprint $table) {
            $table->unique(['idea_id', 'user_id']);
            $table->index('user_id');
        });
        $this->addForeignKeys('idea_spam', ['idea_id' => 'ideas', 'user_id' => 'users']);

        $this->dedupe('comment_spam', ['comment_id', 'user_id']);
        $this->removeOrphans('comment_spam', ['comment_id' => 'comments', 'user_id' => 'users']);
        Schema::table('comment_spam', function (Blueprint $table) {
            $table->unique(['comment_id', 'user_id']);
            $table->index('user_id');
        });
        $this->addForeignKeys('comment_spam', ['comment_id' => 'comments', 'user_id' => 'users']);

        $this->dedupe('idea_tag', ['idea_id', 'tag_id']);
        $this->removeOrphans('idea_tag', ['idea_id' => 'ideas', 'tag_id' => 'tags']);
        Schema::table('idea_tag', function (Blueprint $table) {
            $table->unique(['idea_id', 'tag_id']);
            $table->index('tag_id');
        });
        $this->addForeignKeys('idea_tag', ['idea_id' => 'ideas', 'tag_id' => 'tags']);

        // Frequently filtered/sorted on the idea listings but previously unindexed.
        Schema::table('ideas', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('ideas', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['updated_at']);
        });

        $this->dropForeignKeys('idea_tag', ['idea_id', 'tag_id']);
        Schema::table('idea_tag', function (Blueprint $table) {
            $table->dropUnique(['idea_id', 'tag_id']);
            $table->dropIndex(['tag_id']);
        });

        $this->dropForeignKeys('comment_spam', ['comment_id', 'user_id']);
        Schema::table('comment_spam', function (Blueprint $table) {
            $table->dropUnique(['comment_id', 'user_id']);
            $table->dropIndex(['user_id']);
        });

        $this->dropForeignKeys('idea_spam', ['idea_id', 'user_id']);
        Schema::table('idea_spam', function (Blueprint $table) {
            $table->dropUnique(['idea_id', 'user_id']);
            $table->dropIndex(['user_id']);
        });
    }

    /**
     * Delete duplicate rows for the given columns, keeping the lowest id.
     */
    private function dedupe(string $table, array $columns): void
    {
        $duplicates = DB::table($table)
            ->select($columns)
            ->selectRaw('MIN(id) as keep_id')
            ->selectRaw('COUNT(*) as occurrences')
            ->groupBy($columns)
            ->having('occurrences', '>', 1)
            ->get();

        foreach ($duplicates as $row) {
            $query = DB::table($table)->where('id', '!=', $row->keep_id);
            foreach ($columns as $column) {
                $query->where($column, $row->{$column});
            }
            $query->delete();
        }
    }

    /**
     * Delete rows whose referenced parent no longer exists, so foreign keys
     * can be added to existing data without violations.
     *
     * @param  array<string, string>  $map  pivot column => referenced table
     */
    private function removeOrphans(string $table, array $map): void
    {
        foreach ($map as $column => $referencedTable) {
            DB::table($table)
                ->whereNotIn($column, fn ($query) => $query->select('id')->from($referencedTable))
                ->delete();
        }
    }

    /**
     * @param  array<string, string>  $map  pivot column => referenced table
     */
    private function addForeignKeys(string $table, array $map): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($map) {
            foreach ($map as $column => $referencedTable) {
                $blueprint->foreign($column)->references('id')->on($referencedTable)->cascadeOnDelete();
            }
        });
    }

    /**
     * @param  array<int, string>  $columns
     */
    private function dropForeignKeys(string $table, array $columns): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns) {
            foreach ($columns as $column) {
                $blueprint->dropForeign([$column]);
            }
        });
    }
};
