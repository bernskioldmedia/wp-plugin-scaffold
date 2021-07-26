<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Commands;

use WPPS_Vendor\Symfony\Component\Console\Input\InputInterface;
use WPPS_Vendor\Symfony\Component\Console\Input\InputOption;
use function WPPS_Vendor\Symfony\Component\String\u;
class FieldGroupCommand extends MakeCommand
{
    protected static $basePath = '/src/Fields/';
    protected static $defaultName = 'make:fieldgroup';
    protected static $stubName = 'fieldgroup';
    protected function configure()
    {
        parent::configure();
        $this->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, 'The root plugin namespace.', 'NAMESPACE');
    }
    protected function getReplacements(InputInterface $input) : array
    {
        $name = $input->getArgument('name');
        return ['{{ namespace }}' => $input->getOption('namespace') . '\\Customizer', '{{ class }}' => u($name)->camel()->title()->toString()];
    }
}
