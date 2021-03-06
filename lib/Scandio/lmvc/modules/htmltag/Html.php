<?php

namespace Scandio\lmvc\modules\htmltag;

/**
 * Class Html
 * @package Scandio\lmvc\modules\htmltag
 *
 * Class generating Html-element strings.
 * Can be used to build anything from ul, img and h1-tags.
 *
 * Examples:
 *      Html::img([
 *          'class' => 'aside border',
 *          'src'   => 'images/image.png'
 *      ]);
 *
 *      Html::div(
 *          ['class' => 'wrap-it'],
 *          Html::p(null, '... and nest it')
 *      );
 *
 *      Html::ul(['class' => 'wrap-it'],
 *          Html::li(null, ['item-1', 'item-2', 'item-3'])
 *      );
 *
 * Extending:
 *      The class can be extended for defining hooks (multiple per tag-name). The convention is fairly easy.
 *      Whenever a private|public|protected static method with the name pre<Tag> and post<Tag> is found,
 *      the function will be called before and|or after the internal function have done its work.
 *      The arguments for the preHooks are $tag, $attr = [] and $content = false for the postHook you
 *      will just get the string.
 *
 *      Important:
 *          The return of any on the class defined preHook-function must be an enumerated array as passed
 *          in so that it can be passed to the post-hooks.
 *          Every post-hook function on the other hand should return a string and gets passed a string.
 *          Otherwise you break all the things! (http://cdn.meme.li/instances/400x/39604835.jpg)
 *      Examples:
 *          public static function preImg($tagName, $attr, $content) {
 *              return [$tagName, $attr, $content];
 *          }
 *
 *          public static function postImg($html) {
 *              return $html;
 *          }
 *
 * Without extending:
 *      Adding a hook can also be done without extending the class.
 *      Just call ::addPre($tagName, function) or ::addPost($tagName, function).
 *      As a side not, hooks defined on the base are also being called on the extended class due to their
 *      protected nature.
 *
 *      Important:
 *          Hooks defined as member functions as described above are called before hooks added in functional manner.
 *      Examples:
 *          Html::addPre('img', function($tagName, $attr, $content) {
 *              return [$tagName, $attr, $content];
 *          });
 *
 *          Html::addPost('img', function($html) {
 *              return $html;
 *          });
 */
class Html {

    protected static
        $preHooks = [],
        $postHooks = [];

    /**
     * Function used to respond to any statically called function for generating a tag-name such as
     * Html::img() or Html::a. This function also calls all pre/post-hooks defined on extending classes
     * and added by add[Pre|Post]($tagName, function).
     *
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic($tagName, $arguments) {
        # Define pre/post-hook function names
        $ucFirstTagName         = ucfirst($tagName);
        $preHookFunctionName    = 'pre'  . $ucFirstTagName;
        $postHookFunctionName   = 'post' . $ucFirstTagName;
        $pipedResponse          = [
            $tagName,
            isset($arguments[0]) ? $arguments[0] : null,
            isset($arguments[1]) ? $arguments[1] : false,
        ];

        # Get hooks defined for tag-name currently being processed
        $preHooks   = isset(static::$preHooks[$tagName])    ?   static::$preHooks[$tagName]   : [];
        $postHooks  = isset(static::$postHooks[$tagName])   ?   static::$postHooks[$tagName]  : [];

        # Call the member pre-hook function is defined using late static binding
        if (method_exists(get_called_class(), $preHookFunctionName)) {
            $pipedResponse = forward_static_call_array('static::' . $preHookFunctionName, $pipedResponse);
        }

        # Work all the pre-hooks piping in the [$tagName, $attr, $content]
        foreach ($preHooks as $preHook) {
            $pipedResponse = $preHook($pipedResponse[0], $pipedResponse[1], $pipedResponse[2]);
        }

        # Time to generate the html-tag as a string
        $pipedResponse = Helper::tag($pipedResponse[0], $pipedResponse[1], $pipedResponse[2]);

        # Call the member post-hook function is defined using late static binding
        if (method_exists(get_called_class(), $postHookFunctionName)) {
            $pipedResponse = forward_static_call_array('static::' . $postHookFunctionName, [$pipedResponse]);
        }

        # Work all the post-hooks passing in the generated html-tag-string
        foreach ($postHooks as $postHook) {
            $pipedResponse = $postHook($pipedResponse);
        }

        return $pipedResponse;
    }

    /**
     * Adds a pre-hook for the tag-name which is called whenever the tag will be generating by
     * calling the passed in function.
     *
     * @param string $tagName
     * @param callable $hook
     */
    public static function addPre($tagName, $hook) {
        # Initiate array is no hook for it has ever been defined
        if (!isset(static::$preHooks[$tagName])) { static::$preHooks[$tagName] = []; }

        # Add the hook if it is a callable function
        if (is_callable($hook)) { static::$preHooks[$tagName][] = $hook; }
    }

    /**
     * Adds a post-hook for the tag-name which is called whenever the tag will be generating by
     * calling the passed in function.
     *
     * @param string $tagName
     * @param callable $hook
     */
    public static function addPost($tagName, $hook) {
        if (!isset(static::$postHooks[$tagName])) { static::$postHooks[$tagName] = []; }

        # Add the hook if it is a callable function
        if (is_callable($hook)) { static::$postHooks[$tagName][] = $hook; }
    }
}