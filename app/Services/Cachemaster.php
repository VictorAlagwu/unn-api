<?php
/**
 * Created by shalvah
 * Date: 17/02/2018
 * Time: 22:38
 */

namespace App\Services;


use Illuminate\Support\Facades\Cache;

class Cachemaster
{

    public function saveForStudent($loginDetails, $data)
    {
        list($username, $password) = $loginDetails;
        $key = "$username.$password";
        Cache::put($key, $data, env('CACHE_TIMEOUT_MINUTES'));
    }

    public function getForStudent($username, $password)
    {
        $key = "$username.$password";
        return Cache::get($key);
    }
}
