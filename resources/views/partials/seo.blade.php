@php
    $siteName = config('seo.site_name', 'JP Learn');
    $seoTitle = $title ?? config('seo.default_title', 'JP Learn — Kuasai Huruf & Belajar Bahasa Jepang Interaktif');
    $seoDescription = $description ?? config('seo.default_description', 'Platform belajar bahasa Jepang interaktif: kuasai Hiragana, Katakana, Kanji, kosakata, tata bahasa, dan simulasi JLPT N5 dengan metode spaced repetition adaptif.');
    $seoKeywords = $keywords ?? config('seo.default_keywords', 'belajar bahasa jepang, hiragana, katakana, kanji, jlpt n5, flashcard bahasa jepang, spaced repetition, simulasi jlpt, latihan menulis kanji, tata bahasa jepang, kamus bahasa jepang');
    $seoRobots = $robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $canonicalUrl = $canonical ?? url()->current();
    $ogType = $ogType ?? 'website';
    $ogImage = $ogImage ?? asset('images/logo.png');
    $locale = config('seo.locale', 'id_ID');
    $googleVerification = config('services.google.site_verification') ?: env('GOOGLE_SITE_VERIFICATION');
@endphp

<!-- Primary SEO Meta Tags -->
<title>{{ $seoTitle }}</title>
<meta name="title" content="{{ $seoTitle }}">
<meta name="description" content="{{ $seoDescription }}">
@if(!empty($seoKeywords))
<meta name="keywords" content="{{ $seoKeywords }}">
@endif
<meta name="robots" content="{{ $seoRobots }}">
<meta name="googlebot" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Google Search Console Site Verification -->
@if(!empty($googleVerification))
<meta name="google-site-verification" content="{{ $googleVerification }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $siteName }} - Platform Belajar Bahasa Jepang">
<meta property="og:locale" content="{{ $locale }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $siteName }} Preview">

<!-- Mobile & Theme -->
<meta name="theme-color" content="{{ config('seo.theme_color', '#b3153b') }}">
<meta name="author" content="{{ config('seo.author', 'JP Learn') }}">

