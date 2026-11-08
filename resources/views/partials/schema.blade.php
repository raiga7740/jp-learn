@php
    $siteUrl = url('/');
    $logoUrl = asset('images/logo.png');
    $siteName = config('seo.site_name', 'JP Learn');
    $siteDesc = config('seo.default_description', 'Platform belajar bahasa Jepang interaktif: Hiragana, Katakana, Kanji, dan persiapan JLPT N5.');
@endphp

<!-- Schema.org JSON-LD Structured Data for Google Search -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@graph": [
        {
            "@@type": "WebSite",
            "@@id": "{{ $siteUrl }}#website",
            "url": "{{ $siteUrl }}",
            "name": "{{ $siteName }}",
            "description": "{{ $siteDesc }}",
            "inLanguage": "id-ID",
            "publisher": {
                "@@id": "{{ $siteUrl }}#organization"
            }
        },
        {
            "@@type": "EducationalOrganization",
            "@@id": "{{ $siteUrl }}#organization",
            "name": "{{ $siteName }}",
            "url": "{{ $siteUrl }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ $logoUrl }}"
            },
            "description": "{{ $siteDesc }}",
            "sameAs": []
        },
        {
            "@@type": "Course",
            "@@id": "{{ $siteUrl }}#course-n5",
            "name": "Belajar Huruf Jepang & Persiapan JLPT N5",
            "description": "Kuasai 46 Hiragana, 46 Katakana, dan Kanji N5 dengan flashcard adaptif dan spaced repetition.",
            "provider": {
                "@@type": "Organization",
                "name": "{{ $siteName }}",
                "sameAs": "{{ $siteUrl }}"
            },
            "educationalLevel": "Beginner",
            "inLanguage": "id",
            "isAccessibleForFree": true,
            "hasCourseInstance": {
                "@@type": "CourseInstance",
                "courseMode": "Online"
            }
        }
        @if(!empty($faqItems))
        ,
        {
            "@@type": "FAQPage",
            "mainEntity": [
                @foreach($faqItems as $index => $item)
                {
                    "@@type": "Question",
                    "name": {!! json_encode($item['q'] ?? $item['question'] ?? '') !!},
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": {!! json_encode($item['a'] ?? $item['answer'] ?? '') !!}
                    }
                }@if(!$loop->last),@endif
                @endforeach
            ]
        }
        @endif
    ]
}
</script>

