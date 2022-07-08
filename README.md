# Phinal

Do you like final? _Really_ like it? Then enforce it on all your classes! When you have to fallback to inheritance you can do so by explicitly marking your class with an Attribute and explaining why it should be inherited.

## Installation

```
composer require --dev cspray/phinal
vendor/bin/psalm-plugin enable cspray/phinal
```

## Code Examples

Good! :+1:

```php
<?php declare(strict_types=1);

use Cspray\Phinal\AllowInheritance;

final class ProperlyMarkedFinal {}

#[AllowInheritance('Explain why you would need to inherit this.')]
class YouCanInherite
```

Bad! :-1:

```php
<?php declare(strict_types=1);

class NotMarkedFinal {}
```

Why do you need to inherit this? You probably don't need to and should mark it final!