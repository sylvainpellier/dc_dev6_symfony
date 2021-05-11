<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/products", name="product_")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/all", name="all")
     */
    public function all(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }


//use Symfony\Component\HttpFoundation\Request;
    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash("success","Le produit est bien ajouté");

            return $this->redirectToRoute("product_all");
        } else if($form->isSubmitted() && !$form->isValid())
        {
            $this->addFlash("danger","Le produit ne c'est pas bien ajouté");

        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(string $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);


        if(!$product)
        {
            $this->createNotFoundException('Ce produit n\'existe pas');
        } else {

            return $this->render('product/show.html.twig', [
                'product' => $product,
            ]);
        }
    }





}
