<?php

namespace Weirdan\PsalmPluginSkeleton;

use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface
{
    /** @return void */
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        // This is plugin entry point. You can initialize things you need here,
        // and hook them into psalm using RegistrationInterface
        //
        // Here's some examples:
        // 1. Add a stub file
        // ```php
        // $psalm->addStubFile(__DIR__ . '/stubs/YourStub.php');
        // ```
        foreach ($this->getStubFiles() as $file) {
            $psalm->addStubFile($file);
        }

        // Psalm allows arbitrary content to be stored under you plugin entry in
        // its config file, psalm.xml, so your plugin users can put some configuration
        // values there. They will be provided to your plugin entry point in $config
        // parameter, as a SimpleXmlElement object. If there's no configuration present,
        // null will be passed instead.
    }

    /** @return list<string> */
    private function getStubFiles(): array
    {
        return glob(__DIR__ . '/stubs/*.phpstub') ?: [];
    }
}
