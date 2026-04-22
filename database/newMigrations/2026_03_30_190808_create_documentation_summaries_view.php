<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW documentation_summaries AS
        
        SELECT 
            'release_' || id as id, 
            project_id,
            title,
            is_approve,
            rejection_note,
            updated_at,
            'Release' as type
        FROM releases
        
        UNION ALL
        
        SELECT 
            'roadmap_' || id as id,
            project_id,
            title,
            is_approve,
            rejection_note,
            updated_at,
            'Roadmap' as type
        FROM roadmaps
        
        UNION ALL
        
        SELECT 
            'faq_' || id as id,
            project_id,
            question as title,
            is_approve,
            rejection_note,
            updated_at,
            'FAQ' as type
        FROM faqs
        
        UNION ALL

        SELECT 
            'kb_' || id as id,
            project_id,
            title,
            is_approve,
            rejection_note,
            updated_at,
            'Panduan' as type
        FROM knowledge_bases");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS \"documentation_summaries\"");
    }
};
