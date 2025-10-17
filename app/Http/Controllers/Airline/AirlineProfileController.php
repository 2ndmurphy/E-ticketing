<?php

namespace App\Http\Controllers\Airline;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airline;
use Illuminate\Support\Facades\Auth;

class AirlineProfileController extends Controller
{
    /**
     * Display the airline profile.
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user->isMaskapai()) {
            abort(403, 'Access denied.');
        }

        $airline = Airline::where('manage_by_user_id', $user->id)->firstOrFail();

        return view('pages.airline.profile.edit', compact('airline'));
    }

    /**
     * Update airline profile info.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $airline = Airline::where('manage_by_user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $airline->update($validated);

        return redirect()->route('maskapai.profile.edit')
            ->with('success', 'Airline profile updated successfully!');
    }
}
