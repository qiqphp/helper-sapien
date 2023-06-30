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
[qiq/helper-sapien](https://packagist.org/packages/qiq/helper-sapien):

```
composer require qiq/helper-sapien ^2.0
```

## Adding The Helpers

To access the `request()` and `response()` helper methods in your template
files, use the _SapienHelperMethods_ trait in your custom _Helpers_
class ...

```php
namespace Project\Template;

use Qiq\Helpers;
use Qiq\Helper\Sapien\SapienHelperMethods;

class CustomHelpers extends Helpers
{
    use SapienHelperMethods;
}
```

... and instantiate a _Template_ object with your custom _Helpers_:

```php
use Project\Template\CustomHelpers;
use Qiq\Template;

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers()
);
```

## Request Helper

### Configuration

By default, the request helper will construct a new internal Sapien _Request_
object of its own. If you want to pass a different _Request_ object, you may
do so via the Qiq _Container_ configuration:

```php
use Project\Template\CustomHelpers;
use Qiq\Container;
use Qiq\Helper\Sapien\Request as RequestHelper;
use Qiq\Template;
use Sapien\Request as SapienRequest;

$container = new Container([
    RequestHelper::class => [
        'request' = new SapienRequest(...),
    ])
]);

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers($container)
);
```

### Usage

Call the request helper as `request()` using Qiq syntax ...

```qiq
{{ request()->... }}
```

... or PHP syntax:

```php
<?php $this->request()->... ?>
```

The request helper proxies to its internal Sapien _Request_ object, making
all of the _Request_ properties available.

```qiq
<p>The requested URL path is {{h request()->url->path }}</p>
```

To replace the proxied _Request_ object, use the `set()` method:

```qiq
{{ request()->set(new \Sapien\Request()) }}
```

To get the proxied _Request_ object directly, use the `get()` method:

```html+php
{{ $request = request()->get() }}
```

## Response Helper

### Configuration

By default, the response helper will construct a new internal
Sapien _Response_ object of its own. If you want to pass a
different _Response_ object, you may do so via the Qiq _Container_
configuration:

```php
use Project\Template\CustomHelpers;
use Qiq\Container;
use Qiq\Helper\Sapien\Response as ResponseHelper;
use Qiq\Template;
use Sapien\Response as SapienResponse;

$container = new Container([
    ResponseHelper::class => [
        'response' = new SapienResponse(...),
    ])
]);

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers($container)
);
```

### Usage

Call the request helper as `response()` using Qiq syntax ...


```qiq
{{ response()->... }}
```

... or PHP syntax:

```php
<?php $this->response()->... ?>
```

The response helper proxies to its internal Sapien _Repsonse_ object, making
all of the _Response_ methods available.

```qiq
{{ response()->setVersion(...) }}
{{ response()->setCode(...) }}
{{ response()->setHeader(...) }}
{{ response()->setCookie(...) }}
```

To replace the proxied _Response_ object, use the `set()` method:

```qiq
{{ response()->set(new \Sapien\Response()) }}
```

To get the proxied _Response_ object directly, use the `get()` method:

```qiq
{{ $response = response()->get() }}
```

### Rendering

With typical _Template_ usage, the code calling a _Template_ will set the
rendered template results into a _Response_ body ...

```php
/** @var \Qiq\Engine&\Project\Template\CustomHelpers $template */
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
/** @var \Qiq\Engine&\Project\Template\CustomHelpers $template */
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
{{ response()->setFile('path/to/file.php') }}
```

The `setFile()` method mimics the identically-named method in
[_FileResponse_](https://sapienphp.com/1.x/response/special.html#1-2-9-1).

To convert the _Response_ to a _JsonResponse_, call `response()->setJson()`. The
following example puts all the current _Template_ data into a _JsonResponse_:

```qiq
{{ response()->setJson(getData()) }}
```

The `setJson()` method mimics the identically-named method in
[_JsonResponse_](https://sapienphp.com/1.x/response/special.html#1-2-9-2).

Note that these methods will override any output that would normally be rendered
by the template code, replacing that output with the file output or JSON output,
respectively.
