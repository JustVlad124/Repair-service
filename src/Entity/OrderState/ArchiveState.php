<?php

namespace App\Entity\OrderState;

use App\Entity\Order;

class ArchiveState implements OrderStateInterface
{
    public function __construct(private Order $order)
    {
    }

    public function completeOrder()
    {
        // TODO: Implement completeOrder() method.
    }
}