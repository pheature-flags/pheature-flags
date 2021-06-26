<?php

declare(strict_types=1);
namespace Pheature\Test\Community\Laravel;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Nyholm\Psr7\ServerRequest;
use Pheature\Community\Laravel\RouteParameterAsPsr7RequestAttribute;
use PHPUnit\Framework\TestCase;

final class RouteParameterAsPsr7RequestAttributeTest extends TestCase
{
    public function testItShouldSetAsRequestAttributesLaravelRouterParameters(): void
    {
        $request = new ServerRequest('GET', '/');
        $laravelRouting = $this->createMock(Route::class);
        $laravelRouting->expects(self::once())
            ->method('parameters')
            ->willReturn(['key' => 'value']);
        $laravelRequest = $this->createMock(Request::class);
        $laravelRequest->expects(self::once())
            ->method('route')
            ->willReturn($laravelRouting);
        $app = $this->createMock(Application::class);
        $app->expects(self::once())
            ->method('make')
            ->with('request')
            ->willReturn($laravelRequest);

        $middleware = new RouteParameterAsPsr7RequestAttribute($app);
        $request = $middleware($request);
        self::assertSame('value', $request->getAttribute('key'));
    }
}
