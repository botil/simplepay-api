<?php

namespace Simplepay\SimplepayApi\Models;

use PhpParser\Node\Expr\Array_;

class Payment
{
    public function __construct(array $products, Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->products = $products;
    }

    public $id;

    public $total;

    public $customer_email;

    public $language;

    public $invoice;

    public $products;

    public $currency;

}
