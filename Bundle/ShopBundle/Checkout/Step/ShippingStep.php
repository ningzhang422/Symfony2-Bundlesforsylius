<?php

/*
 * Override Core/Checkout/Shippingstep
 */

namespace Acme\Bundle\ShopBundle\Checkout\Step;

use Sylius\Bundle\AddressingBundle\Model\ZoneInterface;
use Sylius\Bundle\CoreBundle\Checkout\SyliusCheckoutEvents;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;
use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Symfony\Component\Form\FormInterface;



class ShippingStep extends CheckoutStep
{
    /**
     * @var null|ZoneInterface
     */
    private $zone;

    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $order = $this->getCurrentCart();
        $this->dispatchCheckoutEvent(SyliusCheckoutEvents::SHIPPING_INITIALIZE, $order);

        $form = $this->createCheckoutShippingForm($order);

        if (null === $this->zone) {
            return $this->proceed($context->getPreviousStep()->getName());
        }

        return $this->renderStep($context, $order, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $request = $this->getRequest();

        $order = $this->getCurrentCart();
        $this->dispatchCheckoutEvent(SyliusCheckoutEvents::SHIPPING_INITIALIZE, $order);

        $form = $this->createCheckoutShippingForm($order);



        if ($request->isMethod('POST') && $form->submit($request)->isValid()) {
     
        	
            $this->dispatchCheckoutEvent(SyliusCheckoutEvents::SHIPPING_PRE_COMPLETE, $order);

            $this->getManager()->persist($order);
            $this->getManager()->flush();

            $this->dispatchCheckoutEvent(SyliusCheckoutEvents::SHIPPING_COMPLETE, $order);

            return $this->complete();
        }

        return $this->renderStep($context, $order, $form);
    }

    protected function renderStep(ProcessContextInterface $context, OrderInterface $order, FormInterface $form)
    {
        return $this->render('AcmeShopBundle:Checkout/Step:shipping.html.twig', array(
            'order'   => $order,
            'form'    => $form->createView(),
            'context' => $context,
        ));
    }

    protected function createCheckoutShippingForm(OrderInterface $order)
    {
        $this->zone = $this->getZoneMatcher()->match($order->getShippingAddress());

        if (null === $this->zone) {
            $this->get('session')->getFlashBag()->add('error', 'sylius.checkout.shipping.error');
        }

        return $this->createForm('sylius_checkout_shipping', $order, array(
            'criteria' => array('zone' => $this->zone)
        ));
    }
}
