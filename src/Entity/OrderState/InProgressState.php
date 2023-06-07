<?php

namespace App\Entity\OrderState;

use App\Entity\Order;

class InProgressState implements OrderStateInterface
{
    public function __construct(private Order $order)
    {
    }

    public function completeOrder()
    {
        $this->order->setState($this->order->getArchiveState());
    }
}