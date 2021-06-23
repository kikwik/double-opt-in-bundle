KikwikDoubleOptInBundle
=======================

Double opt-in management for Doctrine 2 entities in symfony 4.4 and 5.x


Installation
------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kikwik/double-opt-in-bundle
```

Configuration
-------------

Configure the symfony/mailer component in your `.env` file:

```dotenv
MAILER_DSN=sendmail+smtp://localhost
```

Import double opt in check routes in `config/routes.yaml`:

```yaml
kikwik_double_opt_in_bundle:
  resource: '@KikwikDoubleOptInBundle/Resources/config/routes.xml'
  prefix: '/double-opt-in'
```

Create the `config/packages/kikwik_double_opt_in.yaml` config file, set the `sender_email` parameter

```yaml
kikwik_double_opt_in:
    sender_email: '%env(SENDER_EMAIL)%'
    sender_name: '%env(SENDER_NAME)%'
    remove_secret_code_after_verification: true
```

and define it in your .env file

```dotenv
SENDER_EMAIL=no-reply@example.com
SENDER_NAME=My Company Name
```

Implements `DoubleOptInInterface` to your classes or use the `DoubleOptInTrait`:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInInterface;
use Kikwik\DoubleOptInBundle\Model\DoubleOptInTrait;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, DoubleOptInInterface
{
    use DoubleOptInTrait;

    //...
}
```

Make migrations and update your database

```console
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Usage
-----

Every time you persist a new entity which implements DoubleOptInInterface a confirmation email is sent.
If you want to disable the confirmation email (useful when importing data) just call `disableDoubleOptIn` before persisting your entity

```php
$user = new User();
$user->setEmail('test@example.com');
$user->setPassword('secret');

$user->disableDoubleOptIn();

$em->persist($user);
$em->flush();
```

To resend the confirmation email autowire the `DoubleOptInMailManager` service and call `sendEmail` (be sure that `$doubleOptInSecretCode` field is not empty)

```php
/**
 * @Route("/resend-email/{id}", name="app_resend_email")
 */
public function resendConfirmationEmail(User $user, DoubleOptInMailManager $doubleOptInMailManager)
{
    if($user->getDoubleOptInSecretCode())
    {
        $doubleOptInMailManager->sendEmail($user);
    }
    return $this->redirectToRoute('app_home');
}
```

Customization
-------------

Copy translations file from `vendor/kikwik/double-opt-in-bundle/src/Resources/translations/KikwikDoubleOptInBundle.xx.yaml`
to `translations/KikwikDoubleOptInBundle.xx.yaml` and make changes here.

```yaml
double_opt_in:
    title: Verifica email
    success: La tua email è stata verificata con successo
    danger: Il codice di verifica non è stato trovato, forse è già stato usato?
    email:
        subject: 'Conferma la tua email'
        content: |
            <p>
                <a href="{{ confirm_url }}">Clicca qui per confermare la tua email</a><br/>
                oppure incolla in seguente link nella barra degli indirizzi del browser: <br/>{{ confirm_url }}
            </p>
```


EventListener
-------------

After a successful double-opt-in confirmation the bundle will dispatch the `DoubleOptInVerifiedEvent` custom event, 
so you can listen for it and do your stuff.

```php
// src/EventSubscriber/AfterDoubleOptInEventSubscriber.php
namespace App\EventSubscriber;

use Kikwik\DoubleOptInBundle\Event\DoubleOptInVerifiedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AfterDoubleOptInEventSubscriber implements EventSubscriberInterface
{
    public function onDoubleOptInVerified(DoubleOptInVerifiedEvent $event)
    {
        $object = $event->getObject();

        // Do something with the $object, which is verified
    }

    public static function getSubscribedEvents()
    {
        return [
            'kikwik.double_opt_in.verified' => 'onDoubleOptInVerified',
        ];
    }
}
```
