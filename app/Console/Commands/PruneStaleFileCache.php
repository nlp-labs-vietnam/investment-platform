<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * PruneStaleFileCache
 *
 * Xóa các file cache vật lý đã hết hạn khỏi storage/framework/cache/data.
 * Chạy mỗi ngày lúc 03:00 (off-peak) để giải phóng disk.
 *
 * Lý do cần command này:
 *  - Laravel file driver KHÔNG tự xóa file đã hết hạn trừ khi key được truy cập.
 *  - Theo thời gian, thư mục cache tích lũy hàng nghìn file rác → I/O chậm.
 *  - Command này quét và xóa file có expiry timestamp đã qua.
 */
class PruneStaleFileCache extends Command
{
    protected $signature = 'cache:prune-stale-tags
                            {--dry-run : Liệt kê file sẽ xóa mà không thực sự xóa}';

    protected $description = 'Remove expired file-cache entries from storage/framework/cache/data';

    public function handle(): int
    {
        $dryRun   = $this->option('dry-run');
        $cacheDir = storage_path('framework/cache/data');

        if (!is_dir($cacheDir)) {
            $this->info('Cache directory not found — nothing to prune.');
            return self::SUCCESS;
        }

        $now     = Carbon::now()->timestamp;
        $deleted = 0;
        $bytes   = 0;

        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($cacheDir, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            try {
                $content = file_get_contents($file->getPathname());
                // Laravel file cache format: first 10 chars are Unix expiry timestamp
                // 0000000000 means forever (no expiry)
                $expiry = (int) substr($content, 0, 10);

                if ($expiry === 0) {
                    // No expiry — keep
                    continue;
                }

                if ($expiry < $now) {
                    $bytes += $file->getSize();
                    if ($dryRun) {
                        $this->line('  [dry-run] would delete: ' . $file->getPathname());
                    } else {
                        @unlink($file->getPathname());
                    }
                    $deleted++;
                }
            } catch (\Throwable $e) {
                // Skip unreadable files
                continue;
            }
        }

        $label = $dryRun ? '[dry-run] would remove' : 'Removed';
        $kb    = round($bytes / 1024, 1);
        $this->info("{$label} {$deleted} stale cache file(s) ({$kb} KB freed).");

        return self::SUCCESS;
    }
}
