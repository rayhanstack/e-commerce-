<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)->get();

        return view('customer::profile', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:20|unique:users,mobile_number,'.$user->id,
            'alternate_phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
