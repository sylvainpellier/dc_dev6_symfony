<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OverrideAbstractController extends AbstractController
{
    public function render(string $view,  array $parameters = [], Response $response = null): Response
    {
        $cartRepository = $this->getDoctrine()->getManager()->getRepository(Cart::class);

        $session = new Session();

        if($idPanier = $session->get("idPanier"))
        {
            $cart = $cartRepository->find($idPanier);
            if($cart)
            {
                $parameters["nbCartProduct"]  = count($cart->getProducts());
                $parameters["productsInCart"]  = $cart->getProducts();
            } else
            {
                $parameters["nbCartProduct"]  = 0;
                $parameters["productsInCart"]  = [];
            }


        }


        return parent::render($view, $parameters, $response);
    }

}