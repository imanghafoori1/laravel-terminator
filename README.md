# Tell, don't ask principle in your laravel controllers

## What this package is good for ?

**This package helps you refactor your controllers code by bringing The "law of demter" into it.**



### Installation:

`
composer require ImanGhafoori/laravel-terminator
`



The fact that we have to "return" a response from controllers prevents framework users from refactoring their controller code beyond a certain point.

Consider the code below inspired from a trait in laravel source code.

```php

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
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              return $this->sendLockoutResponse($request);
          }
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
              return $this->sendLoginResponse($request);
          }

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          return $this->sendFailedLoginResponse($request);
  }
}

```
#### Problem :

With the current approach, this is as much as we can refactor at best.
These if blocks can not be easily extracted out.

Why ? because the controllers are asking for response, they are not telling what to do.

#### Solution : 

With this technique you can write it like this:

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
               sendAndTerminate($response);
          }
          
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              $response = $this->sendLockoutResponse($request);
              sendAndTerminate($response);
          }
          
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
               $response = $this->sendLoginResponse($request);
               sendAndTerminate($response);
          }
          

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          $response = $this->sendFailedLoginResponse($request) 
         
          respondeWith($response);  // or use the Facade
    }
}

```
Do you see how "return" keyword is now turned into function calls ?!
Now we have got rid of returns, it is possible to extract into methods like below:


```php
class LoginController
{
    public function Login(Request $request)
    {
        // Here we are telling what to do (not asking them)
        // What can be more readable than this ?
        $this->validateRequest();          // 1
        $this->throttleAttempts();         // 2
        $this->handleValidCredentials();   // 3 
        $this->handleInvalidCredentials(); // 4
        
    }
    
    // private functions may sit here
    
    
    ...
    
}
```


### Usage:

you can use it like this:

```php
$response = response()->json($someData);

respondWith($response);

// or use facade :

\ImanGhafoori\Terminator\TerminatorFacade::sendAndTerminate($response);

```
**In fact sendAndTerminate() function can accept anything you normally return from a typical controller.**


### About Testibility:
Let me mention that The "sendAndTerminate" helper function (like other laravel helper functions) can be easily mocked out and does not affect the testibility at all.



### More of the Author

**If you are looking for more new ways of refactoring your controllers visit the link below**

https://github.com/ImanGhafoori1/laravel-widgetize


