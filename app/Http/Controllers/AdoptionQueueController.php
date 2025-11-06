<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdoptionQueueController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->string('status')->toString(); // optional filter

        $animals = Animal::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('priority')       // 1 = highest
            ->orderBy('updated_at', 'desc')
            ->get(['id','name','species','status','priority']);

        return Inertia::render('AdoptionQueue', [
            'filters' => [
                'status' => $status,
            ],
            'animals' => $animals,
        ]);
    }

    public function updatePriority(Request $request, Animal $animal): RedirectResponse
    {
        $validated = $request->validate([
            'priority' => ['required','integer','min:1','max:10'],
        ]);

        $animal->update(['priority' => $validated['priority']]);

        // For Inertia, redirect back to the same page and preserve state
        return back()->with('success', 'Priority updated.');
    }

    public function resetPriorities(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['nullable','in:available,hold,pending,adopted'],
            'to'     => ['nullable','integer','min:1','max:10'], // default 3
        ]);

        $to = $validated['to'] ?? 3;

        $query = Animal::query();
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $count = $query->update(['priority' => $to]);

        return back()->with('success', "Reset priorities to {$to} for {$count} animal(s).");
    }
}
