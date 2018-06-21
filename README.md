# Tell, don't ask principle in your laravel controllers

## What this package is good for ?

**This package helps you refactor your controller code**

### Installation:

`
composer require imanghafoori/laravel-terminator
`


### Code smell:
- when you see that you have an endpoint from which you have to send back more than one type of response... then this package is going to help you a lot.

#### Example:

consider a login endpoint! it may return 4 type of responses in different cases:
- 1- User is already logged in, so redirect.
- 2- Successfull login
- 3- Invalid credentials error
- 4- Incorrect credentials error
- 5- Too many login attempts error


The fact that frameworks force us to "return a response" from controllers prevents us from simplify controllers beyond a certain point.
So we decide to break that jail and bring ourselves freedom. 

The idea is : Any class in the application should be able to send back a response.

## Remember:

# Controllers are Controllers, they are not Responders !!!

They control the execution flow and send commands to other objects and tell them what to do. Their responsibily is not to send a response back to the client.


Consider the code below:

```php
// BAD code : Too many conditions
// BAD code : In a sinle method
// BAD code : (@_@)   (?_?)

class AuthController {
  public function login(Request $request)
  {
            // 1 - Validate Request
           $validator = Validator::make($request->all(), [
              'email' => 'required|max:255||string',
              'password' => 'required|confirmed||string',
          ]);
          if ($validator->fails()) {
              return redirect('/some-where')->withErrors($validator)->withInput();
          }
          // end 1
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              return $this->sendLockoutResponse($request);
          }
          // end 2
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
              return $this->sendLoginResponse($request);
          }
          // end 3

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          return $this->sendFailedLoginResponse($request);
          // end 4
          
          //These if blocks can not be extracted out.
  }
}

```
#### Problem :

With the current approach, this is as much as we can refactor at best.
Why ? because the controllers are asking for response, they are not telling what to do.


```php

// Good code
// Good code
// Good code

class LoginController
{
    public function Login(Request $request)
    {
        // Here we are telling what to do (not asking them)
        // Nice ???
        $this->validateRequest();          // 1
        $this->throttleAttempts();         // 2
        $this->handleValidCredentials();   // 3 
        $this->handleInvalidCredentials(); // 4
        
    }
    
    // private functions may sit here
    
    ...
    
}
```



#### Refactoring Steps: 

1 - First you shoud eliminate "return" statements in your controllers like this:

```php

use \ImanGhafoori\Terminator\Facades\Responder;

class AuthController {
    public function login(Request $request)
    {
           // 1 - Validate Request
           $validator = Validator::make($request->all(), [
              'email' => 'required|max:255||string',
              'password' => 'required|confirmed||string',
          ]);
          if ($validator->fails()) {
               $response = redirect('/some-where')->withErrors($validator)->withInput();
               respondeWith($response);
          }
          
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              $response = $this->sendLockoutResponse($request);
              respondeWith($response);
          }
          
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
               $response = $this->sendLoginResponse($request);
               respondeWith($response);
          }
          

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          $response = $this->sendFailedLoginResponse($request) 
         
          respondeWith($response);  // or use the Facade
    }
}

```

Do you see how "return" keyword is now turned into function calls ?!


2 - Now we have got rid of returns, it is possible to extract into methods like below:


```php
class LoginController
{
    public function Login(Request $request)
    {
        $this->validateRequest();         
        $this->throttleAttempts();       
        $this->handleValidCredentials();  
        $this->handleInvalidCredentials(); 
        
    }
    ...
}
```


You can use it like this:

```php
$response = response()->json($someData);

respondWith($response);


// or an alias function:

sendAndTerminate($response);

// or use facade :
\ImanGhafoori\Terminator\TerminatorFacade::sendAndTerminate($response);

```
**In fact sendAndTerminate() function can accept anything you normally return from a typical controller.**


### About Testibility:

Let me mention that the "sendAndTerminate or respondWith" helper functions (like other laravel helper functions) can be easily mocked out and does not affect the testibility at all.

In fact they make your application for testable, because your tests do not fail if you change the shape of your response.



### More from the author:

 :gem: A minimal yet powerful package to give you opportunity to refactor your controllers.

- https://github.com/imanghafoori1/laravel-anypass

------------------

 :gem: A minimal yet powerful package to give a better structure and caching opportunity for your laravel apps.

- https://github.com/imanghafoori1/laravel-widgetize


-------------------

 :gem: A simple package that lets you easily impersonate your users.

- https://github.com/imanghafoori1/laravel-MasterPass


