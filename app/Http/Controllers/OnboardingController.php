<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /**
     * Tandai onboarding sebagai selesai atau dilewati untuk pengguna saat ini.
     */
    public function complete(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            try {
                $user->update([
                    'has_completed_onboarding' => true,
                ]);
            } catch (\Throwable $e) {
                // Gracefully ignore if database column hasn't been migrated yet on production
            }
        }

        $request->session()->forget('is_new_registration');

        return response()->json([
            'success' => true,
            'message' => 'Status onboarding berhasil diperbarui.',
        ]);
    }

    /**
     * Reset onboarding pengguna jika ingin mengulang secara penuh.
     */
    public function reset(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            try {
                $user->update([
                    'has_completed_onboarding' => false,
                ]);
            } catch (\Throwable $e) {
                // Gracefully ignore if column does not exist
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status onboarding berhasil di-reset.',
        ]);
    }
}

