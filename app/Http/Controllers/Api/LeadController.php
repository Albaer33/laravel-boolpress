<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lead;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewContactMail;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function store(Request $request) {
        $data = $request->all();

        // validazioni per il form
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            // Errori di validazione
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        //salva la nuova lead
        $new_lead = new Lead();
        $new_lead->fill($data);
        $new_lead->save();

        // invia l' email
        Mail::to('customerservice@boolpress.it')->send(new NewContactMail($new_lead));
    }
}
