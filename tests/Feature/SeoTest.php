<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    /**
     * Uji halaman landing memiliki meta tag SEO lengkap dan Schema.org JSON-LD.
     */
    public function test_landing_page_has_seo_meta_tags_and_schema(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee('JP Learn — Kuasai Huruf &amp; Belajar Bahasa Jepang Interaktif', false)
            ->assertSee('name="description"', false)
            ->assertSee('name="robots"', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('EducationalOrganization', false)
            ->assertSee('FAQPage', false);
    }

    /**
     * Uji meta tag Google Site Verification muncul saat dikonfigurasi.
     */
    public function test_landing_page_renders_google_site_verification_when_configured(): void
    {
        config(['services.google.site_verification' => 'google-test-token-12345']);

        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee('<meta name="google-site-verification" content="google-test-token-12345">', false);
    }

    /**
     * Uji endpoint sitemap.xml dapat diakses dan menghasilkan XML yang valid.
     */
    public function test_sitemap_xml_endpoint_is_accessible_and_valid(): void
    {
        $response = $this->get(route('seo.sitemap'));

        $response->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type') ?? '');

        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', $content);
        $this->assertStringContainsString(url('/'), $content);
        $this->assertStringContainsString(route('updates.index'), $content);
        $this->assertStringContainsString(route('login'), $content);
        $this->assertStringContainsString(route('register'), $content);
    }

    /**
     * Uji penanganan verifikasi Google Search Console via route file HTML.
     */
    public function test_google_verification_html_route(): void
    {
        $response = $this->get('/google1234567890abcdef.html');

        $response->assertOk()
            ->assertSee('google-site-verification: google1234567890abcdef.html', false);
    }

    /**
     * Uji file robots.txt memuat tautan sitemap dan memblokir perayapan halaman internal/dashboard.
     */
    public function test_robots_txt_file_exists_and_blocks_dashboard_routes(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $content = file_get_contents($robotsPath);
        $this->assertStringContainsString('Sitemap: /sitemap.xml', $content);
        $this->assertStringContainsString('Disallow: /dashboard', $content);
        $this->assertStringContainsString('Disallow: /materi', $content);
        $this->assertStringContainsString('Allow: /pembaruan', $content);
    }
}

