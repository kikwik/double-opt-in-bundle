<?php


namespace Kikwik\DoubleOptInBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class KikwikDoubleOptInExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $controller = $container->getDefinition('kikwik_double_opt_in.controller.double_opt_in_controller');
        $controller->setArgument('$removeSecretCodeAfterVerification',$config['remove_secret_code_after_verification']);

        $mailManager = $container->getDefinition('kikwik_double_opt_in.service.double_opt_in_mail_manager');
        $mailManager->setArgument('$senderEmail', $config['sender_email']);
        $mailManager->setArgument('$senderName', $config['sender_name']);
    }

}