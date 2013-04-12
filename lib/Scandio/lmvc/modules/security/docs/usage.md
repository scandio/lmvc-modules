# Usage

* Configure secure module in your config.json (see the example*.json-files)

## Extend your controller

```php
class MyController extends SecureController
{
    // optional
    protected $controllerRole = 'read';

    public static function index()
    {
        if (static::$currentUser->isInRole('edit')) {
            // act as you like
        } else {
            return static::forbidden();
        }
    }
}
```

or for anonymous Access:

```php
class MyController extends AnonymousController
{
    public static function index()
    {
        // everybody can use this
    }

    public static function onlyForMembers()
    {
        if (static::$currentUser->isInRole('edit')) {
            // act as you like
        } else {
            return static::forbidden();
        }
    }
}
```

## Use it in views

```php
<a href="<?= $app->url('Security::logout') ?>">Logout</a>

<p><?= $currentUser->username ?></p>
<p><php if ($currentUser->isInRole('read')) echo 'You\'re a reader!' ?></p>
```

## More

If you're not authenticated and you're requesting an uri the login dialog will be shown.
After a successful login the system redirects to to requested one.



