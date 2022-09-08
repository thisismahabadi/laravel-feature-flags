# Laravel feature flags

This package helps you implement feature flags in your Laravel application without a 3rd party service.

## Installation

```bash
composer require thisismahabadi/laravel-feature-flags
```

## How does it work?

So it's simply using two database tables and trait `(src/Traits/FeatureFlag.php)` to check if the accessor (user, team, agency, etc.) has access to the requested resource or not.

Tables are:

**feature_flags** (to store the feature itself including its name and the date which determines the availability date.)

**feature_flag_permissions** (that stores which accessors [or whatever you specify] have access to what features)

After having the information in the database, all you need to do is to use the mentioned trait in any classes you want, for example, there is also a Middleware `(src/Http/Middleware/CheckFeatureFlagAccess.php)` which is using that trait, and you can also use it to secure your routes. To assign middleware to routes, do something like this:

`Route::middleware('feature_flag:whatsapp')->post('api/v1/whatsapp', WhatsAppController@sendMessage);`

The logic of the feature flag trait is that firstly it checks the **feature_flags** table that if the feature is valid and if so, it'll then check the **feature_flag_permissions** table to see if the accessor which is requesting has a correlated record in the database.

## Configuration

If you execute Laravel's `vendor:publish` command, your file will be copied to the specified publish location, and then the configurations can be customized.

```bash
php artisan vendor:publish --tag=feature-flags-config
```

## Code requirements

If you want to use the User's id to check the permission all you need to do is to define a **belongsTo** relation in your **User** model as `featureAccessor()`.  

```bash
public function featureAccessor(): BelongsTo
{
    return $this->belongsTo(self::class, 'id', 'id');
}
```

Did you know you can also use another resource that you use in your company? Such as `team_id`, `agency_id`, `customer_id` or whatever you use.  

Example for using `agency_id`:

```bash
public function featureAccessor(): BelongsTo
{
    return $this->belongsTo(Agency::class, 'agency_id', 'id');
}
```
