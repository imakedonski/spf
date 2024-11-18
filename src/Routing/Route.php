<?php

namespace SPF\Routing;

final class Route
{
    public function __construct(
      private readonly string $path,
      private readonly string|\Closure $handler,
      private readonly string $method = 'GET',
    ){}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler()
    {
        return $this->handler;
    }
}
