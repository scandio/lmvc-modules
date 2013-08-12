# Upload module (lmvc-modules)

This module intends handling uploads of multiple filetype within an application of the [LMVC-framework](https://github.com/scandio/lmvc).

Its controller should have one method per filetype which is currently only implements ::img().

## Installation

Just setup your app's config.json to add the upload module to the modules-array:

```js
"modules": [
   "Scandio\\lmvc\\modules\\upload"
]
```

You're almost set!

## Configuration

Configure the Upload-modules bootstrap from your project's root bootstrap as in:

```php
upload\Bootstrap::configure([
    'root'              => static::getPath(),
    'uploadDirectory'   => 'img/uploads'
]);
```

Remember to chmod the uploads folder to 777.

## Uploading files

The Upload-controllers::img-function looks into the `$_FILES[]` of php reads and moves the tempfile to your specified path.
Therefore, just firing a request to the `/Upload/img` with an image in the `$_FILES[]` will upload the file.

It is also possible to specify a custom filename by calling `/Upload/img/filename.jpg` which will not use the filename from the `$_FILES[]`. As an addition specifying `"sha1"` as the filename will hash the file and store the file under the its hash.

The return value of every file is the path to the file from the app's root as json in `{path: "path/to/, filename: "file.jpg"}`.

**Thanks for reading**