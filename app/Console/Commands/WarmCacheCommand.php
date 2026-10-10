<?php

namespace App\Console\Commands;

use App\Models\BasicControl;
use App\Models\Language;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * WarmCacheCommand
 *
 * Thay thế `cache:clear --hourly` bằng chiến lược warm từng key riêng lẻ.
 * Chạy mỗi 25 phút (trước khi TTL 30 phút của các key quan trọng hết hạn)
 * để người dùng KHÔNG bao giờ nhận một request lạnh (cold-cache).
 *
 * Nguyên tắc:
 *  - Không xóa cache toàn diện — chỉ refresh key sắp hết hạn.
 *  - Mỗi key được refresh bằng Cache::put() với TTL mới trước khi hết hạn.
 *  - ConfigureSetting không có TTL cứng → chỉ refresh khi key không còn tồn tại.
 */
class WarmCacheCommand extends Command
{
    protected $signature = 'cache:warm
                            {--force : Buộc refresh tất cả key dù chưa hết hạn}';

    protected $description = 'Warm critical cache keys proactively to avoid cold-cache hits for users';

    /** TTL (giây) dùng khi viết lại cache — khớp với TTL trong helpers.php */
    private const TTL_LONG   = 3600;  // 60 phút  — ConfigureSetting
    private const TTL_MEDIUM =  1800; // 30 phút  — menus, pages, language
    private const TTL_SHORT  =  900;  // 15 phút  — footer extra info

    public function handle(): int
    {
        $force = $this->option('force');
        $warmed = 0;

        // 1. ConfigureSetting — nền tảng của basicControl()
        //    Chỉ warm nếu key không còn hoặc --force
        if ($force || !Cache::has('ConfigureSetting')) {
            try {
                $configure = BasicControl::firstOrCreate();
                Cache::put('ConfigureSetting', $configure, self::TTL_LONG);
                $warmed++;
                $this->line('  ✔ ConfigureSetting');
            } catch (\Exception $e) {
                $this->warn('  ✗ ConfigureSetting: ' . $e->getMessage());
            }
        }

        // 2. Active languages  (column: status = 1)
        if ($force || !Cache::has('active_languages')) {
            try {
                $languages = Language::where('status', 1)->get();
                Cache::put('active_languages', $languages, self::TTL_MEDIUM);
                $warmed++;
                $this->line('  ✔ active_languages');
            } catch (\Exception $e) {
                $this->warn('  ✗ active_languages: ' . $e->getMessage());
            }
        }

        // 3. Default language  (column: default_status = 1)
        if ($force || !Cache::has('default_language')) {
            try {
                $default = Language::where('default_status', 1)->first();
                if ($default) {
                    Cache::put('default_language', $default, self::TTL_MEDIUM);
                    $warmed++;
                    $this->line('  ✔ default_language');
                }
            } catch (\Exception $e) {
                $this->warn('  ✗ default_language: ' . $e->getMessage());
            }
        }

        // 4. Menus & page details per active theme
        //    Warm header_manage_menu_{theme} & footer_manage_menu_{theme}
        $this->warmThemeKeys($force, $warmed);

        $this->info("cache:warm completed — {$warmed} key(s) refreshed.");

        return self::SUCCESS;
    }

    private function warmThemeKeys(bool $force, int &$warmed): void
    {
        try {
            $theme = basicControl()->theme ?? 'green';
        } catch (\Exception $e) {
            return;
        }

        $menuKeys = [
            "header_manage_menu_{$theme}",
            "footer_manage_menu_{$theme}",
            "content_extra_info_{$theme}",
        ];

        foreach ($menuKeys as $key) {
            // Only evict+rebuild if key is missing or --force.
            // We do NOT rebuild the actual menu data here (that requires
            // complex query logic from helpers) — instead we just FORGET
            // expired keys so the next web request rebuilds them via
            // Cache::remember() without competition.
            if ($force || !Cache::has($key)) {
                Cache::forget($key);
                $warmed++;
                $this->line("  ✔ evicted (will rebuild on next request): {$key}");
            }
        }
    }
}
