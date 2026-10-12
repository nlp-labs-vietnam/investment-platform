<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fill page_details content for /solutions, /property, /blog
 * across both themes (green + light) and both languages (EN + VI).
 */
return new class extends Migration
{
    // -------------------------------------------------------------------
    // helpers
    // -------------------------------------------------------------------
    private function block(string $section): string
    {
        return '<div class="custom-block" contenteditable="false">'
             . '<div class="custom-block-content">[[' . $section . ']]</div>'
             . '<span class="delete-block">×</span>'
             . '<span class="up-block">↑</span>'
             . '<span class="down-block">↓</span>'
             . '</div><p><br></p>';
    }

    private function blocks(array $sections): string
    {
        return implode('', array_map(fn($s) => $this->block($s), $sections));
    }

    // -------------------------------------------------------------------
    // up
    // -------------------------------------------------------------------
    public function up(): void
    {
        $now = now();

        // ================================================================
        // 1. /solutions
        //    page_id=20 (green), page_id=8 (light)
        //    page_details: id=21 (p20/EN), id=44 (p20/VI),
        //                  id=9  (p8/EN),  id=43 (p8/VI)
        // ================================================================
        $solSections     = ['hero', 'feature', 'about', 'testimonial', 'statistics'];
        $solContent      = $this->blocks($solSections);
        $solSectionsJson = json_encode($solSections);

        DB::table('page_details')
            ->whereIn('id', [9, 21, 43, 44])
            ->update(['content' => $solContent, 'sections' => $solSectionsJson, 'updated_at' => $now]);

        // ================================================================
        // 2. /property
        //    page_id=26 (green), page_id=27 (light) — no page_details yet
        // ================================================================
        $propSections     = ['hero', 'property', 'latest_property', 'testimonial', 'statistics'];
        $propContent      = $this->blocks($propSections);
        $propSectionsJson = json_encode($propSections);

        // Only insert rows that do not already exist
        $existingPropPairs = DB::table('page_details')
            ->whereIn('page_id', [26, 27])
            ->get(['page_id', 'language_id'])
            ->map(fn($r) => "{$r->page_id}:{$r->language_id}")
            ->toArray();

        $propertyRows = [
            [26, 1,  'Investment Projects', 'Dự Án Đầu Tư'],
            [26, 21, 'Investment Projects', 'Dự Án Đầu Tư'],
            [27, 1,  'Investment Projects', 'Dự Án Đầu Tư'],
            [27, 21, 'Investment Projects', 'Dự Án Đầu Tư'],
        ];

        foreach ($propertyRows as [$pageId, $langId, $nameEN, $nameVI]) {
            $key = "{$pageId}:{$langId}";
            if (in_array($key, $existingPropPairs, true)) {
                // Row exists but may be empty — update
                DB::table('page_details')
                    ->where('page_id', $pageId)
                    ->where('language_id', $langId)
                    ->update(['content' => $propContent, 'sections' => $propSectionsJson, 'updated_at' => $now]);
            } else {
                // Insert new row
                $name = $langId === 1 ? $nameEN : $nameVI;
                DB::table('page_details')->insert([
                    'page_id'     => $pageId,
                    'language_id' => $langId,
                    'name'        => $name,
                    'content'     => $propContent,
                    'sections'    => $propSectionsJson,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // ================================================================
        // 3. /blog
        //    page_id=22 (green), page_id=5 (light)
        //    page_details: id=23 (p22/EN), id=55 (p22/VI),
        //                  id=6  (p5/EN),  id=49 (p5/VI)
        // ================================================================
        $blogSections     = ['hero', 'blog'];
        $blogContent      = $this->blocks($blogSections);
        $blogSectionsJson = json_encode($blogSections);

        DB::table('page_details')
            ->whereIn('id', [6, 23, 49, 55])
            ->update(['content' => $blogContent, 'sections' => $blogSectionsJson, 'updated_at' => $now]);
    }

    // -------------------------------------------------------------------
    // down  — clear content/sections to restore original empty state
    // -------------------------------------------------------------------
    public function down(): void
    {
        $now = now();

        // Revert solutions
        DB::table('page_details')
            ->whereIn('id', [9, 21, 43, 44])
            ->update(['content' => null, 'sections' => null, 'updated_at' => $now]);

        // Remove property page_details that were inserted by this migration
        // (safe to remove all rows for page_id 26 & 27 as none existed before)
        DB::table('page_details')->whereIn('page_id', [26, 27])->delete();

        // Revert blog
        DB::table('page_details')
            ->whereIn('id', [6, 23, 49, 55])
            ->update(['content' => null, 'sections' => null, 'updated_at' => $now]);
    }
};
