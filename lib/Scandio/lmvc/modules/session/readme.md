# Session module (lmvc-modules)

This module intends abstracting from interaction with php's session ($_SESSION) within an application of the [LMVC-framework](https://github.com/scandio/lmvc).

It offers a little helper collection reading and writing to and from the session array.

## Installation

Just setup your app's config.json to add the upload module to the modules-array:

```js
"modules": [
   "Scandio\\lmvc\\modules\\session"
]
```

You're set!

## Usage

### Getting parameters

Parameters can be accessed via dot-notation as a parameters within the `::get()-method`.

```php
$userId = Session::get('user.id');

$userData = Session::get('user');
```

The last example shows that accessing a non-primitive value will just return the object at the current dot-notation's pointer position.

### Settings parameters

Parameters can be set in a similar fashion as getting them.

```php
Session::set('user.id', $user->id);

Session::set('authenticated', true)::set('user.id', $userId);

$userId = Session::set('authenticated', true)::set('user.id', $userId)::get('authenticated');
```

The first example shows setting a value while the second one outlines the ability to chain set-calls.
The last example finishes the setting process by finally getting a value which has been set previously.

### Merging values into the session-object

It is also possible to merge a whole array into the session where its values will only be set whenever it has not been set previously.

```php
Session::merge([
    'user' => [
        'id' => $userModel->id
    ],
    'authenticated' => Security::get()->isAuthenticated();
]);
```

Remember, whenever a value is found which is already set, e.g. the `authenticated = false` it will not be set to `true` even if `Security::get()->isAuthenticated()` returns true now.

### Replacing values in the session

The last problem can be solved by using the `::replace()-method`.

```php
Session::replace([
    'user' => [
        'id' => $userModel->id
    ],
    'authenticated' => Security::get()->isAuthenticated();
]);
```

The above example will replace every value (set it if not existend) in the session no matter what its previous value was.

### Some helper methods

```php
# Starts a session
Session::start();

# Checks if a value is set
Session::has('user.id');

# Flushes the session's data
Session::flush();

# Regenerates the session's id - optional params are $flush and $lifetime in seconds.
Session::regenerate();

# Finally closes the session.
Session::close();
```

**Thanks for reading**