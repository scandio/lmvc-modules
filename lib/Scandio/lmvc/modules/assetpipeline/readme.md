# Assetpipeline Modul (lmvc-modules)

This module intends to be a simple and small assetpipeline which integrates easily with the [LMVC-framework](https://github.com/scandio/lmvc).

## An convention of configuration pipeline

The assetpipeline currently supports CSS, Sass, Less and Javascript files through its directives in */assetpipeline/(css|sass|less|js)*.

Each pipe can serve multiple files which will be concatinated into one e.g. */asssetpipeline/css/general.css/menu.css*. In addition a min-option can be passed as in */assetpipeline/css/min...* to minify the concatinated file. This option currently works for every pipe and more options might be added later on.

## Configuration

It is easy to configure and bearly needs any setup. It has a simple default configuration which already makes the pipeline usable.

### Defaults

```php
   $defaults = [
      'stage' => 'dev',
      'assetRootDirectory' => '',
      'cacheDirectory' => '_cache',
      'assetDirectories' => [
         'js'    => [
            'main'  => 'javascripts'
         ],
         'less'  => [
            'main'  =>  'styles'
         ],
         'sass'  => [
            'main'  =>  'styles'
         ],
         'css'   => [
            'main'  =>  'styles'
         ]
      ]
   ];
```

The $rootDirectory will be handeled realativly from your app's root and can e.g. be set to be /assets. The $cacheDirectory will be used to chache concatinated and/or minified sources in each's pipes (js, less, sass and css) subdirectory. The _*cache directory will not be created* and needs to exist prior to using the pipeline.

### Asset directory fallbacks

Each $assetDirectory can have multiple fallbacks as in

```php
   'js'    => [
      'main'      => 'javascripts',
      'fallbacks' => ['vendor/twitter' ,'vendor/']
   ],
   'less'  => [
      'main'  =>  'styles'
   ]
```

which will be used whenever a asset is not found in its main directory. Nevertheless, be aware the search will be performed recursivly and can return files from undisired locations which is why multiple fallbacks can be registered which should be set in order of preference.
After all, the found file will be be cached ($cacheDirectory) and the search will only be carried out once.
Which is different to files found in the $main-Directory. These will be checked if they have been changed (depending on the $stage-option) and be recached accordingly.

### Staging and caching

Putting the $stage in to 'prod' will force caching for production. Meaning that a requested asset will only be generated and cached once even if one of the requested files changes. Only deleting the cached file will force recompilation.

Leaving the $stage in any other mode will respectivly lead to the pipe to compile the asset if any of the requested files changed.
E.g. if one requests *http://localhost/LMVC/lmvc-base/assetpipeline/js/jquery-1.9.1.js/my.plugin.js* and changes *my.plugin.js* the returned concatintated file will automatically be regenerated on the next request.

## Integration with LMVC

Its easy to integrate with the assetpipeline through lmvc. Assuming that you have the pipline as a module just type

```php
   $app->assets(['jquery-1.9.1.js', 'bootstrap.js'], ['min'])
```

will return a url which will request the concatinated and minifed assets.

Its even easier with the UI-Snippetslibrary enabled which reduces the overhead of manually wrapping the url with a link- or script-tag.
Therefore typing

```php
   UI::css($app->assets(['bootstrap.css', 'bootstrap-responsive.css'], ['min']))
```

will print a link-tag which requests the sources with the options from the server.

## Additional tricks

Passing a hash in the url will force the cache to generate a uniquely cached file e.g. requesting Javascript by */assetpipeline/js/min/6d1b5e3/jquery.js/bootstrap.js/main.js* will concat and minify all source and prepend the hash to the cached filename.
After all, this trick mitigates some browser's rather eager caching mechanisms

Versioning between assets is hereby also fairly easy. For example by never clearly the cache-directory one can easily switch between versions by */assetpipeline/js/min/v1/jquery.js/bootstrap.js/main.js* or */assetpipeline/js/min/v2/jquery.js/bootstrap.js/main.js*.

**Thanks for reading!**
