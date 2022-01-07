<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostalCodeResource;
use App\Models\PostalCode;

class PostalCodeController extends Controller
{
    public function index()
    {
        return PostalCodeResource::collection(PostalCode::orderBy("postcode", "asc")->get());
    }
}
