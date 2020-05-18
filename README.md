# Grav AdvancedPageCache Plugin

`AdvancedPageCache` is a powerful static page cache type plugin that caches the entire page output to the Grav cache and reuses this when the URL path is requested again.  This can dramatically increase the performance of a Grav site.  Due to the static nature of this cache, if enabled, you will need to **manually** clear the cache if you make any page modifications.  This cache plugin (by default) will not cache pages that have URLs that contain either **querystring or grav-paramater** style values, you may want to disable this behavior.

This plugin can provide dramatic performance boosts and is an ideal solution for sites with many pages and predominantly static content.

| NOTE: Grav Debugger will not display when the page is cached

# Installation

Installing the AdvancedPageCache plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install advanced-pagecache

This will install the AdvancedPageCache plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/advanced-pagecache`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `advanced-pagecache`. You can find these files either on [GitHub](https://github.com/getgrav/grav-plugin-precache) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/advanced-pagecache

# Usage

The default configuration provided in the `user/plugins/advanced-pagecache.yaml` file contains sensible defaults:

```
enabled: true                       # set to false to disable this plugin completely
disabled_with_params: true          # disabled if there are params set on this URI (eg. /color:blue)
disabled_with_query: true           # disabled if there are query options set on this URI (eg. ?color=blue)
disabled_on_login: true             # disabled if a user is logged in on the frontend of the site
per_user_caching: false             # enable per-user caching of pages (if not disabled_on_login)
disabled_extensions: [rss,xml,json] # disabled for these extensions
whitelist:                          # set to array of enabled page paths to enable only when in whitelist
  - /cache-this-page
blacklist:                          # set to array and provide list of page paths to disable plugin for
  - /error
  - /login
  - /random
  - /dont-cache-this-page
```

If a **whitelist** array is provided, **only** pages specifically listed will be cached. Language prefixes are ignored, but URL extensions are taken into account.
If a **blacklist** array is provided, this plugin will cache all pages except those specifically listed. Language prefixes are ignored, but URL extensions are taken into account.

## Important Notes

This plugin is intended for **production** scenarios where optimal performance is desired and more important than convenience. `AdvancedPageCache` is not intended to be used in a development environment or a rapidly changing one.

Many plugin events will not fire when a cached page is found because these are not processed by Grav, only the static page is returned. For example, because there is no RenderEvent with the cached page, the DebugBar will not show even if enabled.
