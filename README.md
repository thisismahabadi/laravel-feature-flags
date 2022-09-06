# Laravel feature flags

This package helps you implement feature flags in your Laravel application without a 3rd party service.

## Installation

```bash
composer require thisismahabadi/laravel-feature-flags
```

## How does it work?

So it's simply using two database tables and middleware to check if the user has access to the requested route or not.

Tables are:

**feature_flags** (to store the feature itself including its name and the date which determines the availability date.)

**feature_flag_permissions** (that stores which users [or whatever you specify] have access to what features)

After having the information in the database, all we need to do is to assign middleware to our routes, like this:

`Route::middleware('feature_flag:whatsapp')->post('api/v1/whatsapp', WhatsAppController@sendMessage);`

The feature flag middleware firstly checks the **feature_flags** table if that feature is valid and if so, it'll then check **feature_flag_permissions** table to see if the user which is requesting has a correlated record in the database. 