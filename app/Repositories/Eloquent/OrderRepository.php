<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    protected $model = Order::class;

    private $order;

    public function __construct()
    {
        parent::__construct();
        $this->order = $this->model;
    }
}
