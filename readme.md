## Suncore.lv SMS Unlock Laravel 4 package

#### Installing

* Add suncorelv/sms-unlock package to composer.json file as follows:

```
"require": {
    "laravel/framework": "4.2.*"
    ...
    "suncorelv/sms-unlock" : "1.0"
},
```

* Do composer depencies update

```
composer update
```

* Add service provider to app/config.php file

```
'providers' => array(
    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    ...
    'Suncorelv\SmsUnlock\SmsUnlockServiceProvider',
)
```

#### Publish package configuration files

```
php artisan config:publish suncorelv/sms-unlock
```

#### Don't forget to configure package
```
app/config/packages/suncorelv/sms-unlock - there should be tow files (client.php and debug.php)
```

### Enjoy