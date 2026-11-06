<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActivityController extends Controller
{
    public function __construct(private GamificationService $gamification)
    {
    }

    /**
     * Catat aktivitas belajar dari browser (flashcard, menulis, percakapan) dan beri XP.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', Rule::in(GamificationService::CLIENT_ACTIONS)],
        ]);

        $result = $this->gamification->award($request->user(), $validated['action']);

        return response()->json($result);
    }
}

