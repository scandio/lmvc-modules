# Upload module (lmvc-modules)

This module intends handling uploads of multiple type within an application of the [LMVC-framework](https://github.com/scandio/lmvc).

Its controller should have one method per filetype which is currently only ::img().

## Installation

Just setup your app's config.json to add the module to the modules-array:

```js
"modules": [
   "Scandio\\lmvc\\modules\\upload"
]
```

You're almost set!