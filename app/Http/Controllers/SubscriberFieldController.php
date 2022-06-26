<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\SubscriberField;
use App\Http\Resources\FieldCollection;
use Illuminate\Http\Request;

class SubscriberFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Subscriber $subscriber)
    {
         return new FieldCollection($subscriber->fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Subscriber $subscriber, Request $request)
    {
        $request->validate([
            'field_ids' => 'required',
        ]);

        $subscriber->fields()->sync($request->field_ids);

        return response()->json([
            'message' => 'Subscriber fields updated successfully',
            'data' => new FieldCollection($subscriber->fields)
        ]);
    }
}
