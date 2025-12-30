# Pulse

**Pulse** is a lightweight PHP microframework built **specifically for OpenSwoole** and other **long-running PHP environments**.  
It is developed using **PHP 8.5**, so it leverages the latest language features, performance improvements, and modern syntax.  

Pulse helps you build fast, predictable APIs **without accidentally leaking memory or request state** — a common problem when moving from traditional PHP to OpenSwoole.

---

## Who is Pulse for?

Pulse is for developers who:

- want to use **OpenSwoole safely**
- are building **APIs, internal tools, or services**
- want **full control** instead of magic
- are okay with learning how long-running PHP actually works

Pulse is especially suitable for:

- internal systems
- dashboards
- service backends
- async or event-driven apps

---

## Who Pulse is *not* for

Pulse is **not** a good fit if you want:

- a Laravel replacement
- automatic session handling
- heavy ORMs
- hidden globals or facades
- “just works without understanding” abstractions

If you want batteries included, Pulse is probably **not** the right tool.

---

## Why Pulse exists

Traditional PHP frameworks assume this lifecycle:

request → process → response → memory wiped

OpenSwoole changes that completely:

process starts → handles thousands of requests → memory persists


This difference is **the root cause of most bugs** when using OpenSwoole with existing frameworks.

Pulse is designed around this reality instead of ignoring it.

---

## What does “persistent memory” mean?

In Pulse (and OpenSwoole):

- PHP workers **do not restart after each request**
- variables, objects, and connections **stay in memory**
- mistakes can silently affect future requests

Pulse does **not** mean:

- “store user data in globals”
- “share request state between users”

It means:

> Pulse assumes memory lives longer than one request and makes request boundaries explicit.

---

## What Pulse helps you do ✅

### 1. Clearly separate request state from worker state

Pulse enforces a simple rule:

- **Worker-level objects** live long  
  (DB pools, Redis clients, config, route maps)

- **Request-level objects** must die after the request  
  (auth info, user data, request context)

Pulse gives you tools to keep this boundary clear.

### 2. Avoid accidental state leaks

Pulse encourages:

- request-scoped context objects
- explicit lifecycle hooks
- no hidden globals

This makes it **harder to accidentally leak data between requests**.

### 3. Detect memory growth early

Pulse can:

- measure memory usage per request
- warn if memory keeps growing
- help you catch leaks during development

It does not magically fix leaks — but it **makes them visible**.

### 4. Build fast OpenSwoole applications safely

Because Pulse is:

- minimal
- OpenSwoole-first
- long-running aware

You get:

- high performance
- low overhead
- predictable behavior

---

## What Pulse does NOT do ❌

Pulse is intentionally limited.

It does **not**:

- automatically manage sessions
- magically clean memory for you
- prevent all memory leaks
- hide OpenSwoole complexity
- manage multi-node state for you

Pulse assumes you want to **understand and control** your system.

---

## Example

```php
Pulse::route('GET', '/health', function ($req, $res, $ctx) {
    return $res->json([
        'status' => 'ok'
    ]);
});
```


Here:

``$req`` and ``$ctx`` exist only for this request

worker-level services are reused safely

nothing leaks into the next request

# Design philosophy (important)

## Pulse follows these principles:

- Explicit over magical
- Lifecycle-aware
- Stateless by default
- Minimal surface area
- Fail early in development
- Pulse would rather warn you than silently do the wrong thing.

## Requirements

- PHP 8.5+
- OpenSwoole
- CLI environment (not PHP-FPM)
