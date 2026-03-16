<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLinkRequest;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function __construct(
        protected LinkService $linkService
    ) {}

    /**
     * Display a paginated listing of the user's links.
     */
    public function index(Request $request): View
    {
        $links = $request->user()
            ->links()
            ->latest()
            ->paginate(15);

        return view('links.index', compact('links'));
    }

    /**
     * Store a newly created link in storage.
     */
    public function store(CreateLinkRequest $request): RedirectResponse
    {
        $expiresAt = $request->validated('expires_at')
            ? \Carbon\Carbon::parse($request->validated('expires_at'))
            : null;

        $link = $this->linkService->createLink(
            $request->user(),
            $request->validated('original_url'),
            $request->validated('title'),
            $request->validated('custom_alias'),
            $expiresAt
        );

        return redirect()->back()->with('status', 'Tautan berhasil diperpendek!');
    }

    /**
     * Display the analytics for the specified link.
     */
    public function show(Request $request, Link $link): View
    {
        // Authorization check: ensure user owns the link
        if ($link->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get basic click stats grouped by date (last 30 days) for MVP
        $clickData = $link->clicks()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('links.show', compact('link', 'clickData'));
    }

    // edit, update, destroy omitted for Phase 1 MVP (CRUD management moved to Phase 2)
}
