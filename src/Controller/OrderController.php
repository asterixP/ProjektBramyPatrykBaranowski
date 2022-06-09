<?php
namespace App\Controller;

use App\Order\Action\GetOrdersAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{
    private GetOrdersAction $getOrdersAction;

    /**
     * @param GetOrdersAction $getOrdersAction
     */
    public function __construct(GetOrdersAction $getOrdersAction)
    {
        $this->getOrdersAction = $getOrdersAction;
    }

    public function __invoke(): Response
    {
        $result = $this->getOrdersAction->__invoke();
        return $this->render('orders.html.twig', [
            'active_orders' => json_decode((string) $result->getContent(), true)
        ]);
    }
}