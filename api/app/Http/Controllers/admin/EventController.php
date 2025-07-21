<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('limit', 10);
        $events = Event::paginate($perPage);

        return response()->json($events);
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'location' => ['required', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $event = Event::create($validated);

        return response()->json($event, 201);
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'location' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }

    /**
     * Search for events based on query parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = Event::query();

        // Search by name or description
        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by status
        if ($request->has('active')) {
            $isActive = $request->boolean('active');
            $query->where('is_active', $isActive);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $dateFrom = $request->date('date_from');
            $query->where('start_date', '>=', $dateFrom);
        }

        if ($request->has('date_to')) {
            $dateTo = $request->date('date_to');
            $query->where('end_date', '<=', $dateTo);
        }

        if ($request->has('current')) {
            $today = now()->format('Y-m-d');
            $query->where('start_date', '<=', $today)
                  ->where('end_date', '>=', $today);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'start_date');
        $sortDir = $request->input('sort_dir', 'asc');

        if (in_array($sortBy, ['name', 'start_date', 'end_date', 'location', 'created_at'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $perPage = $request->input('limit', 10);
        $events = $query->paginate($perPage);

        return response()->json($events);
    }

    /**
     * Get event statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $today = now()->format('Y-m-d');

        $stats = [
            'total' => Event::count(),
            'active' => Event::where('is_active', true)->count(),
            'inactive' => Event::where('is_active', false)->count(),
            'current' => Event::where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->count(),
            'upcoming' => Event::where('start_date', '>', $today)->count(),
            'past' => Event::where('end_date', '<', $today)->count(),
            'recent' => Event::orderBy('created_at', 'desc')->limit(5)->get(),
            'nextEvents' => Event::where('start_date', '>', $today)
                ->orderBy('start_date', 'asc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Toggle the active status of an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActive(Event $event)
    {
        $event->is_active = !$event->is_active;
        $event->save();

        return response()->json([
            'message' => 'Event status updated successfully',
            'is_active' => $event->is_active,
        ]);
    }

    /**
     * Get dates array for an event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDates(Event $event)
    {
        return response()->json([
            'dates' => $event->getDatesArray(),
            'start_date' => $event->getFormattedStartDateAttribute(),
            'end_date' => $event->getFormattedEndDateAttribute(),
        ]);
    }
}
