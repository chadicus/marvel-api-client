<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the ResourceList class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\ResourceList
 */
final class ResourceListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getAvailable().
     *
     * @test
     * @covers ::__construct
     * @covers ::getAvailable
     *
     * @return void
     */
    public function getAvailable()
    {
        $this->assertSame(4, (new ResourceList(4, 0, 'not under test', []))->getAvailable());
    }

    /**
     * Verify basic behavior of getReturned().
     *
     * @test
     * @covers ::__construct
     * @covers ::getReturned
     *
     * @return void
     */
    public function getReturned()
    {
        $this->assertSame(20, (new ResourceList(0, 20, 'not under test', []))->getReturned());
    }

    /**
     * Verify basic behavior of getCollectionURI().
     *
     * @test
     * @covers ::__construct
     * @covers ::getCollectionURI
     *
     * @return void
     */
    public function getCollectionURI()
    {
        $this->assertSame('a collection uri', (new ResourceList(0, 0, 'a collection uri', []))->getCollectionURI());
    }

    /**
     * Verify basic behavior of getItems().
     *
     * @test
     * @covers ::__construct
     * @covers ::getItems
     *
     * @return void
     */
    public function getItems()
    {
        $this->assertSame(
            [['doesnt' => 'matter']],
            (new ResourceList(0, 0, 'not under test', [['doesnt' => 'matter']]))->getItems()
        );
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $available     The number of total available resources in this list.
     * @param mixed $returned      The number of resources returned in this resource list (up to 20).
     * @param mixed $collectionURI The path to the list of full view representations of the items in this resource
     *                             list.
     * @param array $items         A list of summary views of the items in this resource list.
     *
     * @test
     * @covers ::__construct
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($available, $returned, $collectionURI, array $items)
    {
        new ResourceList($available, $returned, $collectionURI, $items);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'available is not an integer' => ['a string', 0, null, []],
            'returned is not an integer' => [0, 'a string', null, []],
            'collectionURI is not a string' => [0, 0, true, []],
        ];
    }

    /**
     * Verify basic functionality of fromArray().
     *
     * @test
     * @covers ::fromArray
     *
     * @return void
     */
    public function fromArray()
    {
        $resourceList = ResourceList::fromArray(
            [
                'available' => '100',
                'returned' => 1,
                'collectionURI' => 'a collection uri',
                'items' => [['name' => 'a name', 'type' => 'a type', 'resourceURI' => 'a resource uri']],
            ]
        );
        $this->assertSame(100, $resourceList->getAvailable());
        $this->assertSame(1, $resourceList->getReturned());
        $this->assertSame('a collection uri', $resourceList->getCollectionURI());
        $this->assertSame(
            [['name' => 'a name', 'type' => 'a type', 'resourceURI' => 'a resource uri']],
            $resourceList->getItems()
        );
    }

    /**
     * Verify fromArray() throws filter exception.
     *
     * @test
     * @covers ::fromArray
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArrayInvalidAvailable()
    {
        $resourceList = ResourceList::fromArray(['available' => 'a string']);
    }
}
