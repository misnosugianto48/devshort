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
        $query = $request->user()->links();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_code', 'like', "%{$search}%")
                    ->orWhere('original_url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $links = $query->latest()->paginate(15)->withQueryString();

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
            $expiresAt,
            $request->validated('password')
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

    /**
     * Update the specified link.
     */
    public function update(Request $request, Link $link): RedirectResponse
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $link->update([
            'title' => $validated['title'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('status', 'Tautan berhasil diperbarui!');
    }

    /**
     * Remove the specified link.
     */
    public function destroy(Request $request, Link $link): RedirectResponse
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $link->delete(); // Soft delete

        return redirect()->back()->with('status', 'Tautan berhasil dihapus!');
    }

    /**
     * Perform bulk actions on links.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:links,id',
        ]);

        $query = $request->user()->links()->whereIn('id', $validated['ids']);

        switch ($validated['action']) {
            case 'delete':
                $query->delete();
                $message = 'Tautan terpilih berhasil dihapus!';
                break;
            case 'activate':
                $query->update(['is_active' => true]);
                $message = 'Tautan terpilih berhasil diaktifkan!';
                break;
            case 'deactivate':
                $query->update(['is_active' => false]);
                $message = 'Tautan terpilih berhasil dinonaktifkan!';
                break;
        }

        return redirect()->back()->with('status', $message ?? 'Aksi berhasil!');
    }
}
