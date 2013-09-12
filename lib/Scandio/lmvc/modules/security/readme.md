# Security module (lmvc-modules)

This protects specific controllers within an application of the [LMVC-framework](https://github.com/scandio/lmvc).

Authentification can be accomplished through various currently available principals such as one just reading a `config.json` another one conntecting to an LDAP-Service and lastly one reading from a database (also integrates with the registration module).

## Installation

If you want to use the database principal, first import the table-structure needed into your MySQL-Database. The current dump (which is also used by the registration module) can be found [here](docs/DatabasePrincipal.sql).

Next setup your app's config.json to specify the intended registration mediator (in case the module gets extended and also offers LDAP and other mediators) as in:

```js
"modules": [
   "Scandio\\lmvc\\modules\\security"
],
"security": {
   "principal": "\\Scandio\\lmvc\\modules\\security\\handlers\\database\\DatabasePrincipal",
```

Other principals are `"\\Scandio\\lmvc\\modules\\security\\handlers\\database\\LdapPrincipal"` and `"\\Scandio\\lmvc\\modules\\security\\handlers\\database\\JsonPrincipal"`.

You're almost set!

## Protecting controllers

The security module offers two controllers which can be extended.

The first is the `Scandio\lmvc\modules\security\SecureController` which check on `preProcess()` if the user is authenticated and has the right role to access your controller's action. Your extending controller can have a `$controllerRole` which will be used (if set) to check if the user has that specific role if not he will be redirected to a *forbidden-page*.

The other one is `Scandio\lmvc\modules\security\AnonymousController` which should be used in a secure context whenever a controller is available to anyone but the current user's information should be accessible in templates.

## Redirecting statically

Even though requesting a protected controller and any of its actions will automatically redirect you to login you might want to include static links to the login site.

The registration module is accessible via `/app-root/security/login`.
Just change or replace the templates if needed. The data structure should nevertheless remain the same.

*Thanks for reading*