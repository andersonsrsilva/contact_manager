<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    private $limit = 5;
    private $upload_dir = 'public/uploads';
    private $rules = [
        'name'    => ['required', 'min:5'],
        'company' => ['required'],
        'email'   => ['required', 'email'],
        'photo'   => ['mimes:jpg,jpeg,png,gif,bmp'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->upload_dir = base_path() . '/' . $this->upload_dir;
    }

    public function index(Request $request)
    {
        \DB::enableQueryLog();

        listGroups($request->user()->id);

        dd(\DB::getQueryLog());

        $contacts = Contact::where(function ($query) use ($request) {
            $query->where("user_id", $request->user()->id);

            if ($group_id = ($request->get('group_id'))) {
                $query->where('group_id', $group_id);
            }

            if (($term = $request->get("term"))) {
                $keywords = '%' . $term . '%';
                $query->orWhere("name", 'LIKE', $keywords);
                $query->orWhere("company", 'LIKE', $keywords);
                $query->orWhere("email", 'LIKE', $keywords);
            }
        })
            ->orderBy('id', 'desc')
            ->paginate($this->limit);

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $data = $this->getRequest($request);

        Contact::create($data);

        return redirect('contacts')->with('message', 'Contact Saved!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $contact = Contact::find($id);

        return view('contacts.edit', compact('contact'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, $this->rules);

        $data = $this->getRequest($request);
        $contact = Contact::find($id);

        $oldPhoto = $contact->photo;

        $contact->update($data);

        if ($oldPhoto !== $contact->photo) {
            $this->removePhoto($oldPhoto);
        }

        return redirect('contacts')->with('message', 'Contact Updated!');
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        $this->removePhoto($contact->photo);

        return redirect('contacts')->with('message', 'Contact Deleted!');
    }

    public function autocomplete(Request $request)
    {
        if ($request->ajax()) {
            return Contact::select(['id', 'name as value'])->where(function ($query) use ($request) {
                if (($term = $request->get("term"))) {
                    $keywords = '%' . $term . '%';
                    $query->orWhere("name", 'LIKE', $keywords);
                    $query->orWhere("company", 'LIKE', $keywords);
                    $query->orWhere("email", 'LIKE', $keywords);
                }
            })
                ->orderBy('name', 'asc')
                ->take(5)
                ->get();
        }
    }

    private function getRequest($request)
    {
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $fillName = $photo->getClientOriginalName();
            $destination = $this->upload_dir;
            $photo->move($destination, $fillName);
            $data['photo'] = $fillName;
        }

        return $data;
    }

    private function removePhoto($photo)
    {
        if (!empty($photo)) {
            $file_path = $this->upload_dir . '/' . $photo;

            if (file_exists($file_path)) unlink($file_path);
        }
    }
}
