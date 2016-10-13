<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests;
use Illuminate\Http\Request;

class Contacts extends Controller
{
    public function index(Request $request)
    {
        if($group_id = ($request->get('group_id'))) {
            $contacts = Contact::where('group_id', $group_id)->orderBy('id', 'desc')->paginate(4);
        }else {
            $contacts = Contact::orderBy('id', 'desc')->paginate(4);
        }

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'min:5'],
            'company' => ['required'],
            'email' => ['required', 'email'],
        ];

        $this->validate($request, $rules);

        Contact::create($request->all());

        return redirect('contacts')->with('message', 'Contact Saved!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
