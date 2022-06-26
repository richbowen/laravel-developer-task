<?php

namespace App\Http\Controllers;

use App\Enums\FieldType;
use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'field_types' => array_column(FieldType::cases(), 'value')
        ];
    }
}
