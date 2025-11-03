#### 项目简介
该项目是在阿里云产品ESA（版本：2024-09-10）服务的 ScheduledPreloadJob 资源下「创建和删除定时预加载任务」的完整工程示例。此示例向您展示如何：
- 创建和删除定时预加载任务。

#### 注意事项
- **操作费用**：
  - 运行示例代码可能对当前账号发起线上资源操作产生费用，请小心操作！

- **依赖关系**：
  - 资源【定时预加载任务】依赖于资源【站点】，因此在创建定时预加载任务之前必须先成功创建站点。
  - 资源【站点】依赖于资源【套餐】，因此在创建站点之前需要先成功购买套餐。

- **异步操作**：
  - 新购套餐、创建站点均为异步操作，需等待其状态更新为指定状态后再进行下一步操作。

#### 工作流程
1. **初始化客户端**
   - 要运行此示例，您必须首先配置您的凭证，如[凭证配置](https://help.aliyun.com/zh/sdk/developer-reference/v2-manage-php-access-credentials)中所述。创建一个客户端实例。

2. **新购套餐**
   - 调用 [PurchaseRatePlan](https://api.aliyun.com/api/ESA/2024-09-10/PurchaseRatePlan) 接口购买一个新的资源套餐，并等待其状态变为“running”。

3. **创建站点**
   - 在成功购买套餐后，调用 [CreateSite](https://api.aliyun.com/api/ESA/2024-09-10/CreateSite) 接口创建一个新的站点，并等待其状态变为“pending”。

4. **创建定时预加载任务**
   - 在站点创建成功后，调用 [CreateScheduledPreloadJob](https://api.aliyun.com/api/ESA/2024-09-10/CreateScheduledPreloadJob) 接口创建一个定时预加载任务。

5. **删除定时预加载任务**
   - 最后，调用 [DeleteScheduledPreloadJob](https://api.aliyun.com/api/ESA/2024-09-10/DeleteScheduledPreloadJob) 接口删除之前创建的定时预加载任务，完成整个生命周期管理。

#### 执行步骤
- *最低要求 PHP 5.6*
- *必须在系统上[全局安装 Composer](https://getcomposer.org/doc/00-intro.md?spm=api-workbench.SDK%20Document.0.0.206f726ceIMZ36#globally)*
- *注意：执行 composer 安装 SDK 的 PHP 版本要小于或等于实际运行时的 PHP 版本。 例如，在 PHP7.2 环境下安装 SDK 后生成 vendor 目录，只能在 PHP7.2 以上版本使用，如果拷贝到 PHP5.6 环境下使用，会出现依赖版本不兼容问题。*
>*一些用户可能由于网络问题无法安装，可以通过以下命令切换为阿里云 Composer 全量镜像。*
```sh
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
- *执行命令*
```sh
composer install && php src/Sample.php
```

#### 更多示例
更多示例请查看 https://github.com/aliyun/alibabacloud-php-sdk-samples