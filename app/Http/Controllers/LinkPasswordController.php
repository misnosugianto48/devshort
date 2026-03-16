<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LinkPasswordController extends Controller
{
    /**
     * Verify the password for a protected link.
     */
    public function verify(Request $request, Link $link)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $link->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password salah. Silakan coba lagi.',
            ]);
        }

        // Store a session flag that this link is unlocked
        session()->put('link_unlocked_'.$link->id, true);

        // Redirect back to the short link resolution route
        return redirect()->route('link.redirect', $link->short_code);
    }
}
