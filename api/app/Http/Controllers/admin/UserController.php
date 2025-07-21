<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('limit', 10);
        $users = User::paginate($perPage);

        // Les rôles et permissions sont maintenant inclus automatiquement
        // pour chaque utilisateur grâce à l'attribut role_and_permissions dans $appends
        return response()->json($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['sometimes', 'string', Rule::in(array_column(UserRole::cases(), 'value'))],
        ]);

        $user = User::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'username' => $validated['username'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'preferences' => [
                'theme' => 'system',
                'language' => 'en',
                'notifications' => true,
                'emailNotifications' => false
            ]
        ]);

        // Assign role to user
        $role = $validated['role'] ?? UserRole::USER->value;
        $user->assignRole($role);

        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        // Les rôles et permissions sont maintenant inclus automatiquement
        // grâce à l'attribut role_and_permissions qui est dans $appends
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstName' => ['sometimes', 'string', 'max:255'],
            'lastName' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['sometimes', 'string', Rule::in(array_column(UserRole::cases(), 'value'))],
            'isActive' => ['sometimes', 'boolean'],
            'preferences' => ['sometimes', 'array'],
        ]);

        $data = [];

        if (isset($validated['firstName'])) {
            $data['first_name'] = $validated['firstName'];
        }

        if (isset($validated['lastName'])) {
            $data['last_name'] = $validated['lastName'];
        }

        if (isset($validated['email'])) {
            $data['email'] = $validated['email'];
        }

        if (array_key_exists('username', $validated)) {
            $data['username'] = $validated['username'];
        }

        if (array_key_exists('phone', $validated)) {
            $data['phone'] = $validated['phone'];
        }

        if (isset($validated['isActive'])) {
            $data['is_active'] = $validated['isActive'];
        }

        if (isset($validated['preferences'])) {
            $data['preferences'] = $validated['preferences'];
        }

        // Update user data
        $user->update($data);

        // Update role if provided
        if (isset($validated['role'])) {
            // Remove all current roles and assign the new one
            $user->syncRoles([$validated['role']]);
        }

        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'currentPassword' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($validated['currentPassword'], $user->password)) {
            return response()->json(['message' => 'The provided current password is incorrect.'], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * Search for users based on query parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = User::query();

        // Search by name
        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $roleName = $request->input('role');
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            });
        }

        // Filter by status
        if ($request->has('active')) {
            $isActive = $request->boolean('active');
            $query->where('is_active', $isActive);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        if (in_array($sortBy, ['first_name', 'last_name', 'email', 'created_at', 'last_login'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $perPage = $request->input('limit', 10);
        $users = $query->paginate($perPage);

        // Les rôles et permissions sont maintenant inclus automatiquement
        // pour chaque utilisateur grâce à l'attribut role_and_permissions dans $appends
        return response()->json($users);
    }

    /**
     * Get user statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        // Get role counts using Spatie's role system
        $roleCounts = [];
        foreach (UserRole::cases() as $role) {
            $roleName = $role->value;
            $roleCounts[$roleName] = Role::where('name', $roleName)
                ->first()
                ->users()
                ->count();
        }

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'roles' => $roleCounts,
            'recent' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'lastActive' => User::whereNotNull('last_login')
                ->orderBy('last_login', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Update the user's preferences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePreferences(Request $request, User $user)
    {
        $validated = $request->validate([
            'theme' => ['sometimes', 'string', 'in:light,dark,system'],
            'language' => ['sometimes', 'string', 'max:10'],
            'notifications' => ['sometimes', 'boolean'],
            'emailNotifications' => ['sometimes', 'boolean'],
        ]);

        // Get current preferences and update with new values
        $preferences = $user->preferences;

        foreach ($validated as $key => $value) {
            $preferences[$key] = $value;
        }

        $user->update([
            'preferences' => $preferences,
        ]);

        return response()->json([
            'message' => 'User preferences updated successfully',
            'preferences' => $user->preferences,
        ]);
    }
}
