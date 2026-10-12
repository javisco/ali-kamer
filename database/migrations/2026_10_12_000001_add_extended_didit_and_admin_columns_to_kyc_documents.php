<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            // Décision administrative Ali-Kamer (indépendante du statut Didit)
            $table->string('admin_decision', 30)->nullable()->after('decision');
            $table->text('admin_decision_reason')->nullable()->after('admin_decision');

            // Scores biométriques et AML normalisés
            $table->decimal('liveness_score', 6, 2)->nullable()->after('liveness_status');
            $table->decimal('face_match_score', 6, 2)->nullable()->after('face_match_status');
            $table->string('aml_status', 50)->nullable()->after('ip_analysis_status');

            // Blocs de résultats Didit structurés en JSON
            $table->json('didit_document_result')->nullable()->after('didit_result');
            $table->json('didit_liveness_result')->nullable()->after('didit_document_result');
            $table->json('didit_face_match_result')->nullable()->after('didit_liveness_result');
            $table->json('didit_ip_result')->nullable()->after('didit_face_match_result');
            $table->json('didit_aml_result')->nullable()->after('didit_ip_result');
            $table->json('didit_review_result')->nullable()->after('didit_aml_result');
        });
    }

    public function down(): void
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            $columns = [
                'admin_decision',
                'admin_decision_reason',
                'liveness_score',
                'face_match_score',
                'aml_status',
                'didit_document_result',
                'didit_liveness_result',
                'didit_face_match_result',
                'didit_ip_result',
                'didit_aml_result',
                'didit_review_result',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('kyc_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
