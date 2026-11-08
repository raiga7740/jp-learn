<?php

namespace App\Http\Controllers;

use App\Services\UpdateService;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class SeoController extends Controller
{
    /**
     * Inisialisasi controller dengan UpdateService.
     */
    public function __construct(
        private ?UpdateService $updateService = null
    ) {
    }

    /**
     * Hasilkan XML Sitemap untuk Google Search Console dan mesin pencari lainnya.
     *
     * @return Response
     */
    public function sitemap(): Response
    {
        $today = Carbon::today()->toIso8601String();

        $latestUpdateDate = $today;
        if ($this->updateService !== null) {
            $latest = $this->updateService->getLatestRelease();
            if (!empty($latest['date'])) {
                try {
                    $latestUpdateDate = Carbon::parse($latest['date'])->toIso8601String();
                } catch (\Throwable $e) {
                    $latestUpdateDate = $today;
                }
            }
        }

        $urls = [
            [
                'loc'        => url('/'),
                'lastmod'    => $today,
                'changefreq' => 'daily',
                'priority'   => '1.0',
            ],
            [
                'loc'        => route('updates.index'),
                'lastmod'    => $latestUpdateDate,
                'changefreq' => 'weekly',
                'priority'   => '0.8',
            ],
            [
                'loc'        => route('login'),
                'lastmod'    => $today,
                'changefreq' => 'monthly',
                'priority'   => '0.4',
            ],
            [
                'loc'        => route('register'),
                'lastmod'    => $today,
                'changefreq' => 'monthly',
                'priority'   => '0.5',
            ],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        foreach ($urls as $item) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "        <lastmod>" . $item['lastmod'] . "</lastmod>\n";
            $xml .= "        <changefreq>" . $item['changefreq'] . "</changefreq>\n";
            $xml .= "        <priority>" . $item['priority'] . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    /**
     * Tangani verifikasi kepemilikan Google Search Console via file HTML (google[hash].html).
     *
     * @param string $code
     * @return Response
     */
    public function googleVerification(string $code): Response
    {
        $body = "google-site-verification: google{$code}.html";

        return response($body, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}

