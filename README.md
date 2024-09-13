# [![](https://bitbag.io/wp-content/uploads/2021/10/SyliusDHL24PLShippingExportPlugin.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_dhl24)

# BitBag SyliusDHL24PlShippingExportPlugin

----

[![](https://img.shields.io/packagist/l/bitbag/dhl24-pl-shipping-export-plugin.svg) ](https://packagist.org/packages/bitbag/dhl24-pl-shipping-export-plugin "License") [ ![](https://img.shields.io/packagist/v/bitbag/dhl24-pl-shipping-export-plugin.svg) ](https://packagist.org/packages/bitbag/dhl24-pl-shipping-export-plugin "Version") [ ![](https://img.shields.io/github/actions/workflow/status/BitBagCommerce/SyliusDHL24PlShippingExportPlugin/build.yml?branch=master) ](https://github.com/BitBagCommerce/SyliusDHL24PlShippingExportPlugin/actions?query=branch%3Amaster "Build status") [ ![](https://img.shields.io/scrutinizer/quality/g/BitBagCommerce/SyliusDhl24PlShippingExportPlugin.svg) ](https://scrutinizer-ci.com/g/BitBagCommerce/SyliusDHL24PlShippingExportPlugin/ "Scrutinizer") [![](https://poser.pugx.org/bitbag/dhl24-pl-shipping-export-plugin/downloads)](https://packagist.org/packages/bitbag/dhl24-pl-shipping-export-plugin "Total Downloads") [![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com) [![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_dhl24)

<p>
 <img align="left" src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p> 

We want to impact many unique eCommerce projects and build our brand recognition worldwide, so we are heavily involved in creating open-source solutions, especially for Sylius. We have already created over **35 extensions, which have been downloaded almost 2 million times.**

You can find more information about our eCommerce services and technologies on our website: https://bitbag.io/. We have also created a unique service dedicated to creating plugins: https://bitbag.io/services/sylius-plugin-development. 

Do you like our work? Would you like to join us? Check out the **“Career” tab:** https://bitbag.io/pl/kariera. 

# About Us 
---

BitBag is a software house that implements tailor-made eCommerce platforms with the entire infrastructure—from creating eCommerce platforms to implementing PIM and CMS systems to developing custom eCommerce applications, specialist B2B solutions, and migrations from other platforms.

We actively participate in Sylius's development. We have already completed **over 150 projects**, cooperating with clients worldwide, including smaller enterprises and large international companies. We have completed projects for such important brands as **Mytheresa, Foodspring, Planeta Huerto (Carrefour Group), Albeco, Mollie, and ArtNight.**

We have a 70-person team of experts: business analysts and consultants, eCommerce developers, project managers, and QA testers.

**Our services:**
* B2B and B2C eCommerce platform implementations
* Multi-vendor marketplace platform implementations
* eCommerce migrations
* Sylius plugin development
* Sylius consulting
* Project maintenance and long-term support
* PIM and CMS implementations

**Some numbers from BitBag regarding Sylius:**
* 70 experts on board 
* +150 projects delivered on top of Sylius
* 30 countries of BitBag’s customers
* 7 years in the Sylius ecosystem
* +35 plugins created for Sylius

---
[![](https://bitbag.io/wp-content/uploads/2024/09/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_dhl) 

---


## Table of Content

***

* [Overview](#overview)
* [Installation](#installation)
     * [Requirements](#requirements)
     * [Customization](#customization)
* [Functionalities](#functionalities)
* [Demo](#demo)
* [Additional resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact](#contact)
* [Community](#community)


# Overview

***

Streamline your eCommerce logistics with the DHL24ShippingExport Plugin, designed to integrate with your Sylius platform seamlessly. This plugin automates the shipping label generation process for DHL24, facilitating a more efficient and error-free shipping workflow. It takes the complexity out of your shipping operations, enabling you to focus on what really matters: delivering a great customer experience.

# Installation
---
The complete installation guide ca be found **[here](doc/installation.md).**

## Requirements

We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.0          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |


## Customization

***

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

Run the below command to see what Symfony services are shared with this plugin:
```bash
$ bin/console debug:container bitbag_sylius_dhl24pl_plugin
```
---

**If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms) or send us an e-mail at hello@bitbag.io!**

---
# Functionalities

All main functionalities of the plugin are described **[here](doc/functionalities.md).**


# Demo 
---

We created a demo app with some useful use-cases of plugins! Visit http://demo.sylius.com/ to take a look at it.

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_cms)

# Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)
* [Blog - SyliusDHL24PlShippingExportPlugin](https://bitbag.io/blog/ecommerce-shipping-solutions-for-sylius)


# License
---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

# Contact
---
This open-source plugin was developed to help the Sylius community. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know! **Contact us** or send us an **e-mail to hello@bitbag.io** with your question(s).

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_dhl)

# Community
---- 

For online communication, we invite you to chat with us & other users on **[Sylius Slack](https://sylius-devs.slack.com/).**

[![](https://bitbag.io/wp-content/uploads/2024/09/badges-partners.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_dhl)
