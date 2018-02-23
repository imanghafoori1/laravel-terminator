# Tell, don't ask principle in your laravel controllers


### Installation:

`
composer require imanghafoori/laravel-responder
`


This package helps you refactor your controllers code by bringing The law of demter into it.


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
With the current approach, this is as much as we can refactor at best.
These if blocks can not be easily extracted out.
Why ? because the controllers are asking for response they are not telling.


Ideally we want to extract smaller methods and reach some thing like:

```php
class LoginController
{
    public function Login(Request $request)
    {
        // Here we are telling what to do (not asking them)
        // What can be more readable than this ?
        $this->validateRequest();
        $this->throttleAttempts();
        $this->handleValidCredentials();
        $this->handleInvalidCredentials();
        
    }

    private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|max:255||string',
            'password' => 'required|confirmed||string',
        ]);
        if ($validator->fails()) {
            sendAndTerminate(redirect('/some-where')->withErrors($validator)->withInput());
        }
    }

    private function throttleAttempts()
    {
        if ($this->hasTooManyLoginAttempts(request())) {
            $this->fireLockoutEvent(request());
            sendAndTerminate($this->lockoutResponse(request()));
        }
    }

    private function handleValidCredentials()
    {
        if ($this->attemptLogin(request())) {
            sendAndTerminate($this->loginResponse(request()));
        }
    }

    private function handleInvalidCredentials()
    {
        $this->incrementLoginAttempts(request());
        sendAndTerminate($this->failedLoginResponse(request()));
    }
    
}
```


Now we can clean it even more.
But let's stop here.


### About Testibility:
Let me mention that The "sendAndTerminate" helper function (like other laravel helper functions) can be easily mocked out and does not affect the testibility at all.


Throwing exception can also some how create something like this.
But throwing exception and register handlers for them can easily become confusing since they create a lot of parallel execution flows.
