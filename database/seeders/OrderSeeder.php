<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];

        $orders = [
            [
                'customer_name' => 'John Smith',
                'customer_email' => 'john.smith@example.com',
                'customer_phone' => '555-0101',
                'customer_address' => '123 Main Street',
                'customer_city' => 'New York',
                'customer_state' => 'NY',
                'customer_zip' => '10001',
                'customer_country' => 'USA',
                'order_status' => 'pending',
                'payment_status' => 'paid',
                'subtotal' => 450.00,
                'shipping_cost' => 0,
                'total' => 450.00,
                'notes' => 'Please leave at front door.',
                'items' => [
                    ['product_name' => 'Mountain Bike Pro', 'product_price' => 350.00, 'quantity' => 1],
                    ['product_name' => 'Bike Helmet', 'product_price' => 50.00, 'quantity' => 2],
                ],
            ],
            [
                'customer_name' => 'Sarah Johnson',
                'customer_email' => 'sarah.j@example.com',
                'customer_phone' => '555-0102',
                'customer_address' => '456 Oak Avenue',
                'customer_city' => 'Los Angeles',
                'customer_state' => 'CA',
                'customer_zip' => '90001',
                'customer_country' => 'USA',
                'order_status' => 'processing',
                'payment_status' => 'paid',
                'subtotal' => 120.00,
                'shipping_cost' => 10.00,
                'total' => 130.00,
                'notes' => null,
                'items' => [
                    ['product_name' => 'Road Bike Elite', 'product_price' => 120.00, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'Mike Davis',
                'customer_email' => 'mike.davis@example.com',
                'customer_phone' => '555-0103',
                'customer_address' => '789 Pine Road',
                'customer_city' => 'Chicago',
                'customer_state' => 'IL',
                'customer_zip' => '60601',
                'customer_country' => 'USA',
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'subtotal' => 89.99,
                'shipping_cost' => 10.00,
                'total' => 99.99,
                'notes' => 'Gift wrap please.',
                'items' => [
                    ['product_name' => 'Hybrid Bike Comfort', 'product_price' => 89.99, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'Emily Chen',
                'customer_email' => 'emily.chen@example.com',
                'customer_phone' => '555-0104',
                'customer_address' => '321 Elm Street',
                'customer_city' => 'Houston',
                'customer_state' => 'TX',
                'customer_zip' => '77001',
                'customer_country' => 'USA',
                'order_status' => 'cancelled',
                'payment_status' => 'failed',
                'subtotal' => 275.00,
                'shipping_cost' => 0,
                'total' => 275.00,
                'notes' => 'Customer requested cancellation.',
                'items' => [
                    ['product_name' => 'Electric Bike E-Bolt', 'product_price' => 275.00, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'David Wilson',
                'customer_email' => 'david.w@example.com',
                'customer_phone' => '555-0105',
                'customer_address' => '654 Maple Drive',
                'customer_city' => 'Phoenix',
                'customer_state' => 'AZ',
                'customer_zip' => '85001',
                'customer_country' => 'USA',
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => 580.00,
                'shipping_cost' => 0,
                'total' => 580.00,
                'archived_at' => now()->subDays(5),
                'notes' => null,
                'items' => [
                    ['product_name' => 'Mountain Bike Pro', 'product_price' => 350.00, 'quantity' => 1],
                    ['product_name' => 'Bike Lock Premium', 'product_price' => 45.00, 'quantity' => 1],
                    ['product_name' => 'Water Bottle Holder', 'product_price' => 15.00, 'quantity' => 1],
                    ['product_name' => 'Bike Lights Set', 'product_price' => 35.00, 'quantity' => 1],
                    ['product_name' => 'Pump Portable', 'product_price' => 25.00, 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);
            $orderData['user_id'] = $user?->id;

            $order = Order::create($orderData);

            foreach ($items as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $itemData['product_name'],
                    'product_price' => $itemData['product_price'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['product_price'] * $itemData['quantity'],
                ]);
            }
        }
    }
}
