<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\ResponseCache\Facades\ResponseCache;

class ContactController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('do.not.cache.response', except: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        Contact::create([
            'name' => $request->name,
            'email' => $request->email
        ]);
        ResponseCache::clear();
        return redirect()->route('contacts.index')
            ->with('success', __('Contact Created Successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // sleep(2); // for test cache
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());
        ResponseCache::clear();
        return redirect()->route('contacts.index')
            ->with('success', __('Contact Updated Successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        ResponseCache::clear();
        return redirect()->route('contacts.index')
            ->with('success', __('Contact Deleted Successfully.'));
    }
}
