<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the ResourceList class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\ResourceList
 * @covers ::<protected>
 */
final class ResourceListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getAvailable().
     *
     * @test
     *
     * @return void
     */
    public function getAvailable()
    {
        $this->assertSame(4, (new ResourceList(['available' => 4]))->getAvailable());
    }

    /**
     * Verify basic behavior of getReturned().
     *
     * @test
     *
     * @return void
     */
    public function getReturned()
    {
        $this->assertSame(20, (new ResourceList(['returned' => 20]))->getReturned());
    }

    /**
     * Verify basic behavior of getCollectionURI().
     *
     * @test
     *
     * @return void
     */
    public function getCollectionURI()
    {
        $this->assertSame('a collection uri', (new ResourceList(['collectionURI' => 'a collection uri']))->getCollectionURI());
    }

    /**
     * Verify basic behavior of getItems().
     *
     * @test
     *
     * @return void
     */
    public function getItems()
    {
        $resourceList = new ResourceList(
            [
                'items' => [
                    [
                        'resourceURI' => 'a resource uri',
                        'name' => 'a name',
                        'type' => 'a type',
                        'role' => 'a role',
                    ],
                ],
            ]
        );

        $items = $resourceList->getItems();

        $this->assertSame(1, count($items));
        $this->assertSame('a resource uri', $items[0]->resourceURI);
        $this->assertSame('a name', $items[0]->name);
        $this->assertSame('a type', $items[0]->type);
        $this->assertSame('a role', $items[0]->role);
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
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($available, $returned, $collectionURI, array $items)
    {
        new ResourceList(
            [
                'available' => $available,
                'returned' => $returned,
                'collectionURI' => $collectionURI,
                'items' => $items,
            ]
        );
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
        $this->assertSame(1, count($resourceList->getItems()));
        $this->assertSame('a name', $resourceList->getItems()[0]->getName());
        $this->assertSame('a type', $resourceList->getItems()[0]->getType());
        $this->assertSame('a resource uri', $resourceList->getItems()[0]->getResourceURI());
    }

    /**
     * Verify fromArray() throws filter exception.
     *
     * @test
     * @expectedException \Exception
     *
     * @return void
     */
    public function fromArrayInvalidAvailable()
    {
        $resourceList = ResourceList::fromArray(['available' => 'a string']);
    }
}
