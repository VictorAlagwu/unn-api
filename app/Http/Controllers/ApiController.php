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

        if (!$scraper->login(...array_values($request->only("username", "password")))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed. Please check your credentials and try again.'], 400);
        }

        $response = [
            "status" => "success",
            'data' => $scraper->extractDetails()
        ];

        return response()->json($response);
    }

}
