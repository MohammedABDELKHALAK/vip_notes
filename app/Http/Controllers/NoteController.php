<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelsLog;
use App\Models\Note;
use App\Models\User;
use App\Notifications\NoteSentNotification;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Notifications\DatabaseNotification;


use Illuminate\Support\Facades\Log;



class NoteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $notes = Note::where('user_id', Auth::id())->paginate(10);

        // Check if the request is AJAX (for pagination)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'notes' => $notes,
                'links' => (string) $notes->links(), // Converts pagination links to a stringt   
            ]);
        }
        // return response()->json(['success' => true, 'notes' => $notes, 'noteCount' => $noteCount]);
        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Authorize the user using the NotePolicy
        $this->authorize('create', Note::class);
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Retrieve all request data
        $data = $request->all();

        // Decode any HTML entities
        $data['content'] = html_entity_decode($data['content']);

        // Create a new HTMLPurifier instance with default config. package was composer require ezyang/htmlpurifier
        // there are other packages as "composer require devpark/laravel-html-sanitizer" and "composer require paragonie/cage"
        $config = HTMLPurifier_Config::createDefault();

        // Allow specific HTML tags and attributes
        $config->set('HTML.Allowed', 'p,b,strong,i,em,a[href],ul,ol,li,br,blockquote'); // Removed img for testing
        $config->set('HTML.ForbiddenElements', 'script,script[src],img[src],iframe'); // Ensure to block harmful elements
        $config->set('CSS.AllowedProperties', 'color,font-size,text-align'); // Specify allowed CSS properties

        $config->set('HTML.ForbiddenElements', 'script,iframe,style'); // Disallow harmful tags
        $config->set('HTML.ForbiddenAttributes', 'onclick,onmouseover,onerror,onload,onunload'); // Block harmful attributes

        // Create the purifier instance with the custom configuration
        $purifier = new HTMLPurifier($config);
        Log::info('Raw input data:', ['content' => $data['content']]);

        // Sanitize the content to prevent XSS and other vulnerabilities
        $data['content'] = $purifier->purify($data['content']);

        // Add the authenticated user's ID
        $data['user_id'] = Auth::id();

        // Create a new note
        $note = Note::create($data);

        // Redirect or return response after creating the note
        return redirect()->route('notes.index')->with('success-create', 'Note created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id, $token)
    {
        // $note = Note::findOrFail($id);
        $note = Note::where('id', $id)->where('access_token', $token)->firstOrFail();

        // Check if the note was already opened
        if ($note->is_opened) {
            // If the note has already been opened, deny access
            // return response()->json(['message' => 'This note is no longer accessible'], 403);
            return redirect()->back();
        }

        // Mark the note as opened
        if (!$note->is_opened && Auth::id() != $note->user_id) {
            $note->is_opened = true;
            $note->save();
        }

        // Log access details if needed
        ModelsLog::create([
            'note_id' => $note->id,
            'opened_at' => now(),
            'user_ip' => request()->ip()
        ]);

        // Get all users except the authenticated user
        $users = User::where('id', '!=', auth()->id())->select(['id', 'name'])->get();
        // foreach($users as $user) {
        // dd($user->id);}

        return view('notes.show', compact(['note', 'users']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $notes = Note::with('user')->findOrFail($id);
        $note = Note::findOrFail($id);

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::findOrFail($id);

        $new_data = $request->all();

        // Update the note using the validated data
        $note->update($new_data);

        return redirect()->route('notes.index')->with('success-update', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //  Note::destroy($id);

        $note = Note::findOrFail($id); // Find the note
        $note->delete(); // Delete the note

        // Set the session message for page reloads
        // session()->flash('success-delete', 'Note deleted successfully.');

        // Return a JSON response
        return response()->json([
            'success' => true,
            'delete-message' => 'Note deleted successfully.',
            'note_id' => $id,
        ]);

        // return redirect()->back()->with('success', 'Note deleted successfully.');
    }



    // ****************************************************
    //   ***************  Other methods  ***************
    // *****************************************************


    // public function regenerateToken($id)
    // {
    //     // Find the note by its ID
    //     $note = Note::findOrFail($id);

    //     if ($note) {
    //         // Regenerate a new token
    //         $note->access_token = Str::random(32);
    //         $note->is_opened = false;

    //         // Save the updated note
    //         $note->save();

    //         // Return the new token in the response
    //         return response()->json([
    //             'success' => true,
    //             'token' => $note->access_token,
    //             'is_opened' => $note->is_opened
    //         ]);
    //     }

    //     return response()->json(['success' => false], 404);
    // }

    public function toggleOpenStatus($id)
    {
        // Find the note by its ID
        $note = Note::findOrFail($id);

        if ($note) {
            // Toggle the open status (1 if turned on, 0 if turned off)
            $note->is_opened = !$note->is_opened ? 1 : 0; // Ensure it's either 1 or 0

            // Save the updated note
            $note->save();

            // Return the new status in the response
            return response()->json([
                'success' => true,
                'is_opened' => $note->is_opened,
                'access_token' => $note->access_token
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function sendNote(Request $request, $id)
    {
        // dd($request->input('user') );
        Log::info('sendNote method called', ['id' => $id, 'users' => $request->input('users')]);

        $note = Note::findOrFail($id);

        // Get the user ID from the request
        $reciever_user_id = $request->input('users');

        // Fetch the user model using the ID
        $reciever_users = User::findOrFail($reciever_user_id);

        // Loop through each user and send the notification
        foreach ($reciever_users as $reciever_user) {
            $reciever_user->notify(new NoteSentNotification($note));
        }

        // if($note){
        //     $note->update([
        //        'receiver_id' => $request->receiver_id,
        //        'sent_at' => now()
        //     ]);

        //     return response()->json([
        //        'success' => true,
        //        'message' => 'Note sent successfully.',
        //        'receiver_id' => $request->receiver_id,
        //        'sent_at' => $note->sent_at
        //     ]);
        // }

        // return response()->json(['success' => false], 404);
        return redirect()->route('notes.index')->with('success-send', 'Note sent successfully!');;
    }

    public function markAsRead($id)
    {
        // // Find the notification by ID and mark it as read
        // $notification = auth()->user()->notifications->find($id);

        // if ($notification && is_null($notification->read_at)) {
        //     $notification->markAsRead(); // This will set 'read_at' to the current timestamp
        //     // return response()->json(['success' => true]);
        //     return redirect()->route('notes.index');


        // Find the notification by ID and make sure it belongs to the authenticated user
        //This retrieves the notification based on its id and confirms it belongs to the authenticated user by checking notifiable_id
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->first();
        // Mark it as read if it exists
        if ($notification) {
            //This method updates the read_at column to the current timestamp.
            $notification->markAsRead();
        }

        // Redirect to the desired page, like the notification link
        return redirect()->route('notes.show', [
            'id' => $notification->data['note_id'],
            'token' => $notification->data['note_token']
        ]);

        // return response()->json(['success' => false], 404);
    }



    public function ExpiredNotes()
    {

        $notifications = DatabaseNotification::where('notifiable_id', auth()->id())->where('read_at', '!=', null)->get();

        return view('notes.expired', compact('notifications'));
    }

    public function RecievedNotes()
    {
        // Note::where('user_id', Auth::id())->get();
        $notifications = DatabaseNotification::where('notifiable_id', auth()->id())->where('read_at',  null)->get();

        return view('notes.recieved_notes', compact('notifications'));
    }
}
