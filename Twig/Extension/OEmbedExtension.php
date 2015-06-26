<?php

namespace Bangpound\Sculpin\Bundle\OEmbedBundle\Twig\Extension;

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\QueryString;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\CallbackCanCacheStrategy;
use Guzzle\Plugin\Cache\DefaultCacheStorage;
use Guzzle\Plugin\Cache\SkipRevalidation;

class OEmbedExtension extends \Twig_Extension
{
    private $cache_dir;

    /**
     *
     */
    public function __construct($cache_dir)
    {
        $this->cache_dir = $cache_dir;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('oembed', array($this, 'getOEmbed'), array('is_safe' => array('html' => true))),
        );
    }

    public function getOEmbed($url, $params = array())
    {
        $params['url'] = $url;
        $client = new Client();

        $dir = $this->cache_dir.'/oembed';
        $plugin = new CachePlugin(
            array(
                'storage' => new DefaultCacheStorage(
                        new DoctrineCacheAdapter(
                            new FilesystemCache($dir, 'guzzlecache.data')
                        )
                    ),
                'revalidation' => new SkipRevalidation(),
                'can_cache' => new CallbackCanCacheStrategy(
                        function (RequestInterface $request) {
                            return true;
                        }
                    ),
            )
        );
        $client->addSubscriber($plugin);

        try {
            $query = new QueryString($params);
            $request = $client->get('http://api.embed.ly/1/oembed?'.$query);
            $request->getParams()->set('cache.override_ttl', 3600);
            $response = $request->send();
            $data = $response->json();
            $response = $data['html'];

            return $response;
        } catch (BadResponseException $e) {
        }
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'oembed';
    }
}
