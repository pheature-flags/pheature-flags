<?php

declare(strict_types=1);

namespace Pheature\Sdk;

final class Result
{
    /** @var mixed null */
    private $data;

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
