<?php

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use XmlMiddleware\XmlRequestMiddleware;
use XmlMiddleware\XmlRequestServiceProvider;

class XmlRequestMacrosTest extends TestCase
{
    public function setup()
    {
        if (!Request::hasMacro('xml') || !Request::hasMacro('isXml')) {
            (new XmlRequestServiceProvider(null))->register();
        }
    }

    protected function createRequest($headers = [], $content = null)
    {
        return new Request([], [], [], [], [], $headers, $content);
    }

    public function contentTypeDataProvider()
    {
        return [
            ['application/xml', true, '<xml><person>human</person></xml>', ['person' => 'human']],
            ['text/xml', true, '', []],
            ['application/json', false, '{"test": true}', ['test' => true]],
            ['application/x-www-form-urlencoded', false, '', []]
        ];
    }

    /**
     * @dataProvider contentTypeDataProvider
     *
     * @param string $contentType
     * @param bool $assertion
     */
    public function testIsXmlMethod($contentType, $assertion)
    {
        $this->assertEquals($assertion, $this->createRequest(['CONTENT_TYPE' => $contentType])->isXml());
    }

    /**
     * @dataProvider contentTypeDataProvider
     *
     * @param $contentType
     * @param $typeAssertion
     * @param $content
     * @param string $expectedContent
     */
    public function testXmlMethod($contentType, $typeAssertion, $content, $expectedContent = '')
    {
        $request = $this->createRequest(['CONTENT_TYPE' => $contentType], $content);
        if ($typeAssertion) {
            $this->assertEquals($expectedContent, $request->xml());
            $this->assertEquals((object)$expectedContent, $request->xml(false));
        } else {
            $this->assertEquals([], $request->xml());
            $this->assertEquals(new \stdClass, $request->xml(false));
        }
    }

    /**
     * @dataProvider contentTypeDataProvider
     *
     * @param $contentType
     * @param $isXml
     * @param $content
     * @param string $expectedContent
     */
    public function testXmlMiddleware($contentType, $isXml, $content, $expectedContent = '')
    {
        $request = $this->createRequest(['CONTENT_TYPE' => $contentType], $content);
        // Make sure we have an empty array before middleware
        if($isXml) {
            $this->assertEquals([], $request->all());
        }

        // Apply the middleware
        (new XmlRequestMiddleware)->handle($request, function ($request) {
            return $request;
        });

        // If this is xml we want to make sure the content is there
        // If not then it's gonna be an empty array
        $this->assertEquals($expectedContent, $request->all());
    }
}
