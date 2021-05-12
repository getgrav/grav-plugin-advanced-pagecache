<?php

namespace Grav\Plugin;

use Grav\Common\Config\Config;
use Grav\Common\Plugin;
use Grav\Common\Uri;

/**
 * Class AdvancedPageCachePlugin
 * @package Grav\Plugin
 */
class AdvancedPageCachePlugin extends Plugin
{
    /** @var Config $config */
    protected $config;
    protected $pagecache_key;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 1000]
        ];
    }

    /**
     * Return `true` if the page has no extension, or has the default page extension.
     * Return `false` if for example is a RSS version of the page
     */
    private function isValidExtension() : bool
    {
        /** @var Uri $uri */
        $uri = $this->grav['uri'];

        $extension = $uri->extension();
        if (!$extension) {
            return true;
        }

        $disabled_extensions = (array)$this->grav['config']->get('plugins.advanced-pagecache.disabled_extensions');

        return !in_array($extension, $disabled_extensions, true);
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized(): void
    {
        /** @var Uri $uri */
        $uri = $this->grav['uri'];

        $config = (array)$this->grav['config']->get('plugins.advanced-pagecache');

        $full_route = $uri->uri();
        $route = str_replace($uri->baseIncludingLanguage(), '', $full_route);
        $params = $uri->params(null, true);
        $query = $uri->query(null, true);
        $user = $this->grav['user'] ?? null;
        $lang = $this->grav['language']->getLanguageURLPrefix();

        // Definitely don't run in admin plugin or is not a valid extension
        if ($this->isAdmin() || !$this->isValidExtension()) {
            return;
        }

        // If this url is not whitelisted try some other tests
        if (!in_array($route, (array)$config['whitelist'], true)) {
            // do not run in these scenarios
            if (
                $config['disabled_with_params'] && !empty($params) ||
                $config['disabled_with_query'] && !empty($query) ||
                $config['disabled_on_login'] && ($user && $user["authenticated"]) ||
                in_array($route, (array)$config['blacklist'], true)
            ) {
                return;
            }
        }

        if ($config['per_user_caching']) {
            $this->pagecache_key = md5('adv-pc-' . $lang . $full_route . $user["username"]);
        } else {
            $this->pagecache_key = md5('adv-pc-' . $lang . $full_route);
        }

        // Should run and store page
        $this->enable([
            'onOutputGenerated' => ['onOutputGenerated', -1000]
        ]);

        $pagecache = $this->grav['cache']->fetch($this->pagecache_key);
        if ($pagecache) {
            echo $pagecache;
            exit;
        }
    }

    /**
     * Save the page to the cache
     */
    public function onOutputGenerated(): void
    {
        $this->grav['cache']->save($this->pagecache_key, (string)$this->grav->output);
    }
}
