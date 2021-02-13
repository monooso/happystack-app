<?php

namespace Tests\Unit\PlainObjects;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use App\PlainObjects\ComponentStatus;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;
use PHPUnit\Framework\TestCase;

class ComponentStatusTest extends TestCase
{
    use InteractsWithTime;

    /** @test */
    public function set_component_is_fluent()
    {
        $subject = (new ComponentStatus())->setComponent('component');

        $this->assertInstanceOf(ComponentStatus::class, $subject);
    }

    /** @test */
    public function set_retrieved_at_is_fluent()
    {
        $subject = (new ComponentStatus())->setRetrievedAt(new DateTime());

        $this->assertInstanceOf(ComponentStatus::class, $subject);
    }

    /** @test */
    public function set_service_is_fluent()
    {
        $subject = (new ComponentStatus())->setService('service');

        $this->assertInstanceOf(ComponentStatus::class, $subject);
    }

    /** @test */
    public function set_status_is_fluent()
    {
        $subject = (new ComponentStatus())->setStatus(Status::OKAY);

        $this->assertInstanceOf(ComponentStatus::class, $subject);
    }

    /** @test */
    public function set_status_throws_an_exception_if_the_status_is_invalid()
    {
        $this->expectException(UnknownStatusException::class);

        (new ComponentStatus())->setStatus('nope');
    }

    /** @test */
    public function to_array_returns_an_array_of_attributes()
    {
        $retrievedAt = new DateTime();

        $attributes = (new ComponentStatus())
            ->setComponent('test_component')
            ->setRetrievedAt($retrievedAt)
            ->setService('test_service')
            ->setStatus(Status::WARN)
            ->toArray();

        $this->assertEquals([
            'component'   => 'test_component',
            'retrievedAt' => $retrievedAt,
            'service'     => 'test_service',
            'status'      => Status::WARN,
        ], $attributes);
    }

    /** @test */
    public function serialize_serializes_the_attributes_array()
    {
        $retrievedAt = new DateTime();

        $serialized = (new ComponentStatus())
            ->setComponent('test_component')
            ->setRetrievedAt($retrievedAt)
            ->setService('test_service')
            ->setStatus(Status::WARN)
            ->serialize();

        $this->assertSame(serialize([
            'component'   => 'test_component',
            'retrievedAt' => $retrievedAt->getTimestamp(),
            'service'     => 'test_service',
            'status'      => Status::WARN,
        ]), $serialized);
    }

    /** @test */
    public function unserialize_hydrates_the_instance()
    {
        $retrievedAt = Carbon::now()->subDays(5);

        $serialized = serialize([
            'component'   => 'test_component',
            'retrievedAt' => $retrievedAt->getTimestamp(),
            'service'     => 'test_service',
            'status'      => Status::DOWN,
        ]);

        $subject = new ComponentStatus();
        $subject->unserialize($serialized);

        $this->assertSame('test_component', $subject->getComponent());
        $this->assertSame($retrievedAt->getTimestamp(), $subject->getRetrievedAt()->getTimestamp());
        $this->assertSame('test_service', $subject->getService());
        $this->assertSame(Status::DOWN, $subject->getStatus());
    }

    /** @test */
    public function unserialize_falls_back_to_current_date_time()
    {
        $this->travel(2)->weeks();

        $serialized = serialize([
            'component'   => 'test_component',
            'retrievedAt' => 'nope',
            'service'     => 'test_service',
            'status'      => Status::DOWN,
        ]);

        $subject = new ComponentStatus();
        $subject->unserialize($serialized);

        $this->assertSame(Carbon::now()->getTimestamp(), $subject->getRetrievedAt()->getTimestamp());

        $this->travelBack();
    }

    /** @test */
    public function unserialize_ignores_missing_attributes()
    {
        $serialized = serialize(['component' => 'test_component']);

        $subject = new ComponentStatus();
        $subject->unserialize($serialized);

        $this->assertSame('test_component', $subject->getComponent());
        $this->assertNull($subject->getService());
        $this->assertSame(Status::UNKNOWN, $subject->getStatus());
    }
}
