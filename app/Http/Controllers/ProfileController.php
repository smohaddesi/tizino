<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
            'grades' => Grade::query()->orderBy('id')->pluck('title', 'id'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'grade_id' => ['nullable', 'exists:grades,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($request->filled('password')) {
            if (! Hash::check($request->input('current_password'), $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'رمز عبور فعلی درست نیست.'])
                    ->withInput();
            }

            $user->password = $request->input('password');
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->grade_id = $validated['grade_id'] ?? null;
        $user->save();

        return back()->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }
}
