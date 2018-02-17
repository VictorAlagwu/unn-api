<?php

namespace App\Http\Controllers;

use App\Services\UnnPortalScraper;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //
    }

    public function getStudentDetails(Request $request, UnnPortalScraper $scraper)
    {
        $this->validate($request, [
            'username' => 'string|required',
            'password' => 'string|required'
        ]);

        if (!$scraper->login(...$request->only("username", "password"))) {
            return response()->json();
        }

        $response = [
            "status" => "success",
            'data' => $scraper->extractDetails()
        ];

        return response()->json($response);
    }

}
