<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShippingGatewayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wsdl', TextType::class, [
                'label' => 'bitbag.ui.dhl_wsdl',
            ])
            ->add('login', TextType::class, [
                'label' => 'bitbag.ui.dhl_login',
            ])
            ->add('password', TextType::class, [
                'label' => 'bitbag.ui.dhl_password',
            ])
            ->add('billing_account_number', TextType::class, [
                'label' => 'bitbag.ui.billing_account_number', //SAP
            ])
            ->add('shipping_payment_type', ChoiceType::class, [
                'label' => 'bitbag.ui.shipping_payment_type',
                'choices' => [
                    'dhl.ui.shipper' => 'SHIPPER',
                    'dhl.ui.receiver' => 'RECEIVER',
                    'dhl.ui.user' => 'USER',
                ],
            ])
            ->add('payment_type', ChoiceType::class, [
                'label' => 'dhl.ui.payment_type',
                'choices' => [
                    'dhl.ui.bank_transfer' => 'BANK_TRANSFER',
                    'dhl.ui.cash' => 'CASH',
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'bitbag.ui.country',
            ])
            ->add('name', TextType::class, [
                'label' => 'bitbag.ui.name',
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'bitbag.ui.postal_code',
            ])
            ->add('city', TextType::class, [
                'label' => 'bitbag.ui.city',
            ])
            ->add('street', TextType::class, [
                'label' => 'bitbag.ui.street',
            ])
            ->add('house_number', TextType::class, [
                'label' => 'bitbag.ui.house_number',
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'bitbag.ui.phone_number',
            ])
            ->add('drop_off_type', ChoiceType::class, [
                'label' => 'bitbag.ui.drop_off_type',
                'choices' => [
                    'bitbag.ui.request_courier' => 'REQUEST_COURIER',
                    'bitbag.ui.courier_only' => 'COURIER_ONLY',
                    'bitbag.ui.regular_pickup' => 'REGULAR_PICKUP',
                ],
            ])
            ->add('service_type', ChoiceType::class, [
                'label' => 'bitbag.ui.service_type',
                'choices' => [
                    'bitbag.ui.AH' => 'AH',
                    'bitbag.ui.domestic_09' => '09',
                    'bitbag.ui.domestic_12' => '12',
                    'bitbag.ui.EK' => 'EK',
                    'bitbag.ui.PI' => 'PI',
                ],
            ])
            ->add('label_type', ChoiceType::class, [
                'label' => 'bitbag.ui.label_type',
                'choices' => [
                    'bitbag.ui.lp' => 'LP',
                    'bitbag.ui.blp' => 'BLP',
                    'bitbag.ui.lblp' => 'LBLP',
                    'bitbag.ui.zblp' => 'ZBLP',
                ],
            ])
            ->add('package_type', ChoiceType::class, [
                'label' => 'bitbag.ui.type',
                'choices' => [
                    'bitbag.ui.package' => 'PACKAGE',
                    'bitbag.ui.envelope' => 'ENVELOPE',
                    'bitbag.ui.pallet' => 'PALLET',
                ],
            ])
            ->add('shipment_start_hour', TextType::class, [
                'label' => 'bitbag.ui.shipment_start_hour',
            ])
            ->add('shipment_end_hour', TextType::class, [
                'label' => 'bitbag.ui.shipment_end_hour',
            ])
            ->add('pickup_breaking_hour', TextType::class, [
                'label' => 'bitbag.ui.pickup_breaking_hour',
            ])
            ->add('package_width', TextType::class, [
                'label' => 'bitbag.ui.package_width',
            ])
            ->add('package_height', TextType::class, [
                'label' => 'bitbag.ui.package_height',
            ])
            ->add('package_length', TextType::class, [
                'label' => 'bitbag.ui.package_length',
            ])
            ->add('cod_payment_method_code', TextType::class, [
                'label' => 'bitbag.ui.cod_payment_method_code',
            ])
            ->add('collect_on_delivery_form', ChoiceType::class, [
                'label' => 'bitbag.ui.collect_on_deliveryForm',
                'choices' => [
                    'bitbag.ui.cod_cash' => 'CASH',
                    'bitbag.ui.cod_bank_transfer' => 'BANK_TRANSFER',
                ],
            ])
        ;
    }
}
