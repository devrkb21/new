<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relationships for better performance
        $query = Ticket::with('user', 'site')->latest();

        // Handle status filter (existing)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // --- NEW: Handle search query ---
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('id', $searchTerm) // Allow searching by Ticket ID
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('site', function ($siteQuery) use ($searchTerm) {
                        $siteQuery->where('domain', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Paginate results and append query strings to pagination links
        $tickets = $query->paginate(20)->withQueryString();

        return view('admin.support.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('replies.user', 'site'); // Also load site relationship here
        return view('admin.support.tickets.show', compact('ticket'));
    }

    public function storeReply(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        // Set ticket status to 'in-progress' if an admin replies
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in-progress']);
        }


        return back()->with('success', 'Your reply has been posted.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in-progress,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        return back()->with('success', 'Ticket status has been updated.');
    }
}