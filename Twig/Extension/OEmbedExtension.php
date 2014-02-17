<?php

namespace Bangpound\Sculpin\Bundle\OEmbedBundle\Twig\Extension;

use Omlex\OEmbed;

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

    public function getOEmbed($url)
    {
        $client = new OEmbed($url);
        $response = $client->getObject();

        return $response;
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
