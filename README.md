# lmvc-modules

LMVC-Modules are easy-to-use extensions to the 'scandio/lmvc'-framework

## Form module

Easily create validator classes extending the *AbstractForm* base class while defining validator-functions and error-messages.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/form)

## Mustache module

Compiles mustache templates and provides a simple integration into LMVC's views.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/mustache)

## Security module

Protects resources provided by controllers using custom security principals (e.g. Ldap or Json) as gateways.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/security)

## Snippets module

Allows easy snippets integration in views for e.g. rendering of prepared Html-components such as tables, checkboxes, etc. Moreover, directories can be registered to load more custom snippets from various sources.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/snippets)

## Asset pipeline module

Facilitates asset requesting, concatenation and minification of Javascript, CSS, Sass and Less sources. In addition, easily integrates into lmvc and its views.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/assetpipeline)

## Registration module

Allows for user registration in the application. Currently uses a database but could easily be extended to persist registrations in LDAP or maybe even config.json.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/registration)

## Html-tag module

Module simplifying Html-tag generation in views. Abstracts string handling and templating while being a more tailored snippet component.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/htmltag)

## Upload module

Module handling uploads of various types. Currently only supports image uploads but will hopefully contain a bigger set of types in the near future.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/upload)

## Session module

Module abtracting from php's session handling. It allows getting, setting, merging and replacing its values without actually touching the $_SESSION variable.

[Readme](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/session)