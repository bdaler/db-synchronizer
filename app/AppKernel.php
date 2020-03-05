<?php


namespace DBSynchronizer\App;


/**
 * Class AppKernel
 *
 * author: BDaler ( dalerkbtut@gmail.com )
 * @date: 01.03.2020 13:02
 * @package DBSynchronizerqwe\App
 */

use DBSynchronizer\App\ParameterBagNested;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class AppKernel extends Application
{
    /** @var ContainerInterface $container */
    protected $container;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->buildContainer();
    }

    protected function buildContainer(): void
    {
        $parameterBag = new ParameterBagNested();
        $this->container = new ContainerBuilder($parameterBag);
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__ . '/../'));
        $loader->load('config.yml');
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}