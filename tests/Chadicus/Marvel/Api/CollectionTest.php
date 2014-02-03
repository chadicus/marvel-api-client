<?php
namespace Chadicus\Marvel\Api;

/**
 * Unit tests for the Collection class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Collection
 */
final class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up for all tests.
     *
     * @return void
     */
    public function setUp()
    {
        GlobalFunctions::$time = function () {
            return 1;
        };
    }

    /**
     * Tear down for all tests.
     *
     * @return void
     */
    public function tearDown()
    {
        GlobalFunctions::reset();
    }

    /**
     * Verifies basic usage of the collection.
     *
     * @test
     * @covers ::__construct
     * @covers ::rewind
     * @covers ::valid
     * @covers ::key
     * @covers ::current
     * @covers ::next
     * @covers ::count
     *
     * @return void
     */
    public function directUsage()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $collection->rewind();
        $iterations = 0;
        while ($collection->valid()) {
            $key = $collection->key();
            $expected = ['id' => $key, 'name' => "a name for item {$key}"];
            $this->assertSame($expected, $collection->current());
            $collection->next();
            ++$iterations;
        }

        $this->assertSame($collection->count(), $iterations);
    }

    /**
     * Verifies code does not explode when rewind() consectutively.
     *
     * @test
     *
     * @return void
     */
    public function consecutiveRewind()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $collection->rewind();
        $collection->rewind();
        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $expected = ['id' => $key, 'name' => "a name for item {$key}"];
            $this->assertSame($expected, $actual);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);
    }

    /**
     * Verifies code does not explode when current() consectutively.
     *
     * @test
     *
     * @return void
     */
    public function consecutiveCurrent()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $this->assertSame(['id' => 0, 'name' => 'a name for item 0'], $collection->current());
        $this->assertSame(['id' => 0, 'name' => 'a name for item 0'], $collection->current());
    }

    /**
     * Verifies code does not explode when next() consectutively.
     *
     * @test
     *
     * @return void
     */
    public function consecutiveNext()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $collection->next();
        $collection->next();
        $this->assertSame(['id' => 1, 'name' => 'a name for item 1'], $collection->current());
    }

    /**
     * Verifies count() lazy loads the next result.
     *
     * @test
     * @covers ::count
     *
     * @return void
     */
    public function count()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $this->assertSame(5, $collection->count());
    }

    /**
     * Verifies key() lazy loads the next result.
     *
     * @test
     * @covers ::key
     *
     * @return void
     */
    public function key()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $this->assertSame(0, $collection->key());
    }

    /**
     * Verifies current() lazy loads the next result.
     *
     * @test
     * @covers ::current
     *
     * @return void
     */
    public function current()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $this->assertSame(['id' => 0, 'name' => 'a name for item 0'], $collection->current());
    }

    /**
     * Verfies current() throws when collection is empty.
     *
     * @test
     * @expectedException \OutOfBoundsException
     *
     * @return void
     */
    public function currentWithEmpty()
    {
        $client = new Client('not under tests', 'not under test', new EmptyAdapter());
        $collection = new Collection($client, 'not under tests');
        $collection->current();
    }

    /**
     * Verfies key() throws when collection is empty.
     *
     * @test
     * @expectedException \OutOfBoundsException
     *
     * @return void
     */
    public function keyWithEmpty()
    {
        $client = new Client('not under tests', 'not under test', new EmptyAdapter());
        $collection = new Collection($client, 'not under tests');
        $collection->key();
    }

    /**
     * Verify Collection can iterated multiple times.
     *
     * @test
     *
     * @return void
     */
    public function multiIteration()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);

        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $expected = ['id' => $key, 'name' => "a name for item {$key}"];
            $this->assertSame($expected, $actual);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);

        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $expected = ['id' => $key, 'name' => "a name for item {$key}"];
            $this->assertSame($expected, $actual);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);

    }

    /**
     * Verify Collection can handle an empty response.
     *
     * @test
     *
     * @return void
     */
    public function emptyResult()
    {
        $client = new Client('not under tests', 'not under test', new EmptyAdapter());
        $collection = new Collection($client, 'not under tests');
        $this->assertFalse($collection->valid());
        $this->assertSame(0, $collection->count());
    }

    /**
     * Verify Collection can handle a response with a single item.
     *
     * @test
     *
     * @return void
     */
    public function oneItemCollection()
    {
        $client = new Client('not under tests', 'not under test', new SingleAdapter());
        $collection = new Collection($client, 'not under tests');
        foreach ($collection as $item) {
            $this->assertSame(['id' => 0, 'name' => 'a name for item 0'], $item);
        }
    }

    /**
     * Verify current() returns result from the loader given in the constructor.
     *
     * @test
     * @covers ::current
     *
     * @return void
     */
    public function currentCustomLoader()
    {
        $loader = function (array $data) {
            $obj = new \StdClass();
            $obj->id = $data['id'];
            $obj->name = $data['name'];
            return $obj;
        };

        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3], $loader);

        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $this->assertInstanceOf('\StdClass', $actual);
            $this->assertSame($key, $actual->id);
            $this->assertSame("a name for item {$key}", $actual->name);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);
    }
}
