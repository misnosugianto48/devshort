<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LinkService
{
    /**
     * Create a new short link for a user.
     */
    public function createLink(User $user, string $originalUrl, ?string $title = null, ?string $customAlias = null, ?\Carbon\Carbon $expiresAt = null, ?string $password = null): Link
    {
        $shortCode = $customAlias ?: $this->generateShortCode();

        $link = $user->links()->create([
            'original_url' => $originalUrl,
            'short_code' => $shortCode,
            'title' => $title,
            'is_active' => true,
            'expires_at' => $expiresAt,
            'password' => $password ? Hash::make($password) : null,
        ]);

        // Pre-cache the link to save an initial DB query on first visit
        $this->cacheLink($link);

        return $link;
    }

    /**
     * Generate a unique short code.
     */
    public function generateShortCode(int $length = 6): string
    {
        do {
            // using base62 equivalent characters
            $shortCode = Str::random($length);
        } while (Link::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    /**
     * Resolve a short code to a Link model, using cache-first approach.
     */
    public function resolveShortCode(string $code): ?Link
    {
        $cacheKey = "link:{$code}";

        // Try to get from cache first (1-5ms response time)
        return Cache::rememberForever($cacheKey, function () use ($code) {
            return Link::where('short_code', $code)->first();
        });
    }

    /**
     * Validate a URL before saving.
     */
    public function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Update the cache for a specific link.
     */
    public function cacheLink(Link $link): void
    {
        $cacheKey = "link:{$link->short_code}";

        if ($link->is_active) {
            Cache::put($cacheKey, $link);
        } else {
            Cache::forget($cacheKey);
        }
    }
}
