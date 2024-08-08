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

    public function index()
    {
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

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

    public function show(Contact $contact)
    {
        // sleep(2); // for test cache
        return view('contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());
        ResponseCache::clear();
        return redirect()->route('contacts.index')
            ->with('success', __('Contact Updated Successfully.'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        ResponseCache::clear();
        return redirect()->route('contacts.index')
            ->with('success', __('Contact Deleted Successfully.'));
    }
}
