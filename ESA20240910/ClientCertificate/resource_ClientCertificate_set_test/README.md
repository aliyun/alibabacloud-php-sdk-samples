#### Project Overview
This project is a complete engineering example for creating client certificates under the ClientCertificate resource of Alibaba Cloud's ESA (version: 2024-09-10) service. This example demonstrates how to:
- Create, update, and delete client certificates.
- Manage the lifecycle of client certificates, including activation and revocation.

#### Notes
- **Operation Costs**:
  - Running the sample code may incur costs for online resource operations on the current account. Please proceed with caution!

- **Dependencies**:
  - The resource [Client Certificate] depends on the [Site] resource, so you must successfully create a site before creating a client certificate.
  - The [Site] resource depends on the [Rate Plan] resource, so you need to successfully purchase a rate plan before creating a site.

- **Asynchronous Operations**:
  - Purchasing a new rate plan and creating a site are both asynchronous operations. You must wait for their status to update to the specified state before proceeding to the next step.

#### Workflow
1. **Initialize the Client**
   - To run this example, you must first configure your credentials as described in [Credential Configuration](https://help.aliyun.com/zh/sdk/developer-reference/v2-manage-net-access-credentials). Create a client instance.

2. **Purchase a New Rate Plan**
   - Call the [PurchaseRatePlan](https://api.aliyun.com/api/ESA/2024-09-10/PurchaseRatePlan) API to purchase a new rate plan and wait for its status to change to "running".

3. **Create a Site**
   - After successfully purchasing the rate plan, call the [CreateSite](https://api.aliyun.com/api/ESA/2024-09-10/CreateSite) API to create a new site and wait for its status to change to "pending".

4. **Create a Client Certificate**
   - After the site is successfully created, call the [CreateClientCertificate](https://api.aliyun.com/api/ESA/2024-09-10/CreateClientCertificate) API to create a new client certificate.

5. **Update a Client Certificate**
   - Call the [RevokeClientCertificate](https://api.aliyun.com/api/ESA/2024-09-10/RevokeClientCertificate) API to revoke the created client certificate.
   - Call the [ActivateClientCertificate](https://api.aliyun.com/api/ESA/2024-09-10/ActivateClientCertificate) API to activate the created client certificate.
   - Call the [RevokeClientCertificate](https://api.aliyun.com/api/ESA/2024-09-10/RevokeClientCertificate) API again to revoke the created client certificate.

6. **Delete a Client Certificate**
   - Finally, call the [DeleteClientCertificate](https://api.aliyun.com/api/ESA/2024-09-10/DeleteClientCertificate) API to delete the previously created client certificate, completing the entire lifecycle management.

#### How to Run
- *Requires PHP 5.6 or later.*
- *Composer must be [installed globally](https://getcomposer.org/doc/00-intro.md?spm=api-workbench.SDK%20Document.0.0.206f726ceIMZ36#globally) on your system.*
- *Note: The PHP version used to install the SDK using Composer must be equal to or less than the actual PHP version used at runtime. For example, the vendor directory generated after installing the SDK in PHP 7.2 is usable only in PHP 7.2 and above. Copying it to PHP 5.6 will cause dependency version incompatibility.*
>*Some users may be unable to install due to network issues. You can switch to the Alibaba Cloud Composer full image by running the following command.*
```sh
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
- *Command*
```sh
composer install && php src/Sample.php
```

#### More Samples
For additional examples, visit: https://github.com/aliyun/alibabacloud-php-sdk-samples