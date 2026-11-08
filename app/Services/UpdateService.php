<?php

namespace App\Services;

class UpdateService
{
    /**
     * Ambil metadata versi aplikasi terkini.
     *
     * @return array{current_version: string, version_name: string, release_date: string, environment: string, system_status: array<string, mixed>}
     */
    public function getCurrentVersion(): array
    {
        return [
            'current_version' => (string) config('updates.current_version', 'v2.4.0'),
            'version_name'    => (string) config('updates.version_name', 'Sakura Milestone'),
            'release_date'    => (string) config('updates.release_date', '8 November 2026'),
            'environment'     => (string) config('updates.environment', 'production'),
            'system_status'   => (array) config('updates.system_status', [
                'status'       => 'operational',
                'label'        => 'Semua Sistem Operasional',
                'last_checked' => 'Hari Ini',
                'ai_engine'    => 'Online',
                'database'     => 'Optimal',
            ]),
        ];
    }

    /**
     * Ambil seluruh riwayat versi aplikasi secara berurutan.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllVersions(): array
    {
        return (array) config('updates.versions', []);
    }

    /**
     * Ambil data rilis versi paling baru (posisi pertama).
     *
     * @return array<string, mixed>|null
     */
    public function getLatestRelease(): ?array
    {
        $versions = $this->getAllVersions();

        return !empty($versions) ? $versions[0] : null;
    }

    /**
     * Cari detail rilis berdasarkan nomor versi (misal: 'v2.4.0').
     *
     * @param string $versionCode
     * @return array<string, mixed>|null
     */
    public function findByVersion(string $versionCode): ?array
    {
        $versions = $this->getAllVersions();

        foreach ($versions as $item) {
            if (isset($item['version']) && strtolower($item['version']) === strtolower($versionCode)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Filter riwayat pembaruan berdasarkan kategori dan kata kunci pencarian.
     *
     * @param string|null $category
     * @param string|null $search
     * @return array<int, array<string, mixed>>
     */
    public function getFilteredVersions(?string $category = null, ?string $search = null): array
    {
        $versions = $this->getAllVersions();

        if (empty($category) && empty($search)) {
            return $versions;
        }

        $searchLower = !empty($search) ? mb_strtolower(trim($search)) : null;

        return array_values(array_filter($versions, function (array $version) use ($category, $searchLower): bool {
            // Filter Kategori
            if (!empty($category) && $category !== 'all') {
                $hasCategoryItems = match ($category) {
                    'features'     => !empty($version['changes']['fitur_baru'] ?? []),
                    'improvements' => !empty($version['changes']['peningkatan'] ?? []),
                    'fixes'        => !empty($version['changes']['perbaikan'] ?? []),
                    default        => true,
                };

                if (!$hasCategoryItems) {
                    return false;
                }
            }

            // Filter Kata Kunci
            if (!empty($searchLower)) {
                $searchableText = mb_strtolower(
                    ($version['version'] ?? '') . ' ' .
                    ($version['title'] ?? '') . ' ' .
                    ($version['summary'] ?? '') . ' ' .
                    implode(' ', $version['highlights'] ?? [])
                );

                // Tambahkan teks fitur ke dalam pencarian
                foreach ($version['changes'] ?? [] as $changeType => $items) {
                    foreach ($items as $item) {
                        $searchableText .= ' ' . mb_strtolower(($item['title'] ?? '') . ' ' . ($item['desc'] ?? ''));
                    }
                }

                if (!str_contains($searchableText, $searchLower)) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
     * Ambil roadmap / rencana fitur masa depan.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRoadmap(): array
    {
        return (array) config('updates.roadmap', []);
    }

    /**
     * Ambil pengumuman aktif.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActiveAnnouncements(): array
    {
        $announcements = (array) config('updates.announcements', []);

        return array_values(array_filter($announcements, function (array $ann): bool {
            return !empty($ann['is_active']);
        }));
    }

    /**
     * Ambil daftar FAQ seputar pembaruan sistem.
     *
     * @return array<int, array<string, string>>
     */
    public function getFaq(): array
    {
        return (array) config('updates.faq', []);
    }

    /**
     * Ambil statistik ringkas pembaruan platform.
     *
     * @return array{current_version: string, total_releases: int, total_features_count: int, latest_release_date: string}
     */
    public function getStats(): array
    {
        $versions = $this->getAllVersions();
        $totalFeatures = 0;

        foreach ($versions as $ver) {
            $totalFeatures += count($ver['changes']['fitur_baru'] ?? []);
        }

        return [
            'current_version'      => (string) config('updates.current_version', 'v2.4.0'),
            'total_releases'       => count($versions),
            'total_features_count' => $totalFeatures,
            'latest_release_date'  => !empty($versions[0]['release_date']) ? $versions[0]['release_date'] : 'November 2026',
        ];
    }
}

