Acquia SDK module
=================

[![Build Status](https://travis-ci.org/webbj74/acquia_sdk.png?branch=7.x-1.x)](https://travis-ci.org/webbj74/acquia_sdk) [![Coverage Status](https://coveralls.io/repos/webbj74/acquia_sdk/badge.png?branch=7.x-1.x)](https://coveralls.io/r/webbj74/acquia_sdk?branch=7.x-1.x)

This is an in-development Drupal module based on
[Acqia PHP SDK](https://github.com/cpliakas/acquia-sdk-php) and adapted
for use with PHP 5.2 for compatibility with older systems. As of Dec 2013
the focus of this module is on providing Drupal with an interface to
[Acquia's Cloud API service](http://cloudapi.acquia.com).

Adapting code for PHP 5.2
-------------------------
In order to make the acquia-sdk-php code backward compatible with PHP 5.2 
there are a few changes which need to be made to the object-oriented notation.

1. Removal of keywords {namespace} and {use}
   {namespace} and {use} are not available in PHP 5.2, and have been removed.
   In order to preserve PSR-0 compatibility, all classes have been prefixed
   with a convention based on the namespace. For example the class
   \Acquia\Cloud\Api\CloudApiClient was renamed to Acquia_Cloud_Api_CloudApiClient
1. Removal of code dependant on Late Static Binding
   So far this has concerned the use of {new static()} within the factory
   methods of some classes. To work around this for PHP 5.2 the factory
   methods were altered to accept a classname. This means that code
   extending these classes need to override the factory method to inject
   their own class name.

