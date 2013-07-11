# Html-tag module (lmvc-modules)

This module generates Html-tags within any part of an application using the [LMVC-framework](https://github.com/scandio/lmvc).

## Installation

No installation required just add `lmvc-modules` to your `composer.json` and use the namespace `Scandio\lmvc\modules\htmltag`.

## Examples upfront

### The following in php

```php
<?php
   /* Simple image-tag */
   Html::img([
      'class' => 'aside border',
      'src'   => 'images/image.png'
   ]);

   /* Nest the calls */
   Html::div(
      ['class' => 'wrap-it'],
      Html::p(null, '... and nest it')
   );

   /* Pass in content arrays which unfold into a tag each */
   Html::ul(['class' => 'wrap-it'],
      Html::li(null, ['item-1', 'item-2', 'item-3'])
   );
?>
```

### Generates the following Html

```html
<img class='aside border' src='images/image.png' />

<div class='wrap-it'>
   <p>... and nest it</p>
</div>

<ul class='wrap-it'>
   <li>item-1</li>
   <li>item-1</li>
   <li>item-1</li>
</ul>
```

## Hook into the string building

### By extending the Html.php class

The class can be extended for defining hooks (multiple per tag-name). The convention is fairly easy.
Whenever a private|public|protected static method with the name `pre<Tag>` and `post<Tag>` is found,
the function will be called before and|or after the internal function have done its work.
The arguments for the preHooks are $tag, $attr = [] and $content = false for the postHook you
will just get the string.

**Important:**
The return of any on the class defined preHook-function must be an enumerated array as passed in so that it can be passed to the post-hooks. Every post-hook function on the other hand should return a string and gets passed a string. Otherwise you break all the things!

```php
<?php
   public static function preImg($tagName, $attr, $content) {
      return [$tagName, $attr, $content];
   }

   public static function postImg($html) {
      return $html;
   }
?>
```

### By adding a hook with a callback

Adding a hook can also be done without extending the class.
Just call `::addPre($tagName, function)` or `::addPost($tagName, function)`.
As a side not, hooks defined on the base are also being called on the extended class due to their protected nature.

**Important:**
Hooks defined as member functions as described above are called before hooks added in functional manner.

```php
<?php
   Html::addPre('img', function($tagName, $attr, $content) {
      return [$tagName, $attr, $content];
   });

   Html::addPost('img', function($html) {
      return $html;
   });
?>
```

*Thanks for reading!*