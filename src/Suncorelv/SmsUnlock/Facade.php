<?php namespace Suncorelv\SmsUnlock;

class Facade extends \Illuminate\Support\Facades\Facade {

    public static function getFacadeAccessor()
    {
        return 'scr';
    }

}