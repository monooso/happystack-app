<?php

declare(strict_types=1);

namespace App\PlainObjects;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use DateTime;
use Exception;
use Serializable;

final class ComponentStatus implements Serializable
{
    /**
     * The component key
     *
     * @var string $component
     */
    private string $component;

    /**
     * The date and time at which the status was retrieved
     *
     * @var DateTime $retrievedAt
     */
    private DateTime $retrievedAt;

    /**
     * The service key
     *
     * @var string $service
     */
    private string $service;

    /**
     * The standardised status
     *
     * @var string $status
     */
    private string $status;

    /**
     * Get the component key
     *
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * Set the component key
     *
     * @param string $component
     *
     * @return ComponentStatus
     */
    public function setComponent(string $component): ComponentStatus
    {
        $this->component = $component;
        return $this;
    }

    /**
     * Get the date and time at which the status was retrieved
     *
     * @return DateTime
     */
    public function getRetrievedAt(): DateTime
    {
        return $this->retrievedAt;
    }

    /**
     * Set the date and time at which the status was retrieved
     *
     * @param DateTime $retrievedAt
     *
     * @return ComponentStatus
     */
    public function setRetrievedAt(DateTime $retrievedAt): ComponentStatus
    {
        $this->retrievedAt = $retrievedAt;
        return $this;
    }

    /**
     * Get the service key
     *
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * Set the service key
     *
     * @param string $service
     *
     * @return ComponentStatus
     */
    public function setService(string $service): ComponentStatus
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Get the status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status
     *
     *
     * @param string $status
     *
     * @return ComponentStatus
     */
    public function setStatus(string $status): ComponentStatus
    {
        if (!in_array($status, Status::all(), true)) {
            throw new UnknownStatusException($status);
        }

        $this->status = $status;
        return $this;
    }

    /**
     * Get an array representation of the instance
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'component'   => $this->getComponent(),
            'retrievedAt' => $this->getRetrievedAt(),
            'service'     => $this->getService(),
            'status'      => $this->getStatus(),
        ];
    }

    /**
     * Convert properties to an array, suitable for serialisation
     *
     * @return string
     */
    public function serialize(): string
    {
        $properties = $this->toArray();

        $properties['retrievedAt'] = $properties['retrievedAt']->getTimestamp();

        return serialize($properties);
    }

    /**
     * Restore properties from a serialised string
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $properties = unserialize($serialized);

        $this->setComponent($properties['component']);
        $this->setService($properties['service']);
        $this->setStatus($properties['status']);

        try {
            $retrievedAt = new DateTime('@' . $properties['retrievedAt']);
        } catch (Exception $exception) {
            $retrievedAt = new DateTime('now');
        }

        $this->setRetrievedAt($retrievedAt);
    }
}
