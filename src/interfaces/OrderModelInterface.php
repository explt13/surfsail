<?php

namespace Surfsail\interfaces;

interface OrderModelInterface
{
    public function saveOrder(array $user, array $currency);
}