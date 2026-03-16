<?php

namespace App\Http\Controllers;

use App\Services\LinkService;

class PreviewController extends Controller
{
    /**
     * Show the preview page for a short link.
     */
    public function show($shortCode, LinkService $linkService)
    {
        $link = $linkService->resolveShortCode($shortCode);

        if (! $link) {
            abort(404, 'Link not found');
        }

        if ($link->expires_at && $link->expires_at->isPast()) {
            abort(410, 'Link has expired');
        }

        if (! $link->is_active) {
            abort(404, 'Link is inactive');
        }

        return view('links.preview', compact('link', 'shortCode'));
    }
}
