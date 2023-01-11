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
        foreach($products as $product){
            $assets = $product->getDigitalAssets()->get(0);
            $categories = $product->getCategories()->get(0);
            $datas[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'path' =>  $assets ? $assets->getPath() : '',
                'category' => $categories ? $categories->getName() : ''
            ];
        }

        return $this->render('ecommerce/index.html.twig', ['products' => $datas]);
    }

    #[Route(path: '/product-details', name: 'ecommerce_product_details')]
    public function productDetails(): Response
    {
        return $this->render('ecommerce/details.html.twig');
    }


}
