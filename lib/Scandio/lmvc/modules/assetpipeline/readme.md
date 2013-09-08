# Asset pipeline module (lmvc-modules)

This module intends to be a simple and small asset pipeline which integrates easily with the [LMVC-framework](https://github.com/scandio/lmvc).

## A convention over configuration pipeline

The asset pipeline currently supports CSS, Sass, Less, Javascript, CoffeeScript, Images and Markdown files through its directives in */assetpipeline/(css|sass|less|js|coffee|img|markdown)*.

Each pipe can serve multiple files which will be concatenated into one e.g. `/asssetpipeline/css/general.css/menu.css`. In addition a min-option can be passed as in `/assetpipeline/css/min...` to minify the concatenated file. This option currently works for every pipe and more options might be added later on.

## Configuration

It is easy to configure and barely needs any setup. It has a simple default configuration which already makes the pipeline usable.

### Defaults

```js
{
    "useFolders": true,
    "stage": "dev",
    "cacheDirectory": "_cache",
    "assetDirectories": {
        "js": {
            "main": "javascripts",
            "fallbacks": []
        },
        "coffee": {
            "main": "coffeescript",
            "fallbacks": []
        },
        "less": {
            "main": "styles",
            "fallbacks": []
        },
        "sass": {
            "main": "styles",
            "fallbacks": []
        },
        "scss": {
            "main": "styles",
            "fallbacks": []
        },
        "css": {
            "main": "styles",
            "fallbacks": []
        },
        "img": {
            "main": "img",
            "fallbacks": []
        },
        "font": {
            "main": "fonts",
            "fallbacks": []
        },
        "markdown": {
            "main": "markdown",
            "fallbacks": []
        }
    },
    "mimeTypes": {
        "jpg": "image/jpg",
        "png": "image/png",
        "gif": "image/gif",
        "eot": "application/vnd.ms-fontobject",
        "ttf": "application/octet-stream",
        "svg": "image/svg+xml",
        "woff": "application/x-woff"
    }
}
```

The `rootDirectory` will be handled relatively from your app's root and can e.g. be set to be `/assets`. The `cacheDirectory` will be used to chache concatinated and/or minified sources in each's pipes (js, less, sass and css) subdirectory. The _*cache directory will not be created* and needs to exist prior to using the pipeline and should have a 0777 chmod. The [LMVC-afresh bootstrap.sh](https://github.com/scandio/lmvc-afresh/blob/master/bootstrap.sh) will take care or that.

### Assets in sub directories

Assets can also reside in sub directories. Contrary to other pipelines the root directory *will not be recursively traversed* for the asset by file name. The path needs to be fully qualifying. Asset names therefore do not need to be unique in order to be found appropriately.

Requesting an asset as in ´assetpipeline/coffee/backbone/models/user.coffee` will request the asset from the sub directory `backbone/models`. Therefore, whenever multiple assets are requested they all need to be contained in the same directory.

Lastly, *all assets within a directory* can be requested at once by just leaving the file name as in ´assetpipeline/coffee/min/backbone/models` will concatenate and minify all CoffeeScript files under models. Which is handy for requesting application state dependent assets as in ´assetpipeline/js/min/admin` without always needing to update the url whenever an asset gets added or removed.

### Asset directory fallbacks

Each `assetDirectory` (pipe) can have multiple fallbacks as in

```js
"assetpipeline" : {
   "assetDirectories": {
      "js": {
         "fallbacks": ["../bower", "../composer"]
      } ,
      "coffee": {
         "fallbacks": ["../bower", "../composer"]
      }
   }
}
```

which will be used whenever a asset is not found in its main directory. Nevertheless, be aware the search will be performed recursively and can return files from undesired locations which is why multiple fallbacks can be registered which should be set in order of preference.
After all, the found file will be be cached (`cacheDirectory`) and the search will only be carried out once.
Which is different to files found in the `main-directory`. These will be checked if they have been changed (depending on the $stage-option) and be recached accordingly.

Obvisouly, your the assetpipeline directive of your application's `config.json` will be merged into the default configuration and overwrite its values where possible.

### Staging and caching

Putting the $stage in to 'prod' will force caching for production and asset minification whenever possible. Meaning that a requested asset will only be generated and cached once even if one of the requested files changes. Only deleting the cached file will force recompilation.

Leaving the $stage in any other mode will respectively lead to the pipe to compile the asset if any of the requested files changed.
E.g. if one requests `http://localhost/LMVC/lmvc-base/assetpipeline/js/jquery-1.9.1.js/my.plugin.js` and changes `my.plugin.js` the returned concatenated file will automatically be regenerated on the next request.

## Integration with LMVC

Its easy to integrate with the asset pipeline through lmvc. Assuming that you have the pipeline as a module just type

```php
use Scandio\lmvc\modules\assetpipeline\view\Asset;

<?= UI::js(Asset::assets(['jquery.min.js'])) ?>
<?= UI::js(Asset::assets(['config.js', 'main.js'], ['min'])) ?>
```

will return a url which will request the concatenated and minifed assets.

Its even easier with the UI-Snippetslibrary enabled which reduces the overhead of manually wrapping the url with a link- or script-tag.
Therefore typing

```php
<?= UI::css(Asset::assets(['bootstrap-responsive.css'], [])) ?>
```

will print a link-tag which requests the sources with the options from the server.

Images work almost as easy:

```php
use Scandio\lmvc\modules\htmltag\Html;

Html::img(['src' =>
   <?=
      Asset::image(['uploads', 'image.png'], ['w' => 800, 'h' => 600])
   ?>
, null)
```

## Additional tricks

The following trick only works if the `useFolders`-flag is set to false. Otherwise the pipeline will assume that the hash/version-number is indeed a folder and fail locating the file.

Passing a hash in the url will force the cache to generate a uniquely cached file e.g. requesting Javascript by `/assetpipeline/js/min/6d1b5e3/jquery.js/bootstrap.js/main.js` will concat and minify all source and prepend the hash to the cached filename.
After all, this trick mitigates some browser's rather eager caching mechanisms.

Visioning between assets is hereby also fairly easy. For example by never clearly the cache-directory one can easily switch between versions by `/assetpipeline/js/min/v1/jquery.js/bootstrap.js/main.js` or `/assetpipeline/js/min/v2/jquery.js/bootstrap.js/main.js`.

The `mimeType`-directive of the `config.json` solves a very specific problem. Whenever you request e.g. an image-resource from a css-file which is requested through the *css-pipe* via `../../img/image.png` the url gets out of the asset pipeline's scope as in `localhost/img` without the `asset-pipeline`. Having defaulted mime-types helps solving this.
Firstly, assets with a defaulted mime-type won't be minified by other pipes (css on img e.g.) which could break their binary contents. Moreover, by setting the *image-path* and *font-path* as an fallback to the css-pipe you can easily require these files just by their name. After all, every pipe can decide how to handle defaulted mime-types or files with an extension which is not part of its favored types.

## Contributing

Writing your own asset pipe is fairly easy. Just checkout the [contributing.md](https://github.com/scandio/lmvc-modules/blob/master/lib/Scandio/lmvc/modules/assetpipeline/contributing.md).

**Thanks for reading!**