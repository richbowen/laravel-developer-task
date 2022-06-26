<?php

namespace App\Http\Controllers;

use App\Enums\SubscriberState;
use Illuminate\Http\Request;

class SubscriberStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'subscriber_states' => array_column(SubscriberState::cases(), 'value')
        ];
    }
}
