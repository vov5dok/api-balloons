<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class DeveloperController extends Controller
{

    public function generateUuid()
    {
        dump(Str::uuid());
    }


}
