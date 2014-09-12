## Suncore.lv SMS Unlock Laravel 4 package

#### Dependencies

* CURL PHP extension
* Laravel PHP framework 4.1+

#### Installing

* Add suncorelv/sms-unlock package to composer.json file as follows:

```
"require": {
	"laravel/framework": "4.2.*"
	...
	"suncorelv/sms-unlock" : "1.1"
},
```

* Do composer dependencies update

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

* Also don't forget to add alias to app/config.php file

```
'aliases' => array(
	...
	'View'            => 'Illuminate\Support\Facades\View',
	'SCR'			  => 'Suncorelv\SmsUnlock\Facade'
),
```

#### Publish package configuration files

```
php artisan config:publish suncorelv/sms-unlock
```

#### Don't forget to configure package
```
app/config/packages/suncorelv/sms-unlock - there should be two files (client.php and debug.php)
```

Enjoy