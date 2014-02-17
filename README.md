# Sculpin oEmbed Bundle

This bundle is not ready to use!

## Setup

Add this bundle in your ```sculpin.json``` file:

```json
{
    // ...
    "require": {
        // ...
        "bangpound/sculpin-oembed-bundle": "@dev"
    }
}
```

and install this bundle running ```sculpin update```.

Now you can register the bundle in ```SculpinKernel``` class available on ```app/SculpinKernel.php``` file:

```php
class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return array(
           'Bangpound\Sculpin\Bundle\OEmbedBundle\SculpinOEmbedBundle'
        );
    }
}
```

## How to use

In a Twig template, use the oembed function with a URL argument.

```
{{ oembed('https://vimeo.com/86129237') }}
```

## TODO

* Use a different oEmbed library. `[excelwebzone/oemebd](https://github.com/excelwebzone/Omlex)` does not support
  oEmbed parameters.
* Support caching.
