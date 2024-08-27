# Laravel Authentication Package

This package is a wrapper for [Laravel Sanctum](https://github.com/laravel/sanctum) package.
it introduces new concepts to authentication flow such as:

- [Authenticates](#authenticates)
- [Authenticators](#authenticator)
- [Channels](#channel)
- [Workers](#worker)
- [Rules](#rule)
- [Steps](#step)

## Requirements

- `php >= 8.2`
- `laravel/sanctum >= 4.0`

## Installation

```bash
composer require raid/guardian
```

## Configuration

Copy the config file to your own project by running the following command

```bash
php artisan vendor:publish --provider="Raid\Guardian\Providers\GuardianServiceProvider"
```

## Usage

Let's see basic usage for authentication with this package.

```php
class LoginController extends Controller
{
    public function __invoke(Request $request, UserAuthenticator $authenticator)
    {
        $channel = $authenticator->attempt($request->only([
            'email', 'password',
        ]));

        return response()->json([
            'channel' => $channel->getName(),
            'token' => $channel->getStringToken(),
            'resource' => $channel->getAuthenticatable(),
            'errors' => $channel->errors()->toArray(),
        ]);
    }
}
```

The `Authenticator` will handle the authentication process and return a
`Channel` instance that will contain the authentication information.

The `Authenticator` class defines the `Authenticates` class that will be 
used to find the user, also it defines the `Channels` that can be used to
authenticate the user.

The `Channel` class depends on `Workers` to find the authenticated user, 
Then it can run some `Rules` and `Steps` to fulfill the authentication process.

Let's start digging into the `Authenticates`, `Authenticators` and `Channels` classes.

## Authenticates

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

The `Authenticates` class must implement `Authenticates` interface.

The `Authenticates` class must define the `findForAuthentication` method.

The `findForAuthentication` method accepts two parameters: `$attribute` and `$value` passed from the given credentials.

The `findForAuthentication` method must return `Illuminate\Contracts\Auth\Authenticatable` instance if found.

## Authenticator

The `Authenticator` class will be used to define the `Authenticates` class and `Channels` to process authentication with different channels.

You can use this command to create a new authenticator class

```bash
php artisan raid:make-authenticator UserAuthenticator
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Authenticators;

use Raid\Guardian\Guardians\Guardian;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;

class UserAuthenticator extends Guardian implements GuardianInterface
{
    public const NAME = '';

    protected string $authenticates = '';

    protected array $channels = [];
}
```

Let's configure the `Authenticator` class.

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Models\User;
use App\Http\Authentication\Channels\SystemChannel;
use Raid\Guardian\Guardians\Guardian;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;

class UserAuthenticator extends Guardian implements GuardianInterface
{
    public const NAME = 'user';

    protected string $authenticates = User::class;

    protected array $channels = [
        SystemChannel::class,
    ];
}
```

The `Authenticator` class must implement `AuthenticatorInterface`.

The `Authenticator` class must extend the `Authenticator` class.

The `Authenticator` class should define the `name` constant.

The `Authenticator` class must define the `authenticates` property.

The `Authenticator` class should define the `channels` property.

The `Authenticator` class can handle authentication with any of its defined `Channels`.

You can define the `channels` with two ways:

- `channels` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Authenticators\UserAuthenticator;
use App\Http\Authentication\Channels\SystemChannel;

return [

    'authenticator_channels' => [
        UserAuthenticator::class => [
            SystemChannel::class,
        ],
    ],
];
```

This definition allows you to authenticate users with different `Channels` using channel name.

If you didn't pass any channel, the default channel will be used.

```php
<?php

class LoginController extends Controller
{
    public function __invoke(Request $request, UserAuthenticator $authenticator)
    {
        $credentials = $request->only([
            'email', 'password',
        ]);
        
        $channel = $authenticator->attempt($credentials, 'system');
    }
}
```

## Channel

The `Channel` class will be used to handle authentication process using the passed `Authenticates` class and `Credentials`.

You can use this command to create a new channel class

```bash
php artisan raid:make-channel SystemChannel
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Channels;

use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemChannel extends Authenticator implements AuthenticatorInterface
{
    public const NAME = '';
}
```

Let's configure the `Channel` class.

```php
<?php

namespace App\Http\Authentication\Channels;

use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemChannel extends Authenticator implements AuthenticatorInterface
{
    public const NAME = 'system';
}
```

The `Channel` class must implement `ChannelInterface`.

The `Channel` class must extend the `Channel` class.

The `Channel` class should define the `name` constant.

The `Channel` works through `Workers` to find the authenticated user,
It matches the defined `Workers` attribute with the given credentials.

```php
<?php

namespace App\Http\Authentication\Channels;

use App\Http\Authentication\Workers\EmailWorker;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemChannel extends Authenticator implements AuthenticatorInterface
{
    public const NAME = 'system';
    
    protected array $workers = [
        EmailWorker::class,
    ];
}
```

You can define the `workers` with two ways:

- `workers` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Channels\SystemChannel;
use App\Http\Authentication\Workers\EmailWorker;

return [

    'channel_workers' => [
        SystemChannel::class => [
            EmailWorker::class,
        ],
    ],
];
```

This definition allows you to authenticate users with different `Workers` using worker defined attribute.

## Worker

The `Worker` class will be used to find the authenticated user based on the given credentials.

You can use this command to create a new worker class

```bash
php artisan raid:make-worker EmailMatcher
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Workers;

use Raid\Guardian\Matchers\Matcher;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

class EmailWorker extends Matcher implements MatcherInterface
{
    public const ATTRIBUTE = '';
}
```

Let's configure the `Worker` class.

```php
<?php

namespace App\Http\Authentication\Workers;

use Raid\Guardian\Matchers\Matcher;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

class EmailWorker extends Matcher implements MatcherInterface
{
    public const ATTRIBUTE = 'email';
}
```

The `Worker` class must implement `WorkerInterface`.

The `Worker` class must extend the `Worker` class.

The `Worker` class must define the `attribute` constant.

The `Worker` can also define a `QUERY_ATTRIBUTE` constant to find the user.

The `Attribute` is used to match the `Worker` with the given credentials.

The `Query Attribute` is passed to the `findForAuthentication` method to find the user,
if not defined, it will use the `Attribute` constant instead.

## Rule

The `Rule` class will be used to validate the authentication.

To apply `Rules` you need to implement `ShouldRunRules` interface to the `Channel`,
Then you can define your `Rules`.

```php
<?php

namespace App\Http\Authentication\Channels;

use App\Http\Authentication\Rules\VerifiedRule;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunNorms;

class SystemChannel extends Authenticator implements AuthenticatorInterface, ShouldRunNorms
{
    public const NAME = 'system';
    
    protected array $rules = [
        VerifiedRule::class,    
    ];
}
```

You can define the `rules` with two ways:

- `rules` property
- `config\authentication.php` file

```php

use App\Http\Authentication\Channels\SystemChannel;
use App\Http\Authentication\Rules\VerifiedRule;

return [
   
    'channel_rules' => [
        SystemChannel::class => [
            VerifiedRule::class,
      ],
];
```

The `Rules` will be applied to the `Channel` to validate the authentication.

You can use this command to create a new rule class

```bash
php artisan raid:make-rule VerifiedRule
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Rules;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class VerifiedRule implements NormInterface
{
    public function handle(AuthenticatorInterface $channel): bool
    {
    }

    public function fail(AuthenticatorInterface $channel): void
    {
    }
}
```
Let's configure the `Rule` class.

```php
<?php

namespace App\Http\Authentication\Rules;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class VerifiedRule implements NormInterface
{
    public function handle(AuthenticatorInterface $channel): bool
    {
        return $channel->getAuthenticatable()->isVerified();
    }
    
    public function fail(AuthenticatorInterface $channel): void
    {
        $channel->fail(message: __('auth.unverified'));
    }
}
```

The `Rule` class must implement `RuleInterface`.

The `Rule` class must define the `handle` method.

The `handle` method must return a boolean value.

The `handle` method will be called by the `Channel` to validate the authentication.

## Step

The `Step` class will be used to add additional steps to the authentication process.

To apply `Steps` you need to implement `ShouldRunSteps` interface to the `Channel`,
Then you can define your `Steps`.

```php
<?php

namespace App\Http\Authentication\Channels;

use App\Http\Authentication\Steps\TwoFactorEmailStep;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunSequences;

class SystemChannel extends Authenticator implements AuthenticatorInterface, ShouldRunSequences
{
    public const NAME = 'system';
    
    protected array $steps = [
        TwoFactorEmailStep::class,
    ],
}
```

You can define the `steps` with two ways:

- `steps` property
- `config\authentication.php` file

```php
<?php

use App\Http\Authentication\Channels\SystemChannel;
use App\Http\Authentication\Steps\TwoFactorEmailStep;

return [
   
    'channel_steps' => [
        SystemChannel::class => [
            TwoFactorEmailStep::class,
      ],
];
```

The `Steps` will be applied to the `Channel` to add additional steps to the authentication process.

You can use this command to create a new step class

```bash
php artisan raid:make-step TwoFactorEmailStep
```

This will output the following code

```php
<?php

namespace App\Http\Authentication\Steps;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\SequenceInterface;

class TwoFactorEmailStep implements SequenceInterface
{
    public function handle(AuthenticatorInterface $channel): void
    {
    }
}
```

Let's configure the `Step` class.

```php
<?php

namespace App\Http\Authentication\Steps;

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

    public function handle(AuthenticatorInterface $channel): void
    {
        $code = generate_code();
        
        $authenticatable = $channel->getAuthenticatable();

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

The `Step` must implement `StepInterface`.

The `Step` class must define the `handle` method.

The `handle` method will be called by the `Channel` to add additional steps to the authentication process.

`hint:` Running any steps means that the `Channel` will stop the authentication process without issuing any tokens,
This approach can be used in `Multi-Factor` authentication.

You can configure your step class to work through queues.

```php
<?php

namespace App\Http\Authentication\Steps;

use App\Mail\TwoFactorMail;
use App\Core\Integrations\Mail\MailService;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\SequenceInterface;
use Raid\Guardian\Sequences\Contracts\QueueSequenceInterface;

class TwoFactorEmailStep implements SequenceInterface, QueueSequenceInterface
{
    use HasQueue;

    public function __construct(
        private readonly MailService $mailService,
    ) {

    }

    public function handle(AuthenticatorInterface $channel): void
    {
        $code = generate_code();
        
        $authenticatable = $channel->getAuthenticatable();

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

The queue step must implement `ShouldRunQueue`

The `ShouldRunQueue` class must define the `queue` method.

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

### Channel Errors

You can use the `Channel` class to handle authentication errors through `errors` method.

You can add errors to channel using these methods:

```php
$channel->fail('key', 'message');
// or
$channel->errors()->add('key', 'message');
```

You can check the channel errors using these methods:

```php
$hasErrors = $channel->failed();
//or
$hasErrors = $channel->errors()->any();

$hasError = $channel->errors()->has('key');

$errorsByKey = $channel->errors()->get('key');

$firstError = $channel->errors()->first();

$lastError = $channel->errors()->last();

$errorsAsArray = $channel->errors()->toArray();

$errorsAsJson = $channel->errors()->toJson();
```

And that's it.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- **[Mohamed Khedr](https://github.com/MohamedKhedr700)**

## Security

If you discover any security-related issues, please email
instead of using the issue tracker.

## About Raid

Raid is a PHP framework created by **[Mohamed Khedr](https://github.com/MohamedKhedr700)**,
and it is maintained by **[Mohamed Khedr](https://github.com/MohamedKhedr700)**.

## Support Raid

Raid is an MIT-licensed open-source project. It's an independent project with its ongoing development made possible.