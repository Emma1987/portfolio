<?php

namespace App\Entity\Trait;

use Gedmo\Mapping\Annotation as Gedmo;

trait LocaleTrait
{
    #[Gedmo\Locale]
    protected ?string $locale = null;

    public function setTranslatableLocale(string $locale): void
    {
        $this->locale = $locale;
    }
}
