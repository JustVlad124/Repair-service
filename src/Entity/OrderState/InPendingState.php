<?php

namespace App\Entity\OrderState;

use App\Entity\Order;

class InPendingState implements OrderStateInterface
{
    public function __construct(private Order $order)
    {
    }

    public function completeOrder(): \Exception
    {
        return new \Exception('Cannot complete order in pending state.');
    }
}