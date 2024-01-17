<?php

namespace PayU\PaymentGateway\Plugin\Block\Widget\Button;

use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\ToolbarInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
use PayU\PaymentGateway\Model\Ui\CardConfigProvider;
use PayU\PaymentGateway\Model\Ui\ConfigProvider;

class ToolbarPlugin
{
    /**
     * Before push button aceept payment and deny payment action key
     */
    const KEY_ACTION_ONCLICK = 'onclick';

    /**
     * Url action key
     */
    const URL_ACTION_KEY = 'action';

    /**
     * Base review payment url
     */
    const REVIEW_PAYMENT_URL = 'payu/data/reviewpayment';

    /**
     * Before Push Button Plugin change url for accept payment and deny payment for PayU payment methods
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforePushButtons(ToolbarInterface $subject, AbstractBlock $context, ButtonList $buttonList): void
    {
        if ($context instanceof OrderView) {
            $paymentMethod = $context->getOrder()->getPayment()->getMethod();
            if ($paymentMethod === CardConfigProvider::CODE || $paymentMethod === ConfigProvider::CODE) {
                $acceptMessage = __('Are you sure you want to accept this payment?');
                $denyMessage = __('Are you sure you want to deny this payment?');
                $denyUrl = $context->getUrl(static::REVIEW_PAYMENT_URL, [static::URL_ACTION_KEY => 'deny']);
                $acceptUrl = $context->getUrl(static::REVIEW_PAYMENT_URL, [static::URL_ACTION_KEY => 'accept']);
                $buttonList->update(
                    'accept_payment',
                    static::KEY_ACTION_ONCLICK,
                    "confirmSetLocation('{$acceptMessage}', '{$acceptUrl}')"
                );
                $buttonList->update(
                    'deny_payment',
                    static::KEY_ACTION_ONCLICK,
                    "confirmSetLocation('{$denyMessage}', '{$denyUrl}')"
                );
            }
        }
    }
}
