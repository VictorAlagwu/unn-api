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
        $key = $this->getCacheKey($username, $password);
        Cache::put($key, $data, env('CACHE_TIMEOUT_MINUTES', 60 * 4));
    }

    public function getForStudent($username, $password)
    {
        $key = $this->getCacheKey($username, $password);
        return Cache::get($key);
    }

    /**
     * Get the key for referencing this student's details in cache
     *
     * @param $username
     * @param $password
     * @return string
     */
    private function getCacheKey($username, $password)
    {
        return sha1("$username.$password");
    }
}
