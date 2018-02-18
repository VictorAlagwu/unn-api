<?php

namespace App\Http\Controllers;

use App\Services\Cachemaster;
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

    public function getStudentDetails(Request $request, UnnPortalScraper $scraper, Cachemaster $cachemaster)
    {
        $this->validate($request, [
            'username' => 'string|required',
            'password' => 'string|required'
        ]);

        list($username, $password) = array_values($request->only("username", "password"));

        // first try to pull from cache (except the requeter said NO)
        if (!$request->isNoCache() && $details = $cachemaster->getForStudent($username, $password)) {
            return response()->json(['status' => 'success', 'data' => $details]);
        }

        // nothing in cache? Heimdall, open the portal!!!
        if (!$scraper->login($username, $password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed. Please check your credentials and try again.'
            ], 400);
        }

        $response = [
            "status" => "success",
            'data' => $scraper->extractDetails()
        ];
        return response()->json($response);
    }

}
