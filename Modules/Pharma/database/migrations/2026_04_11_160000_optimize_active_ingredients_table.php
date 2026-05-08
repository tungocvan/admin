<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('active_ingredients', function (Blueprint $table) {
            if (! Schema::hasColumn('active_ingredients', 'row_hash')) {
                $table->string('row_hash', 64)->nullable()->after('drug_group');
            }

            if (! Schema::hasColumn('active_ingredients', 'source_file')) {
                $table->string('source_file')->nullable()->after('row_hash');
            }

            if (! Schema::hasColumn('active_ingredients', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('active_ingredients', function (Blueprint $table) {
            $table->index('stt', 'active_ingredients_stt_idx');
            $table->index('hospital_level', 'active_ingredients_hospital_level_idx');
            $table->index('drug_group', 'active_ingredients_drug_group_idx');
            $table->index('dosage_form', 'active_ingredients_dosage_form_idx');
            $table->index(['hospital_level', 'drug_group'], 'active_ingredients_hospital_group_idx');
            $table->unique('row_hash', 'active_ingredients_row_hash_unique');
        });

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            Schema::table('active_ingredients', function (Blueprint $table) {
                $table->fullText(
                    ['name', 'dosage_form', 'note', 'drug_group'],
                    'active_ingredients_search_fulltext'
                );
            });
        }
    }

    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            Schema::table('active_ingredients', function (Blueprint $table) {
                $table->dropFullText('active_ingredients_search_fulltext');
            });
        }

        Schema::table('active_ingredients', function (Blueprint $table) {
            $table->dropUnique('active_ingredients_row_hash_unique');
            $table->dropIndex('active_ingredients_stt_idx');
            $table->dropIndex('active_ingredients_hospital_level_idx');
            $table->dropIndex('active_ingredients_drug_group_idx');
            $table->dropIndex('active_ingredients_dosage_form_idx');
            $table->dropIndex('active_ingredients_hospital_group_idx');
            $table->dropSoftDeletes();
            $table->dropColumn(['row_hash', 'source_file']);
        });
    }
};
