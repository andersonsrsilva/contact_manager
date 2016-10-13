<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests;
use Illuminate\Http\Request;

class Groups extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups'
        ]);

        Group::create($request->all());

        //return redirect('contacts')->with('message', 'Contact Saved!');
    }

}
