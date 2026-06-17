<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        // Remove any existing custom attributes with same codes (idempotent)
        DB::table('attributes')
            ->whereIn('code', ['phone_number', 'nationality', 'country', 'interested_program', 'degree', 'lead_status'])
            ->where('entity_type', 'leads')
            ->delete();

        // Insert new custom lead attributes
        $attrs = [
            [
                'code'            => 'phone_number',
                'name'            => 'Phone Number',
                'type'            => 'text',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 11,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'nationality',
                'name'            => 'Nationality',
                'type'            => 'text',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 12,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'country',
                'name'            => 'Country',
                'type'            => 'text',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 13,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'interested_program',
                'name'            => 'Interested Program',
                'type'            => 'text',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 14,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'degree',
                'name'            => 'Degree',
                'type'            => 'select',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 15,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'lead_status',
                'name'            => 'Lead Status',
                'type'            => 'select',
                'entity_type'     => 'leads',
                'lookup_type'     => null,
                'validation'      => null,
                'sort_order'      => 16,
                'is_required'     => 0,
                'is_unique'       => 0,
                'quick_add'       => 1,
                'is_user_defined' => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        foreach ($attrs as $attr) {
            DB::table('attributes')->insert($attr);
        }

        // Add options for Degree
        $degreeAttrId = DB::table('attributes')
            ->where('code', 'degree')
            ->where('entity_type', 'leads')
            ->value('id');

        DB::table('attribute_options')->insert([
            ['attribute_id' => $degreeAttrId, 'name' => 'بكالوريس',  'sort_order' => 1],
            ['attribute_id' => $degreeAttrId, 'name' => 'ماجستير',   'sort_order' => 2],
            ['attribute_id' => $degreeAttrId, 'name' => 'دكتوراة',   'sort_order' => 3],
            ['attribute_id' => $degreeAttrId, 'name' => 'دبلوم',     'sort_order' => 4],
        ]);

        // Add options for Lead Status
        $statusAttrId = DB::table('attributes')
            ->where('code', 'lead_status')
            ->where('entity_type', 'leads')
            ->value('id');

        DB::table('attribute_options')->insert([
            ['attribute_id' => $statusAttrId, 'name' => 'New Lead',             'sort_order' => 1],
            ['attribute_id' => $statusAttrId, 'name' => 'Contacted1',           'sort_order' => 2],
            ['attribute_id' => $statusAttrId, 'name' => 'Contacted2',           'sort_order' => 3],
            ['attribute_id' => $statusAttrId, 'name' => 'Contacted3',           'sort_order' => 4],
            ['attribute_id' => $statusAttrId, 'name' => 'Qualified',            'sort_order' => 5],
            ['attribute_id' => $statusAttrId, 'name' => 'Consultation',         'sort_order' => 6],
            ['attribute_id' => $statusAttrId, 'name' => 'Interested',           'sort_order' => 7],
            ['attribute_id' => $statusAttrId, 'name' => 'Hot Lead',             'sort_order' => 8],
            ['attribute_id' => $statusAttrId, 'name' => 'Transferred to ABX',   'sort_order' => 9],
            ['attribute_id' => $statusAttrId, 'name' => 'Lost',                 'sort_order' => 10],
        ]);
    }

    public function down(): void
    {
        DB::table('attributes')
            ->whereIn('code', ['phone_number', 'nationality', 'country', 'interested_program', 'degree', 'lead_status'])
            ->where('entity_type', 'leads')
            ->delete();
    }
};
