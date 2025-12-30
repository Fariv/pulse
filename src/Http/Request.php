<?php
namespace Pulse\Http;

use Swoole\Http\Request as SwooleRequest;

class Request
{
    public array $get = [];
    public array $post = [];
    public array $server = [];
    public array $header = [];

    public function __construct(array $get = [], array $post = [], array $server = [], array $header = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->header = $header;
    }

    public static function fromSwoole(SwooleRequest $req): self
    {
        return new self(
            $req->get ?? [],
            $req->post ?? [],
            $req->server ?? [],
            $req->header ?? []
        );
    }

    public function getPath(): string
    {
        // Default to '/' if not set
        return $this->server['request_uri'] ?? '/';
    }
}
