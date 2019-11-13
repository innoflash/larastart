# LaraStart

## Installation
```sh
composer require innoflash/larastart
```

## Usage

### API Responses
This used for manipulating API JSON responses for ```success/fail``` calls

* You can simply use for like:

![Generic image](images/generic_api_trait.png)

* Usage on all your controllers

![API image](images/controller_api.png)

#### Calling the functions
```php
$this->successMessege('{optional :message here}')
```

and this gets you a response with status 200

![Response image](images/response_message.png)


### ExceptionsTrait
This is to override Laravel error logging do that we sent all type of errors to our API clients

* You can simply set it up by using the ExceptionsTrait in your ```Exceptions\Handler class```

![Response image](images/exceptions_init.png)

* Now override the render function

![Exceptions image](images/exceptions_setup.png)

* This overrides all errors and format 

![Error message](images/error_message.png)

If you want the default error rendering 
```php
return $this->apiExceptions($request, $exception, true); //true triggers the default error
```