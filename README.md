# **Laravel Authentication Package**

This package provides a unified authentication flow by acting as a wrapper around any authentication package. It introduces several new concepts:

- [Authenticatable](#authenticatable)
- [Guardians](#guardian)
- [Authenticators](#authenticator)
- [Matchers](#matcher)
- [Norms](#norm)
- [Sequences](#sequence)
- [Drivers](#driver)

---

## **Requirements**

- PHP `>= 8.2`
- An installed authentication package (e.g., Laravel Sanctum, Laravel Passport, or any other compatible package)

This package acts as a wrapper and requires an existing authentication package to provide the underlying authentication mechanisms.

---

## **Installation**

Install the package via Composer:

```bash
composer require raid/guardian
```

---

## **Configuration**

Publish the configuration file with the following command:

```bash
php artisan vendor:publish --provider="Raid\Guardian\Providers\GuardianServiceProvider"
```

---

## **Usage**

Here is an example of using the package in a controller:

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
            'token' => $authenticator->getToken(),
            'resource' => $authenticator->getAuthenticatable()->toArray(),
            'errors' => $authenticator->errors()->toArray(),
        ]);
    }
}
```

### **Key Components:**

- The `Guardian` manages the authentication process and returns an `Authenticator` instance.
- The `Authenticator` relies on `Matchers`, runs `Norms`, and executes `Sequences` for successful authentication.

---

## **Core Components**

### **Authenticatable**

The `Authenticatable` class is responsible for locating a user and returning an `Illuminate\Contracts\Auth\Authenticatable` instance.

#### **Example Implementation:**

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;

class User extends Authenticatable implements AuthenticatableInterface
{
    public function findForAuthentication(string $attribute, mixed $value): ?AuthenticatableInterface
    {
        return $this->where($attribute, $value)->first();
    }
}
```

#### **Requirements:**

- Implement `AuthenticatableInterface`.
- Define the `findForAuthentication` method.

---

### **Guardian**

The `Guardian` class orchestrates the `Authenticatable` and `Authenticators` during authentication.

#### **Command to Generate:**

```bash
php artisan raid:make-guardian UserGuardian
```

#### **Configuration Example:**

```php
<?php

namespace App\Http\Authentication\Guardians;

use App\Models\User;
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

---

### **Authenticator**

Manages the core authentication logic.

#### **Command to Generate:**

```bash
php artisan raid:make-authenticator SystemAuthenticator
```

#### **Configuration Example:**

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
### Defining Authenticators

Authenticators can be defined in the guardian class or via the configuration file. See the [Configuration Section](#components-configuration) for details.

---

### **Matcher**

Matches the user with given credentials.

#### **Command to Generate:**

```bash
php artisan raid:make-matcher EmailMatcher
```

#### **Configuration Example:**

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

### Defining Matchers

Matchers can be defined in the authenticator class or via the configuration file. See the [Configuration Section](#components-configuration) for details.

---

### **Norm**

Defines rules to validate authentication.

#### **Command to Generate:**

```bash
php artisan raid:make-norm VerifiedNorm
```

#### **Configuration Example:**

```php
<?php

namespace App\Http\Authentication\Norms;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class VerifiedNorm implements NormInterface
{
    public function handle(AuthenticatorInterface $authenticator): bool
    {
        return $authenticator->getAuthenticatable()->isVerified();
    }
    
    public function fail(AuthenticatorInterface $authenticator): void
    {
        $authenticator->fail(message: __('auth.unverified'));
    }
}
```

### Defining Norms

Norms can be defined in the authenticator class or via the configuration file. See the [Configuration Section](#components-configuration) for details.

---

### **Sequence**

Adds additional steps to the authentication process.

#### **Command to Generate:**

```bash
php artisan raid:make-sequence TwoFactorEmailSequence
```

#### **Configuration Example:**

```php
<?php

namespace App\Http\Authentication\Sequences;

use App\Core\Integrations\Mail\MailService;
use App\Mail\TwoFactorMail;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Sequences\Contracts\SequenceInterface;

class TwoFactorEmailSequence implements SequenceInterface
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
        
        $this->mailService->send(
            $authenticatable->getAttribute('email'),
            new TwoFactorMail($authenticatable->getAttribute('name'), $code),
        );
    }
}
```

### Defining Sequences

Sequences can be defined in the authenticator class or via the configuration file. See the [Configuration Section](#components-configuration) for details.

---

### **Driver**

Handles token generation for authenticated users depending on the authentication package.

#### **Driver Types:**
- `PassportDriver`
- `JwtDriver`
- `SanctumDriver`

#### **Setting the Default Driver:**

Update the configuration in `config\guardian.php`:

```php
<?php

use Raid\Guardian\Drivers\SanctumDriver;

return [

    'default_driver' => SanctumDriver::class,
];
```

#### **Creating a Custom Driver:**

```bash
php artisan raid:make-driver CustomDriver
```

---

### **Error Handling**

Manage errors using the `errors()` method:

```php
$authenticator->fail('key', 'message');
$authenticator->errors()->toArray();
```

---

## **Components Configuration**

### **Defining Authenticators**

Authenticators manages the core authentication logic.

You can configure the authenticators for a Guardian in two ways:

**1. Using a Property in the Guardian Class**

Define the list of authenticators directly in your custom Guardian class:

```php
<?php

namespace App\Http\Authentication\Guardians;

use App\Http\Authentication\Authenticators\SystemAuthenticator;
use Raid\Guardian\Guardians\Guardian;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;

class UserGuardian extends Guardian implements GuardianInterface
{
    protected array $authenticators = [
        SystemAuthenticator::class,
    ];

    protected string $defaultAuthenticator = SystemAuthenticator::class; // Optional: Set the default authenticator
}
```

**2. Using the Configuration File**

Define authenticators in the `config/guardian.php` file under `guardian_authenticators`:

```php
<?php

use App\Http\Authentication\Authenticators\SystemAuthenticator;
use App\Http\Authentication\Guardians\UserGuardian;

return [

    'guardian_authenticators' => [
        UserGuardian::class => [
            SystemAuthenticator::class,
        ],
    ],
];
```

**Default Authenticator**

To set a default authenticator for a Guardian:
- **In the Guardian Class:** Use the $defaultAuthenticator property.
- **In the Configuration File:** Use guardian_authenticators key.

If no default authenticator is specified, the Guardian will use the default authenticator configured in  `config/guardian.php` under `default_authenticator`.



### **Defining Matchers**

Matchers are used to locate the user based on credentials. You can define matchers in two ways:

**1. Using a Property in the Authenticator Class**

In your custom authenticator class, define the `matchers` property:

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Matchers\EmailMatcher;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    protected array $matchers = [
        EmailMatcher::class,
    ];
}
```

**2. Using the Configuration File**

You can also define matchers in the `config/guardian.php` file under `authenticator_matchers`:

```php
<?php

use App\Http\Authentication\Matchers\EmailMatcher;
use App\Http\Authentication\Authenticators\SystemAuthenticator;

return [

    'authenticator_matchers' => [
        SystemAuthenticator::class => [
            EmailMatcher::class,
        ],
    ],
];
```

### **Defining Norms**

Norms validate the authentication process. Similar to matchers, norms can be defined in two ways:

**1. Using a Property in the Authenticator Class**

In your custom authenticator class, define the `norms` property:

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Norms\VerifiedNorm;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    protected array $norms = [
        VerifiedNorm::class,
    ];
}
```

**2. Using the Configuration File**

Define norms in the `config/guardian.php` file under `authenticator_norms`:

```php
<?php

use App\Http\Authentication\Norms\VerifiedNorm;
use App\Http\Authentication\Authenticators\SystemAuthenticator;

return [

    'authenticator_norms' => [
        SystemAuthenticator::class => [
            VerifiedNorm::class,
        ],
    ],

];
```

### **Defining Sequences**

Sequences add extra steps to the authentication process. Like matchers and norms, they can be defined in two ways:

**1. Using a Property in the Authenticator Class**

In your custom authenticator class, define the `sequences` property:

```php
<?php

namespace App\Http\Authentication\Authenticators;

use App\Http\Authentication\Sequences\TwoFactorEmailSequence;
use Raid\Guardian\Authenticators\Authenticator;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

class SystemAuthenticator extends Authenticator implements AuthenticatorInterface
{
    protected array $sequences = [
        TwoFactorEmailSequence::class,
    ];
}
```
**2. Using the Configuration File**

Define sequences in the `config/guardian.php` file under `authenticator_sequences`:

```php
<?php

use App\Http\Authentication\Sequences\TwoFactorEmailSequence;
use App\Http\Authentication\Authenticators\SystemAuthenticator;

return [

    'authenticator_sequences' => [
        SystemAuthenticator::class => [
            TwoFactorEmailSequence::class,
        ],
    ],

];
```

**Summary**
- **Property in Authenticator Class:** Encapsulation of matchers, norms, and sequences within the authenticator class.
- **Configuration File:** Centralized configuration for easier management across multiple authenticators.

This flexibility allows you to structure your application according to your needs.

---

## **Contributing**

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## **License**

MIT License. See the [LICENSE](LICENSE) file for details.

--- 

## **Credits**

- [Mohamed Khedr](https://github.com/dev-khedr)

---

## **Support Raid**

Raid is an MIT-licensed open-source project developed with community contributions.