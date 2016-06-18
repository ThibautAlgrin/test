<?php

namespace Algrin\ArticleBundle\Tests\Controller;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
   * Article Test controller.
 */
class ArticleRESTControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * Test Get a Article entity
     *
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->faker = Factory::create();
    }

    private function getOneId() {
        $url = $this->router->generate('get_articles_api', ['_format' => 'json']);
        $this->client->request(Request::METHOD_GET, $url);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = $this->client->getResponse()->getContent();
        $this->assertTrue(is_string($data));
        $json = json_decode($data, true);
        $this->assertTrue(is_array($json));
        $this->assertNotEmpty($json);
        $this->assertArrayNotHasKey('id', $json);
        $first = reset($json);
        $this->assertArrayHasKey('id', $first);
        return $first['id'];
    }

    /**
    * Test to create Article
    */
    public function testPostAction()
    {
        $data = [
            'article' => [
                'title' => implode(' ',$this->faker->words(5)),
                'leading' => implode(' ', $this->faker->words(50)),
                'body' => implode(' ', $this->faker->words(100)),
                'createdBy' => sprintf("%s %s", $this->faker->firstNameFemale, $this->faker->lastName),
            ]
        ];
        $url = $this->router->generate('post_article', ['_format' => 'json']);
        $this->client->request(Request::METHOD_POST, $url, $data);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = $this->client->getResponse()->getContent();
        $this->assertTrue(is_string($data));
        $json = json_decode($data, true);
        $this->assertTrue(is_array($json));
        $this->assertNotEmpty($json);
        $this->assertArrayNotHasKey('errors', $json);
        $this->assertArrayHasKey('id', $json);
    }

    /**
     * Test to Get list of Article entity
     */
    public function testCgetAction()
    {
        $this->getOneId();
    }

    /**
     * Test to Get one entity Article
    * @depends testCgetAction
     */
    public function testGetAction()
    {
        $id = $this->getOneId();
        $url = $this->router->generate('get_article_api', [
            '_format' => 'json',
            'id' => $id
        ]);
        $this->client->request(Request::METHOD_GET, $url);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = $this->client->getResponse()->getContent();
        $this->assertTrue(is_string($data));
        $json = json_decode($data, true);
        $this->assertTrue(is_array($json));
        $this->assertNotEmpty($json);
        $this->assertArrayHasKey('id', $json);
    }

    /**
    * Test delete one Article
    * @depends testCgetAction
    */
    public function testDeleteAction()
    {
        $id = $this->getOneId();
        $url = $this->router->generate('delete_article', [
            '_format' => 'json',
            'entity' => $id
        ]);
        $this->client->request(Request::METHOD_DELETE, $url);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }


}
