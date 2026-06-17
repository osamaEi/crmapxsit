<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Keep won (id=5) and lost (id=6) unchanged — they are special system stages.
        // Rename stage id=1 (new) to "New Lead" — code stays 'new'.
        // Replace stages 2, 3, 4 with the new flow stages.

        // Update the first stage name only
        DB::table('lead_pipeline_stages')
            ->where('id', 1)
            ->update(['name' => 'New Lead', 'code' => 'new']);

        // Remove old middle stages (follow-up=2, prospect=3, negotiation=4)
        // First move any leads on those stages to stage 1 so FK is safe
        DB::table('leads')
            ->whereIn('lead_pipeline_stage_id', [2, 3, 4])
            ->update(['lead_pipeline_stage_id' => 1]);

        DB::table('lead_pipeline_stages')->whereIn('id', [2, 3, 4])->delete();

        // Shift won/lost sort_order up to make room for 7 new middle stages
        // New order: 1=New Lead, 2-8=middle, 9=Transferred to ABX, 10=won, 11=lost
        DB::table('lead_pipeline_stages')->where('id', 5)->update(['sort_order' => 10]);
        DB::table('lead_pipeline_stages')->where('id', 6)->update(['sort_order' => 11]);

        // Insert new stages
        DB::table('lead_pipeline_stages')->insert([
            ['code' => 'contacted-1',          'name' => 'Contacted1',          'probability' => 20,  'sort_order' => 2,  'lead_pipeline_id' => 1],
            ['code' => 'contacted-2',          'name' => 'Contacted2',          'probability' => 30,  'sort_order' => 3,  'lead_pipeline_id' => 1],
            ['code' => 'contacted-3',          'name' => 'Contacted3',          'probability' => 40,  'sort_order' => 4,  'lead_pipeline_id' => 1],
            ['code' => 'qualified',            'name' => 'Qualified',           'probability' => 50,  'sort_order' => 5,  'lead_pipeline_id' => 1],
            ['code' => 'consultation',         'name' => 'Consultation',        'probability' => 60,  'sort_order' => 6,  'lead_pipeline_id' => 1],
            ['code' => 'interested',           'name' => 'Interested',          'probability' => 70,  'sort_order' => 7,  'lead_pipeline_id' => 1],
            ['code' => 'hot-lead',             'name' => 'Hot Lead',            'probability' => 85,  'sort_order' => 8,  'lead_pipeline_id' => 1],
            ['code' => 'transferred-to-abx',  'name' => 'Transferred to ABX',  'probability' => 95,  'sort_order' => 9,  'lead_pipeline_id' => 1],
        ]);
    }

    public function down(): void
    {
        // Remove new stages
        DB::table('lead_pipeline_stages')
            ->where('lead_pipeline_id', 1)
            ->whereNotIn('code', ['new', 'won', 'lost'])
            ->delete();

        // Restore won/lost sort_order
        DB::table('lead_pipeline_stages')->where('id', 5)->update(['sort_order' => 5]);
        DB::table('lead_pipeline_stages')->where('id', 6)->update(['sort_order' => 6]);

        // Restore original stage name
        DB::table('lead_pipeline_stages')->where('id', 1)->update(['name' => 'New', 'code' => 'new']);

        // Restore original middle stages
        DB::table('lead_pipeline_stages')->insert([
            ['id' => 2, 'code' => 'follow-up',    'name' => 'Follow Up',    'probability' => 100, 'sort_order' => 2, 'lead_pipeline_id' => 1],
            ['id' => 3, 'code' => 'prospect',     'name' => 'Prospect',     'probability' => 100, 'sort_order' => 3, 'lead_pipeline_id' => 1],
            ['id' => 4, 'code' => 'negotiation',  'name' => 'Negotiation',  'probability' => 100, 'sort_order' => 4, 'lead_pipeline_id' => 1],
        ]);
    }
};
