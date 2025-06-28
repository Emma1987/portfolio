<?php

namespace App\Entity\DTO;

use Gedmo\Translatable\Entity\Translation;

class TranslationDTO
{
    public string $entity;
    public string $class;
    public string $field;
    public string $foreignKey;
    /** @var Translation[] */
    public array $translations = [];
}
