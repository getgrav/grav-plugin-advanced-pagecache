<?php
namespace Grav\Plugin;

use \Grav\Common\Plugin;
use \Grav\Common\Uri;

class AdvancedPageCachePlugin extends Plugin
{
    /** @var Config $config */
    protected $config;
    protected $path;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onOutputGenerated' => ['onOutputGenerated', 0]
        ];
    }

    /**
     * Return `true` if the page has no extension, or has the default page extension. 
     * Return `false` if for example is a RSS version of the page
     */
    private function isDefaultPageType() {
        /** @var Uri $uri */
        $uri = $this->grav['uri'];
        $extension = $uri->extension();
        
        if (!$extension) {
            return true;
        }
        
        if (('.' . $extension) == $this->grav['config']->get('system.pages.append_url_extension')) {
            return true;
        }
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        $config = $this->grav['config']->get('plugins.advanced-pagecache');

        /** @var Uri $uri */
        $uri = $this->grav['uri'];

        $params = $uri->params(null, true);
        $query = $uri->query(null, true);
        $this->path = $this->grav['uri']->path();
        
        // do not run in these scenarios
        if ($this->isAdmin() ||
            !$this->isDefaultPageType() ||
            !$config['enabled_with_params'] && !empty($params) ||
            !$config['enabled_with_query'] && !empty($query) ||
            $config['whitelist'] && is_array($config['whitelist']) && !in_array($this->path, $config['whitelist']) ||
            $config['blacklist'] && is_array($config['blacklist']) && in_array($this->path, $config['blacklist'])) {
            return;
        }

        $pagecache = $this->grav['cache']->fetch($this->path);
        if ($pagecache) {
            echo $pagecache;
            exit;
        }
    }

    /**
     * Save the page to the cache
     */
    public function onOutputGenerated()
    {
        if ($this->isDefaultPageType()) {
            $this->grav['cache']->save($this->path, $this->grav->output);
        }
    }
}
