<?php
namespace Pulse\Http;

class Response
{
    protected array $headers = [];
    protected int $status = 200;
    protected string $body = '';

    public function header(string $name, string $value): void
    {
        $this->headers[$name][] = $value;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function status(int $code): void
    {
        $this->status = $code;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function write(string $content): void
    {
        $this->body .= $content;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Set JSON content
     */
    public function json(array $data, int $status = 200): self
    {
        $this->status($status);
        $this->header('Content-Type', 'application/json');
        $this->body = json_encode($data, JSON_PRETTY_PRINT);
        return $this;
    }
}
