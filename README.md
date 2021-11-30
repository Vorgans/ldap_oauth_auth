# Ldap_Oauth_Auth


## Ldap Oauth Auth (Open Authentication SYStem for Ldap and Oauth)
This extension provides the LDAP or OAUTH login and authentication method.


## Installation

Package installation is handled by Composer.

* If you haven't already, please [install Composer](http://getcomposer.org/doc/00-intro.md#installation-nix)

* Run `composer require vorgans/ldap_oauth_auth` in the root of your project

* In next step, run `php artisan LdapOauth:install`

* Now, you can setup connect_settings.php in the config folder

## Feedback and Contributions

* Feedback is welcome in the form of pull requests and/or issues.
* Contributions should generally follow the strategy outlined in ["Contributing
  to a project"](https://help.github.com/articles/fork-a-repo#contributing-to-a-project)
* Please submit pull requests against the `develop` branch

## Credits

* This code allows you to easily define an ldap or oauth connection. It partly uses the league / oauth2-client package for the oauth connection.

