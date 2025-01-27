<?php

namespace Cspray\Phinal;

use Attribute;

/** @psalm-suppress UnusedClass */
#[Attribute(Attribute::TARGET_CLASS)]
final class AllowInheritance
{
    public function __construct(public string $why)
    {
    }
}
