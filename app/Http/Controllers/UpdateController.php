<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use App\Services\UpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UpdateController extends Controller
{
    /**
     * Inisialisasi controller dengan UpdateService dan GamificationService.
     */
    public function __construct(
        private UpdateService $updateService,
        private GamificationService $gamificationService
    ) {
    }

    /**
     * Tampilkan halaman utama Info Pembaruan & Catatan Rilis.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $category = $request->query('kategori');
        $search = $request->query('q');

        $user = Auth::user();
        $gamiStats = $user ? $this->gamificationService->stats($user) : [
            'xp'     => 0,
            'streak' => 0,
            'level'  => 1,
            'title'  => 'Tamu Pembelajar',
        ];

        $versions = $this->updateService->getFilteredVersions($category, $search);
        $currentMeta = $this->updateService->getCurrentVersion();
        $latestRelease = $this->updateService->getLatestRelease();
        $roadmap = $this->updateService->getRoadmap();
        $announcements = $this->updateService->getActiveAnnouncements();
        $stats = $this->updateService->getStats();
        $faq = $this->updateService->getFaq();

        return view('dashboard.updates.index', [
            'pageTitle'       => 'Info Pembaruan & Versi',
            'pageSubtitle'    => 'Catatan rilis fitur, pembaruan performa, dan rencana pengembangan JP Learn.',
            'user'            => $user,
            'currentUser'     => $user,
            'gamiStats'       => $gamiStats,
            'versions'        => $versions,
            'currentMeta'     => $currentMeta,
            'latestRelease'   => $latestRelease,
            'roadmap'         => $roadmap,
            'announcements'   => $announcements,
            'stats'           => $stats,
            'faq'             => $faq,
            'activeCategory'  => $category ?: 'all',
            'searchQuery'     => $search ?: '',
        ]);
    }

    /**
     * Endpoint API JSON untuk modal pop-up "Apa yang Baru?".
     *
     * @return JsonResponse
     */
    public function latest(): JsonResponse
    {
        $latest = $this->updateService->getLatestRelease();
        $current = $this->updateService->getCurrentVersion();

        return response()->json([
            'status'  => 'success',
            'version' => $current['current_version'],
            'name'    => $current['version_name'],
            'date'    => $current['release_date'],
            'latest'  => $latest,
        ]);
    }
}

