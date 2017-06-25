<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * ProjectServiceContainer.
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * @final since Symfony 3.3
 */
class ProjectServiceContainer extends Container
{
    private $parameters;
    private $targetDirs = array();

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->services = array();
        $this->methodMap = array(
            'bar_service' => 'getBarServiceService',
            'baz_service' => 'getBazServiceService',
            'foo_service' => 'getFooServiceService',
            'translator.loader_1' => 'getTranslator_Loader1Service',
            'translator.loader_2' => 'getTranslator_Loader2Service',
            'translator.loader_3' => 'getTranslator_Loader3Service',
            'translator_1' => 'getTranslator1Service',
            'translator_2' => 'getTranslator2Service',
            'translator_3' => 'getTranslator3Service',
        );
        $this->privates = array(
            'baz_service' => true,
        );

        $this->aliases = array();
    }

    /**
     * {@inheritdoc}
     */
    public function compile()
    {
        throw new LogicException('You cannot compile a dumped container that was already compiled.');
    }

    /**
     * {@inheritdoc}
     */
    public function isCompiled()
    {
        return true;
    }

    /**
     * Gets the 'bar_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \stdClass A stdClass instance
     */
    protected function getBarServiceService()
    {
        return $this->services['bar_service'] = new \stdClass(($this->services['baz_service'] ?? $this->getBazServiceService()));
    }

    /**
     * Gets the 'foo_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator A Symfony\Component\DependencyInjection\ServiceLocator instance
     */
    protected function getFooServiceService()
    {
        return $this->services['foo_service'] = new \Symfony\Component\DependencyInjection\ServiceLocator(array('bar' => function () {
            return ($this->services['bar_service'] ?? $this->get('bar_service'));
        }, 'baz' => function (): \stdClass {
            return ($this->services['baz_service'] ?? $this->getBazServiceService());
        }, 'nil' => function () {
            return NULL;
        }));
    }

    /**
     * Gets the 'translator.loader_1' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \stdClass A stdClass instance
     */
    protected function getTranslator_Loader1Service()
    {
        return $this->services['translator.loader_1'] = new \stdClass();
    }

    /**
     * Gets the 'translator.loader_2' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \stdClass A stdClass instance
     */
    protected function getTranslator_Loader2Service()
    {
        return $this->services['translator.loader_2'] = new \stdClass();
    }

    /**
     * Gets the 'translator.loader_3' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \stdClass A stdClass instance
     */
    protected function getTranslator_Loader3Service()
    {
        return $this->services['translator.loader_3'] = new \stdClass();
    }

    /**
     * Gets the 'translator_1' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator A Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator instance
     */
    protected function getTranslator1Service()
    {
        return $this->services['translator_1'] = new \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator(new \Symfony\Component\DependencyInjection\ServiceLocator(array('translator.loader_1' => function () {
            return ($this->services['translator.loader_1'] ?? $this->get('translator.loader_1'));
        })));
    }

    /**
     * Gets the 'translator_2' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator A Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator instance
     */
    protected function getTranslator2Service()
    {
        $this->services['translator_2'] = $instance = new \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator(new \Symfony\Component\DependencyInjection\ServiceLocator(array('translator.loader_2' => function () {
            return ($this->services['translator.loader_2'] ?? $this->get('translator.loader_2'));
        })));

        $instance->addResource('db', ($this->services['translator.loader_2'] ?? $this->get('translator.loader_2')), 'nl');

        return $instance;
    }

    /**
     * Gets the 'translator_3' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator A Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator instance
     */
    protected function getTranslator3Service()
    {
        $a = ($this->services['translator.loader_3'] ?? $this->get('translator.loader_3'));

        $this->services['translator_3'] = $instance = new \Symfony\Component\DependencyInjection\Tests\Fixtures\StubbedTranslator(new \Symfony\Component\DependencyInjection\ServiceLocator(array('translator.loader_3' => function () {
            return ($this->services['translator.loader_3'] ?? $this->get('translator.loader_3'));
        })));

        $instance->addResource('db', $a, 'nl');
        $instance->addResource('db', $a, 'en');

        return $instance;
    }

    /**
     * Gets the 'baz_service' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * This service is private.
     * If you want to be able to request this service from the container directly,
     * make it public, otherwise you might end up with broken code.
     *
     * @return \stdClass A stdClass instance
     */
    protected function getBazServiceService()
    {
        return $this->services['baz_service'] = new \stdClass();
    }
}
