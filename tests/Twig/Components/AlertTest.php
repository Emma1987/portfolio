<?php

namespace App\Tests\Twig\Components;

use App\Twig\Components\Alert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\UX\TwigComponent\Test\InteractsWithTwigComponents;

class AlertTest extends KernelTestCase
{
    use InteractsWithTwigComponents;

    public function testComponentMount(): void
    {
        $component = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'message' => 'Everything is fine. This is totally not on fire. 🔥',
            ],
        );

        $this->assertInstanceOf(Alert::class, $component);
        $this->assertSame('success', $component->type);
        $this->assertSame('Everything is fine. This is totally not on fire. 🔥', $component->message);
        $this->assertFalse($component->autoDismiss);

        $otherComponent = $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'type' => 'danger',
                'message' => 'An error occurred. But it works on my machine.',
                'autoDismiss' => true,
            ],
        );

        $this->assertInstanceOf(Alert::class, $otherComponent);
        $this->assertSame('danger', $otherComponent->type);
        $this->assertSame('An error occurred. But it works on my machine.', $otherComponent->message);
        $this->assertTrue($otherComponent->autoDismiss);
    }

    public function testComponentRenders(): void
    {
        $rendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'message' => 'Everything is fine. This is totally not on fire. 🔥',
            ],
        );

        $this->assertStringContainsString('Everything is fine. This is totally not on fire. 🔥', (string) $rendered);
        $this->assertCount(1, $rendered->crawler()->filter('.alert.alert-success'));

        $otherRendered = $this->renderTwigComponent(
            name: Alert::class,
            data: [
                'type' => 'danger',
                'message' => 'An error occurred. But it works on my machine.',
            ],
        );

        $this->assertStringContainsString('An error occurred. But it works on my machine.', (string) $otherRendered);
        $this->assertCount(1, $otherRendered->crawler()->filter('.alert.alert-danger'));
    }

    public function testInvalidTypeThrowsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "type" with value "funny" is invalid.');

        $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'type' => 'funny',
                'message' => 'Success: Your changes have been deleted.',
                'autoDismiss' => true,
            ],
        );
    }

    public function testInvalidAutoDismissThrowsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "autoDismiss" with value "Why not?" is expected to be of type "bool", but is of type "string".');

        $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'type' => 'danger',
                'message' => 'Success: Your changes have been deleted.',
                'autoDismiss' => 'Why not?',
            ],
        );
    }

    public function testMissingMessageThrowsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "message" is missing.');

        $this->mountTwigComponent(
            name: Alert::class,
            data: [
                'type' => 'success',
                'autoDismiss' => true,
            ],
        );
    }
}
