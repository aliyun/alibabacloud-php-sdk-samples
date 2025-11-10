#### Project Overview
This project is a complete engineering example for "creating, updating, and deleting HTTPS basic configurations" under the HttpsBasicConfiguration resource of Alibaba Cloud's ESA (version: 2024-09-10) service. This example demonstrates how to:
- Create an HTTPS basic configuration.
- Update an existing HTTPS basic configuration.
- Delete an existing HTTPS basic configuration.

#### Notes
- **Operation Costs**:
  - Running the sample code may incur costs by initiating online resource operations on your current account. Please proceed with caution!

- **Dependencies**:
  - The resource [HTTPS Basic Configuration] depends on the [Site] resource, so you must successfully create a site before creating an HTTPS basic configuration.
  - The [Site] resource depends on the [Plan] resource, so you need to successfully purchase a plan before creating a site.

- **Asynchronous Operations**:
  - Purchasing a new plan and creating a site are both asynchronous operations. You must wait for their status to update to the specified state before proceeding to the next step.

#### Workflow
1. **Initialize Client**
   - To run this example, you must first configure your credentials as described in the [Credential Configuration](https://help.aliyun.com/zh/sdk/developer-reference/v2-manage-net-access-credentials). Create a client instance.

2. **Purchase Plan**
   - Call the [PurchaseRatePlan](https://api.aliyun.com/api/ESA/2024-09-10/PurchaseRatePlan) API to purchase a new resource plan and wait for its status to change to "running".

3. **Create Site**
   - After successfully purchasing a plan, call the [CreateSite](https://api.aliyun.com/api/ESA/2024-09-10/CreateSite) API to create a new site and wait for its status to change to "pending".

4. **Create HTTPS Basic Configuration**
   - After the site is successfully created, call the [CreateHttpsBasicConfiguration](https://api.aliyun.com/api/ESA/2024-09-10/CreateHttpsBasicConfiguration) API to create an HTTPS basic configuration.

5. **Update HTTPS Basic Configuration**
   - Call the [UpdateHttpsBasicConfiguration](https://api.aliyun.com/api/ESA/2024-09-10/UpdateHttpsBasicConfiguration) API to update the created HTTPS basic configuration.

6. **Delete HTTPS Basic Configuration**
   - Finally, call the [DeleteHttpsBasicConfiguration](https://api.aliyun.com/api/ESA/2024-09-10/DeleteHttpsBasicConfiguration) API to delete the previously created HTTPS basic configuration, completing the entire lifecycle management.

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