<?php

namespace App\Http\Controllers;

use App\Jobs\RecordClickJob;
use App\Services\LinkService;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __construct(
        protected LinkService $linkService
    ) {}

    /**
     * Handle the incoming short link request.
     */
    public function __invoke(Request $request, string $shortCode)
    {
        $link = $this->linkService->resolveShortCode($shortCode);

        if (! $link) {
            abort(404, 'Link not found');
        }

        // Check if the link has expired
        if ($link->expires_at && $link->expires_at->isPast()) {
            abort(410, 'Link has expired');
        }

        // If it's deactivated manually (but not expired), return 404
        if (! $link->is_active) {
            abort(404, 'Link is inactive');
        }

        // Dispatch job to record the click analytics asynchronously
        RecordClickJob::dispatch(
            $link,
            $request->ip(),
            $request->userAgent(),
            $request->headers->get('referer')
        );

        // Perform 301 Permanent Redirect for SEO benefits
        return redirect()->away($link->original_url, 301);
    }
}
