<?php

/**
 * Stripe Checkout Session Request.
 */

namespace Omnipay\Stripe\Message\Checkout;

/**
 * Stripe Checkout Session Request
 *
 * @see \Omnipay\Stripe\Gateway
 * @link https://stripe.com/docs/api/checkout/sessions
 */
class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return [];
    }

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/checkout/sessions/' . $this->getTransactionReference();
    }
}
