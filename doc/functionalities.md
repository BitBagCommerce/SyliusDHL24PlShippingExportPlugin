# Functionalities

---
### Introduction
DHL International GmbH - a German parcel and logistics company operating in the international courier segment.
It has a worldwide reach.

The plugin allows you to make a DHL24 (PL) courier order with one click of the mouse, without having to fill in the data each time.

This plugin supports communication with the DHL API, including exporting shipping data
and creating ready-made labels to print directly from the order.

### Usage
After installation, the user can add a shipping method corresponding to the service provider he wants to ship to.

<div align="center">
    <img src="./images/shipping_method.png"/>
</div>

Then he create a new "shipping gateway" for the added shipping method.

<div align="center">
    <img src="./images/shipping_gateway.png"/>
</div>

<p align="center">
    <b>It is possible to select:</b>
</p>

<p align="center">
    type of request
</p>
<div align="center">
    <img src="./images/type_of_request.jpg"/>
</div>

<p align="center">
    type of transport service
</p>
<div align="center">
    <img src="./images/type_of_transport_service.jpg"/>
</div>

<p align="center">
    return label
</p>
<div align="center">
    <img src="./images/return_label.jpg"/>
</div>

<p align="center">
    type of package
</p>
<div align="center">
    <img src="./images/type_of_package.jpg"/>
</div>

Once the shipping method and shipping gateway for the shipping provider are created,
customer can use this shipping method during a checkout. When the order is placed,
user can now go to the 'Export shipping data' section from Sylius Admin Panel and export chosen shipments.

After exporting the shipment, it is possible to download the label for printing.

<div align="center">
    <img src="./images/shipping_export.png"/>
</div>
