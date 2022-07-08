<?php

namespace Cspray\Phinal;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class AllowInheritance
{
    public function __construct(public string $why)
    {
    }
}
