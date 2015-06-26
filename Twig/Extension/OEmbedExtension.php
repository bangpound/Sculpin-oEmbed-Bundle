<?php

namespace Bangpound\Sculpin\Bundle\OEmbedBundle\Twig\Extension;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class OEmbedExtension extends \Twig_Extension
{
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

        try {
            $response = $client->get('http://api.embed.ly/1/oembed', array(
              'query' => $params,
            ));
            $data = json_decode($response->getBody()->getContents(), true);
            $response = $data['html'];

            return $response;
        } catch (BadResponseException $e) {
            return $url;
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
