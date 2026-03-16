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
