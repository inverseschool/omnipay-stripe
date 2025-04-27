<?php

/**
 * Stripe Refund Request.
 */

namespace Omnipay\Stripe\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Stripe Refund Request.
 *
 * When you create a new refund, you must specify a
 * charge to create it on.
 *
 * Creating a new refund will refund a charge that has
 * previously been created but not yet refunded. Funds will
 * be refunded to the credit or debit card that was originally
 * charged. The fees you were originally charged are also
 * refunded.
 *
 * You can optionally refund only part of a charge. You can
 * do so as many times as you wish until the entire charge
 * has been refunded.
 *
 * Once entirely refunded, a charge can't be refunded again.
 * This method will return an error when called on an
 * already-refunded charge, or when trying to refund more
 * money than is left on a charge.
 *
 * Example -- note this example assumes that the purchase has been successful
 * and that the transaction ID returned from the purchase is held in $sale_id.
 * See PurchaseRequest for the first part of this example transaction:
 *
 * <code>
 *   // Do a refund transaction on the gateway
 *   $transaction = $gateway->refund(array(
 *       'amount'                   => '10.00',
 *       'transactionReference'     => $sale_id,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Refund transaction was successful!\n";
 *       $refund_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $refund_id . "\n";
 *   }
 * </code>
 *
 * @see PurchaseRequest
 * @see \Omnipay\Stripe\Gateway
 * @link https://stripe.com/docs/api#create_refund
 */
class RefundRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->endpoint . '/refunds';
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getRefundApplicationFee()
    {
        return $this->getParameter('refundApplicationFee');
    }

    public function setRefundApplicationFee($value): RefundRequest
    {
        return $this->setParameter('refundApplicationFee', $value);
    }

    public function getReverseTransfer()
    {
        return $this->getParameter('reverseTransfer');
    }

    public function setReverseTransfer($value): RefundRequest
    {
        return $this->setParameter('reverseTransfer', $value);
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionReference');

        $data = [];

        $data['charge'] = $this->getTransactionReference();

        if ($this->getAmountInteger()) {
            $data['amount'] = $this->getAmountInteger();
        }

        if ($this->getRefundApplicationFee()) {
            $data['refund_application_fee'] = 'true';
        }

        if ($this->getReverseTransfer()) {
            $data['reverse_transfer'] = 'true';
        }

        return $data;
    }
    protected function createResponse($data, $headers = []): RefundResponse
    {
        return $this->response = new RefundResponse($this, $data, $headers);
    }
}
