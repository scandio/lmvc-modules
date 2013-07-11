# Registration module (lmvc-modules)

This module allows a simple user registration within an application using the [LMVC-framework](https://github.com/scandio/lmvc).

## Installation

First import the table-structure needed into your MySQL-Database. The current dump (which is also used by the security module) can be found [here](https://github.com/scandio/lmvc-modules/tree/master/lib/Scandio/lmvc/modules/security/docs/DatabasePrincipal.sql).

Next setup your app's config.json to specify the intended registration mediator (in case the module gets extended and also offers LDAP and other mediators) as in:

```js
"modules": [
   "Scandio\\lmvc\\modules\\registration"
],
"registration": {
   "mediator": "\\Scandio\\lmvc\\modules\\registration\\handlers\\DatabaseMediator"
```

Your almost set!

## Running registrations

The registration module is accessible via `/app-root/registration/register` and `…/signup`, `…/success` and `…/failure`.
Just change or replace the templates if needed. The data structure should nevertheless remain the same.

*Thanks for reading*