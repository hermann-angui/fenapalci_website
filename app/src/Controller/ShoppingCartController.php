<?php

namespace App\Controller;

use App\Entity\DigitalAsset;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/shoppingcart')]
class ShoppingCartController extends AbstractController
{
    #[Route('/', name: 'shoppingcart_index', methods: ['GET'])]
    public function index(OrderRepository $productRepository): Response
    {
        return $this->render('shoppingcart/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'shoppingcart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrderRepository $productRepository): Response
    {
        return $this->render('shoppingcart/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'shoppingcart_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('shoppingcart/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'shoppingcart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        return $this->render('shoppingcart/edit.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}', name: 'shoppingcart_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('shoppingcart_index', [], Response::HTTP_SEE_OTHER);
    }
}
