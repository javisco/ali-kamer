<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cette migration part du schéma KYC ACTUEL d'Ali-Kamer.
     *
     * Elle ne crée pas une seconde table `kyc_verifications` : le projet
     * possède déjà `kyc_documents` et tout le code vendeur/admin s'appuie
     * dessus. On l'enrichit donc pour Didit afin d'éviter deux sources de vérité.
     */
    public function up(): void
    {
        Schema::table('kyc_documents', function (Blueprint $table) {
            // Les fichiers locaux ne sont plus utilisés avec Hosted Sessions.
            $table->string('cni_front_url')->nullable()->change();
            $table->string('cni_back_url')->nullable()->change();
            $table->string('selfie_url')->nullable()->change();
            $table->string('rccm_url')->nullable()->change();

            $table->string('provider', 30)->default('didit')->after('user_id');

            $table->uuid('didit_session_id')->nullable()->after('provider');
            $table->unsignedBigInteger('didit_session_number')->nullable();
            $table->string('didit_session_status', 50)->nullable();
            $table->text('didit_session_url')->nullable();
            $table->uuid('didit_workflow_id')->nullable();
            $table->unsignedInteger('didit_workflow_version')->nullable();
            $table->string('didit_vendor_data', 255)->nullable();
            $table->string('didit_environment', 20)->nullable();

            $table->string('document_status', 50)->nullable();
            $table->string('ocr_status', 50)->nullable();
            $table->string('face_match_status', 50)->nullable();
            $table->string('liveness_status', 50)->nullable();
            $table->string('ip_analysis_status', 50)->nullable();
            $table->string('identity_resolution_status', 30)->nullable();

            $table->string('decision', 30)->nullable();
            $table->text('decision_reason')->nullable();

            $table->char('cni_hash', 64)->nullable();
            $table->string('document_number_masked', 100)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->date('expiration_date')->nullable();
            $table->decimal('name_match_score', 6, 3)->nullable();

            $table->json('warnings')->nullable();
            $table->json('didit_result')->nullable();

            $table->ipAddress('verification_ip')->nullable();
            $table->timestamp('last_webhook_at')->nullable();

            $table->timestamp('consent_at')->nullable();
            $table->ipAddress('consent_ip')->nullable();
            $table->string('consent_user_agent', 500)->nullable();
            $table->string('consent_version', 50)->nullable();

            $table->index('didit_session_id');
            $table->index('cni_hash');
            $table->index(['provider', 'status']);
        });

        Schema::create('didit_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_id')->unique();
            $table->string('webhook_type', 80);
            $table->uuid('session_id')->nullable()->index();
            $table->string('status', 50)->nullable();
            $table->string('environment', 20)->nullable();
            $table->string('signature_method', 20)->nullable();

            // Données reçues, conservées pour audit/debug interne.
            // Ne jamais exposer cette colonne directement au vendeur.
            $table->json('payload');

            $table->timestamp('received_at');
            $table->timestamp('processed_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->text('processing_error')->nullable();
            $table->timestamps();

            $table->index(['webhook_type', 'processed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('didit_webhook_events');

        Schema::table('kyc_documents', function (Blueprint $table) {
            // Les colonnes ajoutées sont supprimées individuellement pour
            // conserver la compatibilité avec MySQL/MariaDB.
            $columns = [
                'provider', 'didit_session_id', 'didit_session_number',
                'didit_session_status', 'didit_session_url', 'didit_workflow_id',
                'didit_workflow_version', 'didit_vendor_data', 'didit_environment',
                'document_status', 'ocr_status', 'face_match_status', 'liveness_status',
                'ip_analysis_status', 'identity_resolution_status', 'decision',
                'decision_reason', 'cni_hash', 'document_number_masked', 'full_name',
                'date_of_birth', 'nationality', 'expiration_date', 'name_match_score',
                'warnings', 'didit_result', 'verification_ip', 'last_webhook_at',
                'consent_at', 'consent_ip', 'consent_user_agent', 'consent_version',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('kyc_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
