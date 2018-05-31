@managing_shipping_export_dhl24
Feature: Managing shipping gateway
    In order to export shipping data to external shipping provider service
    As an Administrator
    I want to be able to export shipments to external API

    Background:
        Given the store operates on a single channel in the "United States" named "Web-US"
        And I am logged in as an administrator
        And the store has "DHL Express" shipping method with "$10.00" fee
        And there is a registered "dhl24_pl" shipping gateway for this shipping method named "DHL24"
        And it has "wsdl" field set to "https://sandbox.dhl24.com.pl/webapi2"
        And it has "login" field set to "123"
        And it has "password" field set to "123"
        And it has "billing_account_number" field set to "6000000"
        And it has "shipping_payment_type" field set to "SHIPPER"
        And it has "payment_type" field set to "BANK_TRANSFER"
        And it has "country" field set to "PL"
        And it has "name" field set to "Ja"
        And it has "street" field set to "Leśna"
        And it has "postal_code" field set to "60-001"
        And it has "city" field set to "Wawa"
        And it has "house_number" field set to "2"
        And it has "phone_number" field set to "123456789"
        And it has "drop_off_type" field set to "REQUEST_COURIER"
        And it has "service_type" field set to "AH"
        And it has "label_type" field set to "LBLP"
        And it has "package_type" field set to "ENVELOPE"
        And it has "shipment_start_hour" field set to "12:00"
        And it has "shipment_end_hour" field set to "15:00"
        And it has "pickup_breaking_hour" field set to "15:00"
        And it has "package_width" field set to "11"
        And it has "package_height" field set to "10"
        And it has "package_length" field set to "22"
        And it has "cod_payment_method_code" field set to "stripe_checkout"
        And it has "collect_on_delivery_form" field set to "BANK_TRANSFER"
        And the store has a product "Chicken" priced at "$2" in "Web-US" channel
        And customer "user@bitbag.pl" has placed 5 orders on the "Web-US" channel in each buying 5 "Chicken" products
        And the customer set the shipping address "Mike Ross" addressed it to "350 5th Ave", "10118" "New York" in the "United States" to orders
        And those orders were placed with "DHL Express" shipping method
        And set product weight to "10"
        And set units to the shipment

    @ui
    Scenario: Seeing shipments to export
        When I go to the shipping export page
        Then I should see 5 shipments with "New" state
