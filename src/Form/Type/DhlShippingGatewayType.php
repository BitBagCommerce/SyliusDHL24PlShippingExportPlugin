<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\DhlShippingExportPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class DhlShippingGatewayType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'bitbag.ui.dhl_login',
            ])
            ->add('password', TextType::class, [
                'label' => 'bitbag.ui.dhl_password',
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
            ->add('type', ChoiceType::class, [
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
            ->add('package_width', TextType::class, [
                'label' => 'bitbag.ui.package_width',
            ])
            ->add('package_height', TextType::class, [
                'label' => 'bitbag.ui.package_height',
            ])
            ->add('package_length', TextType::class, [
                'label' => 'bitbag.ui.package_length',
            ])
        ;
    }
}