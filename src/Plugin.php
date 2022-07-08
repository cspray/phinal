<?php

namespace Cspray\Phinal;

use PhpParser\Node;
use Psalm\Codebase;
use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeAnalysisEvent;
use Psalm\StatementsSource;
use Psalm\Storage\ClassLikeStorage;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

class Plugin implements PluginEntryPointInterface, AfterClassLikeAnalysisInterface
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
        $psalm->registerHooksFromClass($this::class);
    }

    /** @return list<string> */
    private function getStubFiles(): array
    {
        return glob(__DIR__ . '/stubs/*.phpstub') ?: [];
    }

    public static function afterStatementAnalysis(AfterClassLikeAnalysisEvent $event)
    {
        $stmt = $event->getStmt();
        if (!$stmt instanceof Node\Stmt\Class_ || $stmt->isAbstract() || $stmt->isFinal() || $stmt->isAnonymous()) {
            return;
        }


        $attributes = $stmt->attrGroups;
        foreach ($attributes as $attribute) {
            foreach ($attribute->attrs as $attr) {
                if (str_contains($attr->name->toString(), 'AllowInheritance')) {
                    return;
                }
            }
        }

        $class = (string) $stmt->name?->toString();
        IssueBuffer::accepts(
            new ClassNotFinal(
                sprintf('%s has not been marked as final nor is marked for inheritance.', $class),
                new CodeLocation($event->getStatementsSource(), $event->getStmt(), single_line: true)
            )
        );

        return null;
    }
}
