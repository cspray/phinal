<?php

namespace Cspray\Phinal;

use PhpParser\Node;
use Psalm\CodeLocation;
use Psalm\IssueBuffer;
use Psalm\Plugin\EventHandler\AfterClassLikeAnalysisInterface;
use Psalm\Plugin\EventHandler\Event\AfterClassLikeAnalysisEvent;
use SimpleXMLElement;
use Psalm\Plugin\PluginEntryPointInterface;
use Psalm\Plugin\RegistrationInterface;

/** @psalm-suppress UnusedClass */
final class Plugin implements PluginEntryPointInterface, AfterClassLikeAnalysisInterface
{
    #[\Override]
    public function __invoke(RegistrationInterface $psalm, ?SimpleXMLElement $config = null): void
    {
        // Psalm allows arbitrary content to be stored under you plugin entry in
        // its config file, psalm.xml, so your plugin users can put some configuration
        // values there. They will be provided to your plugin entry point in $config
        // parameter, as a SimpleXmlElement object. If there's no configuration present,
        // null will be passed instead.
        $psalm->registerHooksFromClass($this::class);
    }

    #[\Override]
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
