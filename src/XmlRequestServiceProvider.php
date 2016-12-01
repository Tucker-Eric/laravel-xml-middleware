<?php

namespace XmlMiddleware;

use Illuminate\Support\ServiceProvider;
use Request;

class XmlRequestServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Request::macro('isXml', function () {
            return strtolower($this->getContentType()) === 'xml';
        });

        Request::macro('xml', function ($assoc = true) {
            if (!$this->isXml()) {
                return [];
            }
            // Returns the xml input from a request
            $xml = simplexml_load_string($this->getContent());
            $json = json_encode($xml);

            return json_decode($json, $assoc);
        });
    }
}