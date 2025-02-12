<?php

namespace Cspray\Phinal;

use Attribute;

/** @api */
#[Attribute(Attribute::TARGET_CLASS)]
final class AllowInheritance
{
    public function __construct(public string $why)
    {
    }
}
