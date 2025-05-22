<?php

namespace App\Service;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    private string $publicKey;
    private string $secretKey;
    private string $appUrl;

    public function __construct(string $publicKey, string $secretKey, string $appUrl)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->appUrl = rtrim($appUrl, '/');
        Stripe::setApiKey($this->secretKey);
    }

    // public function createCheckoutSession(array $lineItems, int $orderId): Session
    // {
    //     return Session::create([
    //         'payment_method_types' => ['card'],
    //         'line_items' => $lineItems,
    //         'mode' => 'payment',
    //         'success_url' => 'http://127.0.0.1:8000/payment/success/' . $orderId, 
    //         'cancel_url' => 'http://127.0.0.1:8000/payment/cancel',
    //     ]);
    
    public function createCheckoutSession(array $lineItems): ?Session
    {
        try {
            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->appUrl . '/payment/success',
                'cancel_url' => $this->appUrl . '/payment/cancel',
            ]);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return null;
        }
    }   
}
