<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature   = 'nlp:generate-sitemap';
    protected $description = 'Generate sitemap.xml and robots.txt for nlpgroup.vn';

    /** Static routes always included */
    private array $staticRoutes = [
        ['loc' => '/',                      'priority' => '1.0',  'changefreq' => 'weekly'],
        ['loc' => '/about',                 'priority' => '0.8',  'changefreq' => 'monthly'],
        ['loc' => '/ecosystem',             'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-energy',            'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-ev-charging',       'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-technology',        'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-ai-digital',        'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-esg-carbon',        'priority' => '0.9',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-ev-products',       'priority' => '0.8',  'changefreq' => 'monthly'],
        ['loc' => '/nlp-energy-products',   'priority' => '0.8',  'changefreq' => 'monthly'],
        ['loc' => '/solutions',             'priority' => '0.8',  'changefreq' => 'monthly'],
        ['loc' => '/blog',                  'priority' => '0.8',  'changefreq' => 'daily'],
        ['loc' => '/contact',               'priority' => '0.7',  'changefreq' => 'yearly'],
        ['loc' => '/about',                 'priority' => '0.7',  'changefreq' => 'yearly'],
        ['loc' => '/faq',                   'priority' => '0.6',  'changefreq' => 'monthly'],
        ['loc' => '/tuyen-dung',            'priority' => '0.6',  'changefreq' => 'weekly'],
        ['loc' => '/privacy-policy',        'priority' => '0.3',  'changefreq' => 'yearly'],
        ['loc' => '/terms-and-conditions',  'priority' => '0.3',  'changefreq' => 'yearly'],
    ];

    public function handle(): int
    {
        $baseUrl = rtrim(config('app.url'), '/');
        $now     = now()->toAtomString();

        $urls = $this->buildUrlSet($baseUrl, $now);

        $xml = $this->renderXml($urls);
        File::put(public_path('sitemap.xml'), $xml);

        $this->writeRobots($baseUrl);

        $this->info('sitemap.xml → ' . public_path('sitemap.xml') . '  (' . count($urls) . ' URLs)');
        $this->info('robots.txt  → ' . public_path('robots.txt'));

        return self::SUCCESS;
    }

    private function buildUrlSet(string $baseUrl, string $now): array
    {
        $urls = [];

        // Static routes
        foreach ($this->staticRoutes as $route) {
            $urls[$route['loc']] = [
                'loc'        => $baseUrl . $route['loc'],
                'lastmod'    => $now,
                'changefreq' => $route['changefreq'],
                'priority'   => $route['priority'],
            ];
        }

        // Dynamic blog/news posts
        if (class_exists(Post::class)) {
            try {
                Post::where('status', 1)
                    ->select(['slug', 'updated_at'])
                    ->orderByDesc('updated_at')
                    ->limit(500)
                    ->each(function ($post) use ($baseUrl, &$urls) {
                        $loc = '/blog/' . $post->slug;
                        $urls[$loc] = [
                            'loc'        => $baseUrl . $loc,
                            'lastmod'    => $post->updated_at->toAtomString(),
                            'changefreq' => 'monthly',
                            'priority'   => '0.6',
                        ];
                    });
            } catch (\Throwable) {
                // Table may not exist yet — skip silently
            }
        }

        return array_values($urls);
    }

    private function renderXml(array $urls): string
    {
        $items = '';
        foreach ($urls as $u) {
            $items .= "  <url>\n";
            $items .= "    <loc>" . e($u['loc']) . "</loc>\n";
            $items .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            $items .= "    <changefreq>{$u['changefreq']}</changefreq>\n";
            $items .= "    <priority>{$u['priority']}</priority>\n";
            $items .= "  </url>\n";
        }

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
          http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
$items</urlset>
XML;
    }

    private function writeRobots(string $baseUrl): void
    {
        $content = <<<ROBOTS
User-agent: *
Allow: /

# Disallow private/admin areas
Disallow: /admin/
Disallow: /user/
Disallow: /login
Disallow: /register
Disallow: /password/
Disallow: /sanctum/
Disallow: /api/
Disallow: /email/
Disallow: /*.json$
Disallow: /storage/

# Crawl delay
Crawl-delay: 5

# Sitemap
Sitemap: {$baseUrl}/sitemap.xml
ROBOTS;

        File::put(public_path('robots.txt'), $content);
    }
}
