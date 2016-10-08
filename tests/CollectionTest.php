<?php
namespace Chadicus\Marvel\Api;

use Chadicus\Marvel\Api\Assets\CollectionAdapter;
use Chadicus\Marvel\Api\Assets\EmptyAdapter;
use Chadicus\Marvel\Api\Assets\SingleAdapter;

/**
 * Unit tests for the Collection class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Collection
 * @covers ::<private>
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
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date']);
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return 1;
            }
        );
    }

    /**
     * Tear down for all tests.
     *
     * @return void
     */
    public function tearDown()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date']);
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
            $this->assertSame($key, $collection->current()->id);
            $this->assertSame("a title for comic {$key}", $collection->current()->title);
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
            $this->assertSame($key, $collection->current()->id);
            $this->assertSame("a title for comic {$key}", $collection->current()->title);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);
    }

    /**
     * Verifies code does not explode when current() consectutively.
     *
     * @test
     * @covers ::current
     *
     * @return void
     */
    public function consecutiveCurrent()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $this->assertSame(0, $collection->current()->id);
        $this->assertSame(0, $collection->current()->id);
    }

    /**
     * Verifies code does not explode when next() consectutively.
     *
     * @test
     * @covers ::next
     *
     * @return void
     */
    public function consecutiveNext()
    {
        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3]);
        $collection->next();
        $collection->next();
        $this->assertSame(1, $collection->current()->id);
        $this->assertSame('a title for comic 1', $collection->current()->title);
    }

    /**
     * Verifies count() lazy loads the next result.
     *
     * @test
     * @covers ::count
     *
     * @return void
     */
    public function countBasic()
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
        $this->assertSame(0, $collection->current()->getId());
        $this->assertSame('a title for comic 0', $collection->current()->getTitle());
    }

    /**
     * Verfies current() throws when collection is empty.
     *
     * @test
     * @covers ::current
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
     * @covers ::key
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
            $this->assertSame($key, $collection->current()->getId());
            $this->assertSame("a title for comic {$key}", $collection->current()->getTitle());
            ++$iterations;
        }

        $this->assertSame(5, $iterations);

        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $this->assertSame($key, $collection->current()->getId());
            $this->assertSame("a title for comic {$key}", $collection->current()->getTitle());
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
            $this->assertSame(0, $collection->current()->getId());
            $this->assertSame('a title for comic 0', $collection->current()->getTitle());
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
        $loader = function ($comic) {
            $obj = new \StdClass();
            $obj->id = $comic->id;
            $obj->name = $comic->title;
            return $obj;
        };

        $client = new Client('not under tests', 'not under test', new CollectionAdapter());
        $collection = new Collection($client, 'not under tests', ['limit' => 3], $loader);

        $iterations = 0;
        foreach ($collection as $key => $actual) {
            $this->assertInstanceOf('\StdClass', $actual);
            $this->assertSame($key, $actual->id);
            $this->assertSame("a title for comic {$key}", $actual->name);
            ++$iterations;
        }

        $this->assertSame(5, $iterations);
    }
}
