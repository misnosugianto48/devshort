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

        $period = $request->input('period', '30d');
        $startDate = match ($period) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            'all' => \Carbon\Carbon::create(2000, 1, 1),
            default => now()->subDays(30),
        };

        $baseQuery = $link->clicks()->where('created_at', '>=', $startDate);

        // Timeline
        $clickData = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Devices
        $deviceData = (clone $baseQuery)
            ->selectRaw('device, COUNT(*) as count')
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();

        // Browsers
        $browserData = (clone $baseQuery)
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // OS
        $osData = (clone $baseQuery)
            ->selectRaw('os, COUNT(*) as count')
            ->groupBy('os')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Countries
        $countryData = (clone $baseQuery)
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Referers
        $refererData = (clone $baseQuery)
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('links.show', compact(
            'link', 'clickData', 'deviceData', 'browserData', 'osData', 'countryData', 'refererData', 'period'
        ));
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

    /**
     * Export link analytics data as CSV.
     */
    public function export(Request $request, Link $link)
    {
        if ($link->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="analytics-'.$link->short_code.'-'.date('Y-m-d').'.csv"',
        ];

        return response()->streamDownload(function () use ($link) {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, ['Waktu', 'IP Address', 'Negara', 'Perangkat', 'Browser', 'OS', 'Referer']);

            // Add data rows, chunked to save memory
            $link->clicks()->latest()->chunk(500, function ($clicks) use ($handle) {
                foreach ($clicks as $click) {
                    fputcsv($handle, [
                        $click->created_at->format('Y-m-d H:i:s'),
                        $click->ip_address ?? 'Unknown',
                        $click->country ?? 'Unknown',
                        $click->device ?? 'Unknown',
                        $click->browser ?? 'Unknown',
                        $click->os ?? 'Unknown',
                        $click->referer ?? 'Direct',
                    ]);
                }
            });

            fclose($handle);
        }, 'analytics-'.$link->short_code.'-'.date('Y-m-d').'.csv', $headers);
    }
}
