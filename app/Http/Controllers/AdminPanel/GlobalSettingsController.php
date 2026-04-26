<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlobalSettingsController extends Controller
{
    /**
     * Show the Global Settings page.
     */
    public function index(Request $request)
    {
        $userId   = $request->user()->id;
        $settings = UserSetting::getAllForUser($userId);

        return view('admin.global-settings.index', compact('settings'));
    }

    /**
     * Persist user-specific settings (AJAX).
     */
    public function save(Request $request): JsonResponse
    {
        $request->validate([
            'theme_mode'         => 'required|in:light,dark,system',
            'theme_color_preset' => 'required|in:biogenix-green,forest-green,modern-indigo,midnight-black',
        ]);

        $userId = $request->user()->id;

        UserSetting::saveMany($userId, [
            'theme.mode'         => $request->input('theme_mode'),
            'theme.color_preset' => $request->input('theme_color_preset'),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Settings saved successfully.',
            'settings' => UserSetting::getAllForUser($userId),
        ]);
    }

    /**
     * Reset all user settings to defaults.
     */
    public function reset(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        UserSetting::resetForUser($userId);

        return response()->json([
            'success'  => true,
            'message'  => 'Settings reset to defaults.',
            'settings' => UserSetting::DEFAULTS,
        ]);
    }
}
