KikwikDoubleOptInBundle
=======================

Double opt-in management for Doctrine 2 entities in symfony 4


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

```
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
```

and define it in your .env file

```dotenv
SENDER_EMAIL=no-reply@example.com
```

Implements `DoubleOptInInterface` to your classes and use the `DoubleOptInTrait`:

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
