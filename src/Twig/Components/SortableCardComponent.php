<?php

namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('sortable_card')]
class SortableCardComponent
{
    public string $title;
    public string $color;
    public FormView $form;
    public array $list;
    public string $formFieldName;
    public string $sortRoute;
    public string $deleteRouteName;
    public array $translations;
}
