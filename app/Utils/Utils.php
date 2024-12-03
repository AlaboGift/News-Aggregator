<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cache;

class Utils
{  
    public static function getIp(): string
    {
        $ipAddress = '';
    
        switch (true) {
            case !empty($_SERVER['HTTP_CLIENT_IP']):
                $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
                break;
            
            case !empty($_SERVER['HTTP_X_FORWARDED_FOR']):
                $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($ipAddressList as $ip) {
                    if (!empty($ip)) {
                        $ipAddress = $ip;
                        break;
                    }
                }
                break;
            
            case !empty($_SERVER['HTTP_X_FORWARDED']):
                $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
                break;
            
            case !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']):
                $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
                break;
            
            case !empty($_SERVER['HTTP_FORWARDED_FOR']):
                $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
                break;
            
            case !empty($_SERVER['HTTP_FORWARDED']):
                $ipAddress = $_SERVER['HTTP_FORWARDED'];
                break;
            
            case !empty($_SERVER['REMOTE_ADDR']):
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                break;
        }
        
        return $ipAddress;
    }

    public static function updateUserPreference($keyword): bool
    {
        $ip = self::getIp();
        
        // Fetch existing preferences
        $preferences = self::userPreferences();
    
        // Add the new keyword if it's not already in the preferences array
        if (!in_array($keyword, $preferences)) {
            $preferences[] = $keyword;
        }
    
        // Store the updated preferences in the cache
        return Cache::set($ip, json_encode($preferences));
    }
    
    public static function userPreferences(): array
    {
        $ip = self::getIp();
        
        // Retrieve the cached preferences
        $cachedPreferences = Cache::get($ip);
        
        // Return the decoded preferences or an empty array if none exist
        return json_decode($cachedPreferences, true) ?? [];
    }


}
