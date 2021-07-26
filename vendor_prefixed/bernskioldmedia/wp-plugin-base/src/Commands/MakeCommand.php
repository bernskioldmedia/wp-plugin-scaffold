<?php

namespace WPPS_Vendor\BernskioldMedia\WP\PluginBase\Commands;

use WPPS_Vendor\Symfony\Component\Console\Command\Command;
use WPPS_Vendor\Symfony\Component\Console\Input\InputArgument;
use WPPS_Vendor\Symfony\Component\Console\Input\InputInterface;
use WPPS_Vendor\Symfony\Component\Console\Output\OutputInterface;
abstract class MakeCommand extends Command
{
    protected static $basePath = '/';
    protected static $stubName;
    public function __construct()
    {
        parent::__construct();
    }
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED);
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateFile($input);
        return 0;
    }
    protected function getReplacements(InputInterface $input) : array
    {
        return [];
    }
    protected function generateFile($input)
    {
        $name = $input->getArgument('name');
        $stubFile = $this->getStubFileContents();
        $generatedFile = $this->replaceVariablesIn($stubFile, $this->getReplacements($input));
        $outputPath = $this->getOutputPath($name);
        if (!\is_dir($outputPath)) {
            \mkdir($outputPath);
        }
        $this->createFile($name, $generatedFile);
    }
    protected function getStubFileContents() : string
    {
        $fileName = $this->getStubPath() . '/' . static::$stubName . '.stub';
        if (\file_exists($fileName)) {
            return \file_get_contents($fileName);
        }
        return '';
    }
    protected function replaceVariablesIn(string $string, array $variables = []) : string
    {
        $output = $string;
        foreach ($variables as $variable => $value) {
            $output = \str_replace($variable, $value, $output);
        }
        return $output;
    }
    protected function createFile(string $fileName, string $contents) : void
    {
        $path = $this->getOutputPath($fileName) . $fileName . '.php';
        \file_put_contents($path, $contents);
    }
    protected function getOutputPath(string $name) : string
    {
        $path = \explode('/', $name);
        // Remove the final name from the path.
        unset($path[\array_pop($path)]);
        // Put path back together.
        $path = \implode('/', $path);
        return \getcwd() . static::$basePath . $path;
    }
    protected function getStubPath() : string
    {
        return __DIR__ . '/../../stubs';
    }
}
