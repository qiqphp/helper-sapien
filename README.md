# Qiq Helpers for Sapien

This package provides [Qiq](https://qiqphp.com) helpers for
[Sapien](https://sapienphp.com) _Request_ and _Response_ objects. These helpers
allow you to read from a _Request_, and build a _Response_, from **inside a
Qiq _Template_**.

Read more about:

- [the request helper](#request-helper)
- [the response helper](#response-helper)

## Installation

This package is installable via Composer as
[qiq/helpers-sapien](https://packagist.org/packages/qiq/helper-sapien):

```
composer require qiq/helpers-sapien ^1.0
```

## Request Helper

### Registration

To make the request helper available inside your _Template_, you need to
register it with the Qiq _HelperLocator_. You can register the helper
semi-automatically ...

```php
use Qiq\Helper\Sapien\Request;
use Qiq\Template;

/** @var Template $template */
Request::register('request', $template);
```

... or you can do so more manually:

```php
use Qiq\Helper\Sapien\Request;
use Qiq\Template;

/** @var Template $template */
$template->getHelperLocator()->set('request', function () {
    return new Request();
});
```

By default, the helper will construct a new internal Sapien _Request_ object of
its own. If you want to pass an existing _Request_ object, you may do so:

```php
use Qiq\Helper\Sapien\Request;
use Qiq\Template;
use Sapien\Request as SapienRequest;

/** @var Template $template */
/** @var SapienRequest $sapienRequest */
Request::register('request', $template, $sapienRequest);

// or:
$template->getHelperLocator()->set('request', function () use ($sapienRequest) {
    return new Request($sapienRequest);
});
```

### Usage

Once the helper is registered, you can call it using the method name you
registered it under. You can use Qiq syntax ...

```qiq
{{ request()->... }}
```

... or PHP syntax:

```html+php
<?php $this->request()->... ?>
```

The request helper proxies to its internal Sapien _Request_ object, making
all of the _Request_ properties available.

```qiq
<p>The requested URL path is {{h request()->url->path }}</p>
```

To replace the proxied _Request_ object, use the `set()` method:

```qiq
{{ request()->set(new \Sapien\Request() )}}
```

To get the proxied _Request_ object directly, use the `get()` method:

```html+php
<?php $request = $this->request()->get(); ?>
```

## Response Helper

### Registration

To make the response helper available inside your _Template_, you need to
register it with the Qiq _HelperLocator_. You can register the helper
semi-automatically ...

```php
use Qiq\Helper\Sapien\Response;
use Qiq\Template;

/** @var Template $template */
Response::register('response', $template);
```

... or you can do so more manually:

```php
use Qiq\Helper\Sapien\Response;
use Qiq\Template;

/** @var Template $template */
$template->getHelperLocator()->set('response', function () {
    return new Response();
});
```

By default, the helper will construct a new internal Sapien _Response_ object of
its own. If you want to pass an existing _Response_ object, you may do so:

```php
use Qiq\Helper\Sapien\Response;
use Qiq\Template;
use Sapien\Response as SapienResponse;

/** @var Template $template */
/** @var SapienResponse $sapienResponse */
Request::register('response', $template, $sapienResponse);

// or:
$template->getHelperLocator()->set('response', function () use ($sapienResponse) {
    return new Request($sapienResponse);
});
```

### Usage

Once the helper is registered, you can call it using the method name you
registered it under. You can use Qiq syntax ...

```qiq
{{ response()->... }}
```

... or PHP syntax:

```html+php
<?php $this->response()->... ?>
```

The response helper proxies to its internal Sapien _Repsonse_ object, making
all of the _Response_ methods available.

```qiq
{{ response()->setVersion(...) }}
{{ response()->setStatus(...) }}
{{ response()->setHeader(...) }}
{{ response()->setCookie(...) }}
```

To replace the proxied _Response_ object, use the `set()` method:

```qiq
{{ request()->set(new \Sapien\Response() )}}
```

To get the proxied _Response_ object directly, use the `get()` method:

```html+php
<?php $response = $this->response()->get(); ?>
```

### Rendering

With typical _Template_ usage, the code calling a _Template_ will set the
rendered template results into a _Response_ body ...

```php
/** @var \Qiq\Template $template */
$template->setData(...);
$template->setView(...);
$template->setLayout(...);
$content = $template();

/** @var \Sapien\Response $response */
$response->setContent($content);
```

... as well as setting version, status, headers, and cookies on the _Response_.

With this helper, you instead call `render()` on the helper itself, passing it
the _Template_ object to be used for content, and get back a _Response_
object.


```php
/** @var \Qiq\Template $template */
$template->setData(...);
$template->setView(...);
$template->setLayout(...);

/** @var \Sapien\Response $response */
$response = $template->response()->render($template);
```

The returned _Response_ will be the one built up inside the template, complete
with the version, status, headers, and cookies that were set in the template
code.

### Content Replacement

When you call `response()->render()`, the _Response_ content will be whatever
was rendered from a normal _Template_ invocation.

However, you can override this behavior by calling `response()->setContent(...)`
and setting the content directly from inside the template code. In the following
example, the _Response_ content will be "Hello, world!":

```qiq
Goodbye, content!
{{ response()->setContent("Hello, world!") }}
```

### Specialized Responses

While you can replace the internal _Response_ using `response()->set(...)` and
exert fine control over the replacement _Response_, the helper provides two
convenience methods to replace the _Response_ with specialized Sapien
responses: one for _FileResponse_ and one for _JsonResponse_.

To convert the _Response_ to a _FileResponse_, call `response()->setFile()`:

```qiq
{{ response()->setFile(
    name: 'path/to/file.php',
) }}
```

The `setFile()` method mimics the identically-named method in
[_FileResponse_](https://sapienphp.com/1.x/response/special.html#1-2-9-1).

To convert the _Response_ to a _JsonResponse_, call `response()->setJson()`. The
following example puts all the current _Template_ data into a _JsonResponse_:

```qiq
{{ response()->setJson($this->getData()) }}
```

The `setJson()` method mimics the identically-named method in
[_JsonResponse_](https://sapienphp.com/1.x/response/special.html#1-2-9-2).

Note that these methods will override any output that would normally be rendered
by the template code, replacing that output with the file output or JSON output,
respectively.
