 # :fire:Laravel Terminator :fire:

## :gem: "Tell, don't ask principle" for your laravel controllers


### What this package is good for ?

Short answer : **This package helps you clean up your controller code in a way that you have never seen before**


<p align="left">
  <img src="https://user-images.githubusercontent.com/6961695/41775502-5406df86-7639-11e8-9211-3b618e0e4600.jpg" width="500" title="I kill nasty code">



[![Latest Stable Version](https://poser.pugx.org/imanghafoori/laravel-terminator/v/stable)](https://packagist.org/packages/imanghafoori/laravel-terminator)
[![Build Status](https://scrutinizer-ci.com/g/imanghafoori1/laravel-terminator/badges/build.png?b=master)](https://scrutinizer-ci.com/g/imanghafoori1/laravel-terminator/build-status/master)
<a href="https://scrutinizer-ci.com/g/imanghafoori1/laravel-terminator"><img src="https://img.shields.io/scrutinizer/g/imanghafoori1/laravel-terminator.svg?style=round-square" alt="Quality Score"></img></a>
[![License](https://poser.pugx.org/imanghafoori/laravel-terminator/license)](https://packagist.org/packages/imanghafoori/laravel-terminator)
</p>
**Made with :heart: for every laravel "Clean Coder"**

### Installation:

`
composer require imanghafoori/laravel-terminator
`

No need to add any service providers.

### Compatibility:

- Laravel +5.1 and above
- Php 7.0 and above


### When to use it?

#### Code smell: :nose:

- When you see that you have an endpoint from which you have to send back more than one type of response... Then this package is going to help you a lot.

#### Example:

Consider a typical login endpoint, It may return 5 type of responses in different cases:
- 1- User is already logged in, so redirect.
- 2- Successfull login
- 3- Invalid credentials error
- 4- Incorrect credentials error
- 5- Too many login attempts error


The fact that MVC frameworks force us to "return a response" from controllers prevents us from simplify controllers beyond a certain point.
So we decide to break that jail and bring ourselves freedom. 

The idea is : Any class in the application should be able to send back a response.


# Remember:

## Controllers Are Controllers, They Are Not Responders !!!

Controllers, "control" the execution flow of your code, and send commands to other objects, telling them what to do. Their responsibility is not returning a "response" back to the client. And this is the philosophy of terminator package.


Consider the code below:

```php
// BAD code : Too many conditions
// BAD code : In a single method
// BAD code : (@_@)   (?_?)
// (It is not that bad, since it is a simplified example)
class AuthController {
  public function login(Request $request)
  {
           
           $validator = Validator::make($request->all(), [
              'email' => 'required|max:255||string',
              'password' => 'required|confirmed||string',
          ]);
          
          if ($validator->fails()) {
              return redirect('/some-where')->withErrors($validator)->withInput(); // return response 1
          }
          
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              return $this->sendLockoutResponse($request);   // return response 2
          }
        
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
              return $this->sendLoginResponse($request);   // return response 3
          }
        

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          return $this->sendFailedLoginResponse($request); // return response 4
          
          
          //These if blocks can not be extracted out. Can they ?
  }
}

```
#### Problem :

With the current approach, this is as much as we can refactor at best.
Why? because the controllers are asking for response, they are not telling what to do.

We do not want many if conditions all within a single method, it makes the method hard to understand and reason about.


```php

// Good code
// Good code
// Good code

class LoginController
{
    public function Login(Request $request)
    {
        // Here we are telling what to do (not asking them)
        // No response, just commands, Nice ???
        
        $this->validateRequest();          // 1
        $this->throttleAttempts();         // 2
        $this->handleValidCredentials();   // 3 
        $this->handleInvalidCredentials(); // 4
        
    }
    
    // private functions may sit here
    
    ...
    
}
```

### Note: 

Using "**respondWith()**" does not prevent the normal execution flow of the framework to be interrupted.
All the middlewares and other normal termination process of the laravel will happen as normal. So it is production ready! :dolphin:


#### Refactoring Steps: :hammer:

1 - First, you should eliminate "return" statements in your controllers like this:

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
               respondWith($response);  // <-- look here
          }
          
         
          // 2 - throttle Attempts
          if ($this->hasTooManyLoginAttempts($request)) {
              $this->fireLockoutEvent($request);
              $response = $this->sendLockoutResponse($request);
              respondWith($response); // <-- look here "no return !!!"
          }
          
         
          // 3 - handle valid Credentials
          if ($this->attemptLogin($request)) {
               $response = $this->sendLoginResponse($request);
               respondWith($response);  // <-- look here  "no return !!!"
          }
          

          // 4 - handle invalid Credentials
          $this->incrementLoginAttempts($request);
          $response = $this->sendFailedLoginResponse($request) 
         
          respondWith($response);  // <-- look here "no return !!!"
    }
}

```

Do you see how "return" keyword is now turned into regular function calls ?!


2 - Now that we have got rid of return statements,then the rest is easy,
It is now possible to extract each if block into a method like below:


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

### Terminator API

All this package exposes for you is 2 global helper functions and 1 Facade:

- respondWith()
- sendAndTerminate()
- \ImanGhafoori\Terminator\TerminatorFacade::sendAndTerminate()

```php
$response = response()->json($someData);

respondWith($response);

// or 
respondWith()->json($someData);


// or an alias function for 'respondWith()' is 'sendAndTerminate':

sendAndTerminate($response);


// or use facade :
\ImanGhafoori\Terminator\TerminatorFacade::sendAndTerminate($response);

```
**In fact sendAndTerminate() ( or it's alias "respondWith" ) function can accept anything you normally return from a typical controller.**


### About Testibility:

Let me mention that the "sendAndTerminate or respondWith" helper functions (like other laravel helper functions) can be easily mocked out and does not affect the testibility at all.


```php
// Sample Mock
TerminatorFacade::shouldRecieve('sendAndTerminate')->once()->with($someResponse)->andReturn(true);
```

In fact they make your application for testable, because your tests do not fail if you change the shape of your response.


#### How The Magic Is Even Possible, Dude ?!

You may wonder how this magic is working behind the scenes. In short it uses nothing more than a standard laravel "renderable exception".

We highly encourage you to take a look at the simple source code of the package to find out what's going on there. It is only a few lines of code.


#### ❗️ Security

If you discover any security related issues, please email :e-mail: imanghafoori1@gmail.com instead of using the issue tracker.


#### ⭐️ Your Stars Make Us Do More ⭐️

As always if you found this package useful and you want to encourage us to maintain and work on it, Please press the star button to declare your willing.

### More from the author:


#### Laravel Hey Man

:gem: It allows to write expressive code to authorize, validate and authenticate.

- https://github.com/imanghafoori1/laravel-heyman

------------------

#### Laravel Any Pass

 :gem: A minimal package that helps you login with any password on local environments.

- https://github.com/imanghafoori1/laravel-anypass

------------------

#### Laravel Widgetize

 :gem: A minimal yet powerful package to give a better structure and caching opportunity for your laravel apps.

- https://github.com/imanghafoori1/laravel-widgetize


-------------------

#### Laravel Master Pass


 :gem: A simple package that lets you easily impersonate your users.

- https://github.com/imanghafoori1/laravel-MasterPass


