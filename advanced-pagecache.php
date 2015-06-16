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


    public function onOutputGenerated()
    {
        $this->grav['cache']->save($this->path, $this->grav->output);
    }
}
