<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\imageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ContactsController extends Controller
{

    use imageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $contacts = $user->contacts()->paginate(10);

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
    public function store(Request $request)
    {
       // dd($request->all());
        $validatedData = Validator($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:contacts',
            'phone' => 'nullable|string',
            'job' => 'nullable|string',
            'education' => 'required|string',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'language' => 'nullable|in:English,Arabic,French',
            'dob' => 'nullable|date',
        ]);


        if ($validatedData->fails()) {
            toastr()->error($validatedData->getMessageBag()->first());
            return redirect()->back();
        }

        $Data = $request->except('image');

        if ($request->hasFile('image')) {
            $coverImage = $request->file('image');
            $coverImagePath = $this->storeImage($coverImage);
            $Data['image'] = $coverImagePath;
        }
//        $request->merge([
//            'image' => $coverImagePath
//        ]);
//        dd($coverImagePath,$request->alll());
        auth()->user()->contacts()->create($Data);
        toastr()->success('Contact has been created successfully!');
        return redirect()->route('contacts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contacts::find($id);
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $contact = Contacts::findOrFail($id);
        return view('contacts.edit', compact('contact'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contacts $contact)
    {
        $validatedData = Validator($request->all(), [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('contacts')->ignore($contact->id),
            ],
            'phone' => 'nullable|string',
            'job' => 'nullable|string',
            'education' => 'required|string',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'language' => 'nullable|in:English,Arabic,French',
            'cv' => 'nullable|mimes:pdf|max:2048',
            'dob' => 'nullable|date',
        ]);

        if ($validatedData->fails()) {
            toastr()->error($validatedData->getMessageBag()->first());
            return redirect()->back();
        }
        $Data = $request->except('image');


        if ($request->hasFile('image')) {
            $contactImage = $request->file('image');
            $contactImageName = $this->storeImage($contactImage);

            // Delete previous cover image if it exists
            if ($contact->image) {
                $this->deleteImage($contact->image);
            }

            $Data['image'] = $contactImageName;
        }

        $contact->update($Data);

        return redirect()->route('contacts.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Contacts::findOrFail($id)->delete();
        return response()->json(['message' => 'Ticket deleted.']);
    }

    public function approveStatus(Contacts $contact)
    {
        $contact->update(['is_approved' => true]);

        return response()->json(['is_approved' => true]);
    }
}
