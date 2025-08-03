<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Eager load the site relationship
        $query = Auth::user()->tickets()->with('site')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(10);

        return view('support.tickets.index', compact('tickets'));
    }

    public function create()
    {
        // Fetch the user's sites to pass to the view
        $sites = Auth::user()->sites;
        return view('support.tickets.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // Ensure the selected site_id exists and belongs to the current user
            'site_id' => [
                'required',
                Rule::exists('sites', 'id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ],
            'message' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:2048',
        ]);

        $ticketData = $validated; // The validated data already contains site_id

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket_attachments', 'private');
            $ticketData['attachment_path'] = $path;
        }

        $user->tickets()->create($ticketData);

        return redirect()->route('support.tickets.index')->with('success', 'Your support ticket has been submitted!');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager load the site relationship here as well
        $ticket->load('replies.user', 'site');

        return view('support.tickets.show', compact('ticket'));
    }

    public function storeReply(Request $request, Ticket $ticket)
    {
        // Authorize that the user owns the ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        // Re-open the ticket if it was closed
        $ticket->update(['status' => 'open']);

        return back()->with('success', 'Your reply has been submitted.');
    }
    public function close(Ticket $ticket)
    {
        // Authorize that the user owns the ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        // Update the ticket's status to 'closed'
        $ticket->update(['status' => 'closed']);

        return redirect()->route('support.tickets.index')->with('success', 'Ticket has been successfully closed.');
    }
    public function downloadAttachment(Ticket $ticket)
    {
        // Authorize that the user owns the ticket
        if ($ticket->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        // Ensure there is an attachment
        if (!$ticket->attachment_path) {
            abort(404);
        }

        return Storage::disk('private')->download($ticket->attachment_path);
    }
}