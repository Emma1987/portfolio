<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'em_portfolio_root_redirect')]
    public function rootRedirect(Request $request): Response
    {
        $locale = $request->getLocale();
        $redirectLocale = in_array($locale, ['fr', 'en']) ? $locale : 'fr';

        return $this->redirectToRoute('em_portfolio_home', ['_locale' => $redirectLocale]);
    }
}
