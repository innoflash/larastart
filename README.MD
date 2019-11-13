# LaraStart

## Installation
```sh
composer require innoflash/larastart
```

## Usage

### API Responses
This used for manipulating API JSON responses for ```success/fail``` calls

* You can simply use for like:

```php
<?php

use InnoFlash\LaraStart\Traits\APIResponses;

class ClassName
{
    use APIResponses;
```

* Usage on all your controllers

```php
<?php

namespace App\Http\Controllers;

use InnoFlash\LaraStart\Traits\APIResponses;
...

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use APIResponses;
}
```

#### Calling the functions
```php
$this->successMessege('{optional :message here}')
```

and this gets you a response with status 200

![Response image](images/response_message.png)


### ExceptionsTrait
This is to override Laravel error logging do that we sent all type of errors to our API clients

* You can simply set it up by using the ExceptionsTrait in your ```Exceptions\Handler class```

```php
<?php

namespace App\Exceptions;

use Exception;
use InnoFlash\LaraStart\Traits\ExceptionsTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ExceptionsTrait;
```

* Now override the render function

```php
    public function render($request, Exception $exception)
    {
        return $this->apiExceptions($request, $exception);
    }
```

* This overrides all errors and format 

![Error message](images/error_message.png)

If you want the default error rendering 
```php
return $this->apiExceptions($request, $exception, true); //true triggers the default error
```