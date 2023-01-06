<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/eboutique')]
class ECommerceController extends AbstractController
{
    #[Route(path: '/', name: 'ecommerce_index')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $products = [1,2,3,4,5,6,7,8,9,10];
        return $this->render('ecommerce/index.html.twig', ['products' => $products]);
    }

    #[Route(path: '/product-details', name: 'ecommerce_product_details')]
    public function productDetails(): Response
    {
        return $this->render('ecommerce/details.html.twig');
    }


}
