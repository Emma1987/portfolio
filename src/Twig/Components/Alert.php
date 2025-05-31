<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
class Alert
{
    public string $type = 'success';
    public string $message;
    public bool $autoDismiss = false;

    /**
     * @param array<string, mixed> $data
     *
     * @return array{type: 'success'|'danger', message: string, autoDismiss: bool}
     */
    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefaults([
                'type' => 'success',
                'autoDismiss' => false,
            ])
            ->setAllowedValues('type', ['success', 'danger'])
            ->setRequired('message')
            ->setAllowedTypes('message', 'string')
            ->setAllowedTypes('autoDismiss', 'bool')
        ;

        return $resolver->resolve($data);
    }
}
