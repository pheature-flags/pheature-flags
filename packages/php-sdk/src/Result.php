<?php

declare(strict_types=1);

namespace Pheature\Sdk;

final class Result
{
    /** @var null|mixed null */
    private $data;

    /**
     * Result constructor.
     * @param null|mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
