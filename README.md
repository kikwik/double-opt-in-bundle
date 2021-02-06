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

Configure the symfony/mailer component in your `.env` file:

```
MAILER_DSN=sendmail+smtp://localhost
```

Configuration
-------------

Import double opt in check routes in `config/routes.yaml`:

```yaml
kikwik_double_opt_in_bundle:
  resource: '@KikwikDoubleOptInBundle/Resources/config/routes.xml'
  prefix: '/double-opt-in'
```

Copy translations file from `vendor/kikwik/double-opt-in-bundle/src/Resources/translations/KikwikDoubleOptInBundle.xx.yaml`
to `translations/KikwikDoubleOptInBundle.xx.yaml` and change at least the `double_opt_in.email.sender` value

```yaml
double_opt_in:
    title: Verifica email
    success: La tua email è stata verificata con successo
    danger: Il codice di verifica non è stato trovato, forse è già stato usato?
    email:
        sender:  'no-reply@example.com'
        subject: 'Conferma la tua email'
        content: |
            <p>
                <a href="{{ confirm_url }}">Clicca qui per confermare la tua email</a><br/>
                oppure incolla in seguente link nella barra degli indirizzi del browser: <br/>{{ confirm_url }}
            </p>
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