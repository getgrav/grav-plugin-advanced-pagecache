# v3.0.3
## mm/dd/2021

1. [](#new)
    * Require **Grav 1.7.0**
    * Added support for HTTP code and headers
    * Added support for `304 Not Modified` responses
1. [](#improved)
    * Raise `onPluginsInitialized` event priority to make page loads faster
    * Lower `onOutputGenerated` event priority to allow other plugins to postprocess the content (and prefer `onOutputRendered` instead)

# v3.0.2
## 06/21/2020

1. [](#improved)
    * fix default value for per_user_caching in admin [#18](https://github.com/getgrav/grav-plugin-advanced-pagecache/pull/18)

# v3.0.1
## 06/21/2020

1. [](#bugfix)
    * Check for user when login plugin is not installed [#19](https://github.com/getgrav/grav-plugin-advanced-pagecache/issues/19)

# v3.0.0
## 06/08/2020

1. [](#new)
    * Allow disabling user on login [#17](https://github.com/getgrav/grav-plugin-advanced-pagecache/issues/17)
1. [](#improved)
    * Fixed conflicting and misleading messages [#16](https://github.com/getgrav/grav-plugin-advanced-pagecache/issues/16)

# v2.0.0
## 04/27/2019

1. [](#new)
    * Advanced page cache now works with language prefixes
1. [](#improved)
    * Switch to a disable toggles for `querystrings` and `params`. Default is now to **not cache** these.
    * Blacklist and Whitelist now work with extensions
    * Optimized code to be more efficient

# v1.2.0
## 07/14/2016

1. [](#bugfix)
    * Fix issue with URLs with extension being considered like the default page extension [#10](https://github.com/getgrav/grav-plugin-advanced-pagecache/issues/10)

# v1.1.1
## 09/02/2015

1. [](#improved)
    * Switched to `value_only` arrays for whitelist and blacklist

# v1.1.0
## 08/25/2015

1. [](#improved)
    * Added blueprints for Grav Admin plugin

# v1.0.0
## 06/16/2015

1. [](#new)
    * ChangeLog started...
