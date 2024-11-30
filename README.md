# Laravel Authentication Package

This package is a wrapper for any authentication package to provide a unified authentication flow.
it introduces new concepts to authentication flow such as:

- [Authenticatable](#authenticatable)
- [Guardians](#guardian)
- [Authenticators](#authenticator)
- [Matchers](#matcher)
- [Norms](#norm)
- [Sequences](#sequence)
- [Drivers](#driver)

## Requirements

- `php >= 8.2`

## Installation

```bash
composer require raid/guardian
```

## Configuration

Publish the config file to your own project by running the following command

```bash
php artisan vendor:publish --provider="Raid\Guardian\Providers\GuardianServiceProvider"
```

## Usage

Let's see basic usage for authentication with this package.

```php
class LoginController extends Controller
{
    public function __invoke(Request $request, UserGuardian $guardian)
    {
        $authenticator = $guardian->attempt($request->only([
            'email', 'password',
        ]));

        return response()->json([
            'authenticator' => $authenticator->getName(),
            'token' => $authenticator->getStringToken(),
            'resource' => $authenticator->getAuthenticatable(),
            'errors' => $authenticator->errors()->toArray(),
        ]);
    }
}
```

The `Guardian` will handle the authentication process and return a
`Authenticator` instance that will contain the authentication information.

The `Guardian` class defines the `Authenticatable` class that will be 
used to find the user, also it defines the `Authenticators` that can be used to
authenticate the user.

The `Authenticator` class depends on `Matchers` to find the authenticated user, 
Then it can run some `Norms` and `Sequences` to fulfill the authentication process.

Let's start digging into the `Authenticatable`, `Guardian` and `Authenticator` classes.

## Authenticatable

The `Authenticates` class will be used to find the user,
and return `Illuminate\Contracts\Auth\Authenticatable` instance if found.

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as IlluminateUser;
use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;

class User extends IlluminateUser implements AuthenticatableInterface
{
    use HasApiTokens;

    public function findForAuthentication(string $attribute, mixed $value): ?AuthenticatableInterface
    {
        return $this->where($attribute, $value)->first();
    }
}
```

The `Authenticatable` class must implement `AuthenticatableInterface` interface.

The `Authenticatable` class must define the `findForAuthentication` method.

The `findForAuthentication` method accepts two parameters: `$attribute` and `$value` passed from the given credentials.

The `findForAuthentication` method must return `Illuminate\Contracts\Auth\Authenticatable` instance if found.

## Guardian

The `Guardian` class will be used to define the `Authenticatable` class and `Authenticators` to process authentication with different flow.

You can use this command to create a new guardian class

```bash
php artisan raid:make-guardian UserGuardian
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Guardians;

use Raid\Guardian\Guardians\Guardian;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;

class UserGuardian extends Guardian implements GuardianInterface
{
    public const NAME = '';

    protected string $authenticatable;

    protected array $authenticators = [];
}
```

Let's configure the `Guardian` class.

```php
<?php

namespace App\Http\Authentication\Guardians;

use App\Models\User;
use App\Http\Authentication\Guardians\SystemAuthenticator;
use Raid\Guardian\Guardians\Guardian;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;

class UserGuardian extends Guardian implements GuardianInterface
{
    public const NAME = 'user';

    protected string $authenticatable = User::class;

    protected array $authenticators = [
        SystemAuthenticator::class,
    ];
}
```

The `Guardian` class must implement `GuardianInterface`.

The `Guardian` class must extend the `Guardian` class.

The `Guardian` class should define the `name` constant.

The `Guardian` class must define the `authenticatable` property.

The `Guardian` class should define the `authenticators` property.

The `Guardian` class can handle authentication with any of its defined `authenticators`.

You can define the `authenticators` with two ways:

- `authenticators` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Guardians\UserGuardian;
use App\Http\Authentication\Authenticators\SystemAuthenticator;

return [

    'guardian_authenticators' => [
        UserGuardian::class => [
            SystemAuthenticator::class,
        ],
    ],
];
```

This definition allows you to authenticate users with different `Authenticators` using authenticator name.

If you didn't pass any authenticator, the default authenticator will be used.

```php
<?php

class LoginController extends Controller
{
    public function __invoke(Request $request, UserGuardian $guardian)
    {
        $credentials = $request->only([
            'email', 'password',
        ]);
        
        $authenticator = $guardian->attempt($credentials, 'system');
    }
}
```

## Authenticator

The `Authenticator` class will be used to handle authentication process using the passed `Authenticatable` class and `Credentials`.

You can use this command to create a new authenticator class

```bash
php artisan raid:make-authenticator SystemAuthenticator
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Authenticators;

use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    public const NAME = '';
}
```

Let's configure the `Authenticator` class.

```php
<?php

namespace App\Http\Authentication\Authenticators;

use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    public const NAME = 'system';
}
```

The `Authenticator` class must implement `AuthenticatorInterface`.

The `Authenticator` class must extend the `Authenticator` class.

The `Authenticator` class should define the `name` constant.

The `Authenticator` works through `Matchers` to find the authenticated user,
It matches the defined `Matchers` attribute with the given credentials.

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Matchers\EmailMatcher;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    public const NAME = 'system';
    
    protected array $matchers = [
        EmailMatcher::class,
    ];
}
```

You can define the `matchers` with two ways:

- `matchers` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Authenticators\SystemAuthenticator;
use App\Http\Authentication\Matchers\EmailMatcher;

return [

    'authenticator_matchers' => [
        SystemAuthenticator::class => [
            EmailMatcher::class,
        ],
    ],
];
```

This definition allows you to authenticate users with different `Matchers` using the defined attribute.

## Matcher

The `Matcher` class will be used to find the authenticated user based on the given credentials.

You can use this command to create a new matcher class

```bash
php artisan raid:make-matcher EmailMatcher
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Matchers;

use Raid\Guardian\Matchers\Matcher;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

class EmailMatcher extends Matcher implements MatcherInterface
{
    public const ATTRIBUTE = '';
}
```

Let's configure the `Matcher` class.

```php
<?php

namespace App\Http\Authentication\Matchers;

use Raid\Guardian\Matchers\Matcher;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

class EmailMatcher extends Matcher implements MatcherInterface
{
    public const ATTRIBUTE = 'email';
}
```

The `Matcher` class must implement `MatcherInterface`.

The `Matcher` class must extend the `Matcher` class.

The `Matcher` class must define the `ATTRIBUTE` constant.

The `Matcher` can also define a `QUERY_ATTRIBUTE` constant to find the user.

The `ATTRIBUTE` is used to match the `Matcher` with the given credentials.

The `QUERY_ATTRIBUTE` is passed to the `findForAuthentication` method to find the user,
if not defined, it will use the `ATTRIBUTE` constant instead.

## Norm

The `Norm` class will be used to validate the authentication.

To apply `Norms` you need to implement `ShouldRunNorms` interface to the `Authenticator` class,
Then you can define your `Norms`.

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Norms\VerifiedNorm;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunNorms;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface, ShouldRunNorms
{
    public const NAME = 'system';
    
    protected array $norms = [
        VerifiedNorm::class,    
    ];
}
```

You can define the `norms` with two ways:

- `norms` property
- `config\authentication.php` file

```php

use App\Http\Authentication\Authenticators\SystemAuthenticator;
use App\Http\Authentication\Norms\VerifiedNorm;

return [
   
    'authenticators_norms' => [
        SystemAuthenticator::class => [
            VerifiedNorm::class,
      ],
];
```

The `Norms` will be applied to the `Authenticator` to validate the authentication.

You can use this command to create a new rule class

```bash
php artisan raid:make-norm VerifiedNorm
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Norms;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class VerifiedNorm implements NormInterface
{
    public function handle(AuthenticatorInterface $authenticator): bool
    {
    }

    public function fail(AuthenticatorInterface $authenticator): void
    {
    }
}
```
Let's configure the `Norm` class.

```php
<?php

namespace App\Http\Authentication\Norms;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class VerifiedNorm implements NormInterface
{
    public function handle(AuthenticatorInterface $authenticator): bool
    {
        return $channel->getAuthenticatable()->isVerified();
    }
    
    public function fail(AuthenticatorInterface $authenticator): void
    {
        $channel->fail(message: __('auth.unverified'));
    }
}
```

The `Norm` class must implement `NormInterface`.

The `Norm` class must define the `handle` method.

The `handle` method must return a boolean value.

The `handle` method will be called by the `Authenticator` to validate the authentication.

## Sequence

The `Sequence` class will be used to add additional steps to the authentication process.

To apply `Sequences` you need to implement `ShouldRunSequences` interface to the `Authenticator` class,
Then you can define your `Sequences`.

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Sequences\TwoFactorEmailSequence;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunSequences;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface, ShouldRunSequences
{
    public const NAME = 'system';
    
    protected array $sequences = [
        TwoFactorEmailSequence::class,
    ],
}
```

You can define the `sequences` with two ways:

- `sequences` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Authenticators\SystemAuthenticator;
use App\Http\Authentication\Sequences\TwoFactorEmailSequence;

return [
   
    'authenticators_sequences' => [
        SystemAuthenticator::class => [
            TwoFactorEmailSequence::class,
      ],
];
```

The `Sequences` will be applied to the `Authenticator` to add additional sequences to the authentication process.

You can use this command to create a new step class

```bash
php artisan raid:make-sequence TwoFactorEmailSequence
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Sequences;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\SequenceInterface;

class TwoFactorEmailSequence implements SequenceInterface
{
    public function handle(AuthenticatorInterface $authenticator): void
    {
    }
}
```

Let's configure the `Sequence` class.

```php
<?php

namespace App\Http\Authentication\Sequences;

use App\Core\Integrations\Mail\MailService;
use App\Mail\TwoFactorMail;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\SequenceInterface;

class TwoFactorEmailStep implements SequenceInterface
{
    public function __construct(
        private readonly MailService $mailService,
    ) {

    }

    public function handle(AuthenticatorInterface $authenticator): void
    {
        $code = generate_code();
        
        $authenticatable = $authenticator->getAuthenticatable();

        $authenticatable->update([
            'two_factor_email_code' => $code,
        ]);
        
        $this->send(
            $authenticatable->getAttribute('email'),
            $authenticatable->getAttribute('name'),
            $code,
        );
    }

    private function send(string $email, string $name, int $code): void
    {
        $this->mailService->send(
            $email,
            new TwoFactorMail($name, $code),
        );
    }
}
```

The `Sequence` must implement `SequenceInterface`.

The `Sequence` class must define the `handle` method.

The `handle` method will be called by the `Authenticator` to add additional sequences to the authentication process.

`hint:` Running any steps means that the `Authenticator` will stop the authentication process without issuing any tokens,
This approach can be used in `Multi-Factor` authentication.

You can configure your sequence class to work through queues.

```php
<?php

namespace App\Http\Authentication\Sequences;

use App\Mail\TwoFactorMail;
use App\Core\Integrations\Mail\MailService;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\QueueSequenceInterface;

class TwoFactorEmailSequence implements QueueSequenceInterface
{
    use HasQueue;

    public function __construct(
        private readonly MailService $mailService,
    ) {

    }

    public function handle(AuthenticatorInterface $authenticator): void
    {
        $code = generate_code();
        
        $authenticatable = $authenticator->getAuthenticatable();

        $authenticatable->update([
            'two_factor_email_code' => $code,
        ]);
        
        $this->send(
            $authenticatable->getAttribute('email'),
            $authenticatable->getAttribute('name'),
            $code,
        );
    }

    private function send(string $email, string $name, int $code): void
    {
        $this->mailService->send(
            $email,
            new TwoFactorMail($name, $code),
        );
    }
}
```

The queue sequence must implement `QueueSequenceInterface`.

The queue sequence class must define the `queue` method.

You can use the `HasQueue` trait to define the `queue` method with its default configuration.

You can override the trait queue configurations by defining these methods:

```php
protected function getJob(): string
{
    // return your Job class;
}

protected function getConnection(): ?string
{
    // return your Connection name;
}

protected function getQueue(): ?string
{
    // return your Queue name;
}

protected function getDelay(): DateInterval|DateTimeInterface|int|null
{
    // return your Delay interval;
}
```

## Driver

The Driver class is responsible for handling the generation of tokens depending on the authentication package used.

There are two types of drivers available:
- `JwtDriver`
- `SanctumDriver`

You can define the default driver in the `config\guardian.php` file.

```php
<?php

use Raid\Guardian\Drivers\SanctumDriver;

return [

    'default_driver' => SanctumDriver::class,
];
```

You can create your own Driver using this command

```bash
php artisan raid:make-driver JwtDriver
```

This will output the following code

```php
<?php

namespace Raid\Guardian\Drivers;

use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Drivers\Contracts\DriverInterface;

class JwtDriver implements DriverInterface
{
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string
    {
    }
}
```

The `Driver` must implement `DriverInterface`.

The `Driver` class must define the `generateToken` method.

The `generateToken` method will be called by the `Authenticator` to generate a token.

### Authenticator Errors

You can use the `Authenticator` class to handle authentication errors through `errors` method.

You can add errors to an authenticator using these methods:

```php
$authenticator->fail('key', 'message');
// or
$authenticator->errors()->add('key', 'message');
```

You can check the authenticator errors using these methods:

```php
$hasErrors = $authenticator->failed();
//or
$hasErrors = $authenticator->errors()->any();

$hasError = $authenticator->errors()->has('key');

$errorsByKey = $authenticator->errors()->get('key');

$firstError = $authenticator->errors()->first();

$lastError = $authenticator->errors()->last();

$errorsAsArray = $authenticator->errors()->toArray();

$errorsAsJson = $authenticator->errors()->toJson();
```

And that's it.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- **[Mohamed Khedr](https://github.com/dev-khedr)**

## Security

If you discover any security-related issues, please email
instead of using the issue tracker.

## About Raid

Raid is a PHP framework created by **[Mohamed Khedr](https://github.com/dev-khedr)**,
and it is maintained by **[Mohamed Khedr](https://github.com/dev-khedr)**.

## Support Raid

Raid is an MIT-licensed open-source project. It's an independent project with its ongoing development made possible.