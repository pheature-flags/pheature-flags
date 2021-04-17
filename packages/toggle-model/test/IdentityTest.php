<?php

namespace Pheature\Test\Model\Toggle;

use Pheature\Model\Toggle\Identity;
use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    private const ID = '45066d37-fcb6-46d9-9c1e-e85ecd2afece';
    private const PAYLOAD = ['some_payload' => null];

    public function testItShouldBeCreatedWithId(): void
    {
        $identity = new Identity(self::ID, self::PAYLOAD);
        $this->assertSame(self::ID, $identity->id());
        $this->assertSame(self::PAYLOAD, $identity->payload());
    }
}
