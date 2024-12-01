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

---

### **Driver**

Handles token generation for authenticated users.

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