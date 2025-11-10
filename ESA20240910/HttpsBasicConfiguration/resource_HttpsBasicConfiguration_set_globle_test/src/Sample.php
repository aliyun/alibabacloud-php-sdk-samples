<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace AlibabaCloud\CodeSample;
use AlibabaCloud\SDK\ESA\V20240910\ESA;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\Credentials\Credential;
use AlibabaCloud\SDK\ESA\V20240910\Models\PurchaseRatePlanResponseBody;
use AlibabaCloud\Dara\Util\Console;
use AlibabaCloud\SDK\ESA\V20240910\Models\PurchaseRatePlanRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DescribeRatePlanInstanceStatusRequest;
use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateSiteResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateSiteRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\GetSiteRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateHttpsBasicConfigurationResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateHttpsBasicConfigurationRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\UpdateHttpsBasicConfigurationRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteHttpsBasicConfigurationRequest;
class Sample {


  /**
   * @remarks
   * Init Client
   * @return ESA
   */
  static public function createESA20240910Client()
  {
    $config = new Config([ ]);
    $config->credential = new Credential(null);
    // Endpoint please refer to https://api.aliyun.com/product/ESA
    $config->endpoint = 'esa.cn-hangzhou.aliyuncs.com';
    return new ESA($config);
  }

  /**
   * @param ESA $client
   * @return PurchaseRatePlanResponseBody
   */
  static public function ratePlanInst($client)
  {
    Console::info('Begin Call PurchaseRatePlan to create resource');
    $purchaseRatePlanRequest = new PurchaseRatePlanRequest([
      'type' => 'NS',
      'chargeType' => 'PREPAY',
      'autoRenew' => false,
      'period' => 1,
      'coverage' => 'overseas',
      'autoPay' => true,
      'planName' => 'high',
    ]);
    $purchaseRatePlanResponse = $client->purchaseRatePlan($purchaseRatePlanRequest);
    $describeRatePlanInstanceStatusRequest = new DescribeRatePlanInstanceStatusRequest([
      'instanceId' => $purchaseRatePlanResponse->body->instanceId,
    ]);
    $currentRetry = 0;
    $delayedTime = 10000;
    $interval = 10000;

    while ($currentRetry < 10) {
      try {
        $sleepTime = 0;
        if ($currentRetry == 0) {
          $sleepTime = $delayedTime;
        } else {
          $sleepTime = $interval;
        }

        Console::info('Polling for asynchronous results...');
        usleep($sleepTime * 1000);
      } catch (DaraException $error) {
        throw new DaraException([
          'message' => $error->message,
        ]);
      }      
      $describeRatePlanInstanceStatusResponse = $client->describeRatePlanInstanceStatus($describeRatePlanInstanceStatusRequest);
      $instanceStatus = $describeRatePlanInstanceStatusResponse->body->instanceStatus;
      if ($instanceStatus === 'running') {
        Console::info('Call PurchaseRatePlan success, response: ');
        Console::info(Utils::toJSONString($purchaseRatePlanResponse));
        return $purchaseRatePlanResponse->body;
      }

      $currentRetry++;
    }
    throw new DaraException([
      'message' => 'Asynchronous check failed',
    ]);
  }

  /**
   * @param PurchaseRatePlanResponseBody $ratePlanInstResponseBody
   * @param ESA $client
   * @return CreateSiteResponseBody
   */
  static public function httpBasicConf($ratePlanInstResponseBody, $client)
  {
    Console::info('Begin Call CreateSite to create resource');
    $createSiteRequest = new CreateSiteRequest([
      'siteName' => 'gositecdn.cn',
      'instanceId' => $ratePlanInstResponseBody->instanceId,
      'coverage' => 'overseas',
      'accessType' => 'NS',
    ]);
    $createSiteResponse = $client->createSite($createSiteRequest);
    $getSiteRequest = new GetSiteRequest([
      'siteId' => $createSiteResponse->body->siteId,
    ]);
    $currentRetry = 0;
    $delayedTime = 60000;
    $interval = 10000;

    while ($currentRetry < 5) {
      try {
        $sleepTime = 0;
        if ($currentRetry == 0) {
          $sleepTime = $delayedTime;
        } else {
          $sleepTime = $interval;
        }

        Console::info('Polling for asynchronous results...');
        usleep($sleepTime * 1000);
      } catch (DaraException $error) {
        throw new DaraException([
          'message' => $error->message,
        ]);
      }      
      $getSiteResponse = $client->getSite($getSiteRequest);
      $status = $getSiteResponse->body->siteModel->status;
      if ($status === 'pending') {
        Console::info('Call CreateSite success, response: ');
        Console::info(Utils::toJSONString($createSiteResponse));
        return $createSiteResponse->body;
      }

      $currentRetry++;
    }
    throw new DaraException([
      'message' => 'Asynchronous check failed',
    ]);
  }

  /**
   * @param CreateSiteResponseBody $httpBasicConfResponseBody
   * @param ESA $client
   * @return CreateHttpsBasicConfigurationResponseBody
   */
  static public function httpsBasicConf($httpBasicConfResponseBody, $client)
  {
    Console::info('Begin Call CreateHttpsBasicConfiguration to create resource');
    $createHttpsBasicConfigurationRequest = new CreateHttpsBasicConfigurationRequest([
      'siteId' => $httpBasicConfResponseBody->siteId,
      'ciphersuite' => 'TLS_ECDHE_ECDSA_WITH_CHACHA20_POLY1305_SHA256',
      'ruleEnable' => 'on',
      'https' => 'on',
      'http3' => 'on',
      'http2' => 'on',
      'tls10' => 'on',
      'tls11' => 'on',
      'sequence' => 1,
      'tls12' => 'on',
      'tls13' => 'on',
      'ciphersuiteGroup' => 'all',
      'rule' => 'true',
      'ruleName' => 'test_global1',
      'ocspStapling' => 'on',
    ]);
    $createHttpsBasicConfigurationResponse = $client->createHttpsBasicConfiguration($createHttpsBasicConfigurationRequest);
    Console::info('Call CreateHttpsBasicConfiguration success, response: ');
    Console::info(Utils::toJSONString($createHttpsBasicConfigurationResponse));
    return $createHttpsBasicConfigurationResponse->body;
  }

  /**
   * @param CreateSiteResponseBody $httpBasicConfResponseBody
   * @param CreateHttpsBasicConfigurationResponseBody $createHttpsBasicConfigurationResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateHttpsBasicConf($httpBasicConfResponseBody, $createHttpsBasicConfigurationResponseBody, $client)
  {
    Console::info('Begin Call UpdateHttpsBasicConfiguration to update resource');
    $updateHttpsBasicConfigurationRequest = new UpdateHttpsBasicConfigurationRequest([
      'siteId' => $httpBasicConfResponseBody->siteId,
      'ciphersuite' => 'TLS_ECDHE_ECDSA_WITH_CHACHA20_POLY1305_SHA256',
      'ruleEnable' => 'off',
      'https' => 'off',
      'http3' => 'off',
      'http2' => 'off',
      'tls10' => 'off',
      'tls11' => 'off',
      'sequence' => 2,
      'tls12' => 'off',
      'tls13' => 'off',
      'ciphersuiteGroup' => 'custom',
      'rule' => 'true',
      'ruleName' => 'test_global1',
      'ocspStapling' => 'off',
      'configId' => $createHttpsBasicConfigurationResponseBody->configId,
    ]);
    $updateHttpsBasicConfigurationResponse = $client->updateHttpsBasicConfiguration($updateHttpsBasicConfigurationRequest);
    Console::info('Call UpdateHttpsBasicConfiguration success, response: ');
    Console::info(Utils::toJSONString($updateHttpsBasicConfigurationResponse));
  }

  /**
   * @param CreateSiteResponseBody $httpBasicConfResponseBody
   * @param CreateHttpsBasicConfigurationResponseBody $createHttpsBasicConfigurationResponseBody
   * @param ESA $client
   * @return void
   */
  static public function destroyHttpsBasicConf($httpBasicConfResponseBody, $createHttpsBasicConfigurationResponseBody, $client)
  {
    Console::info('Begin Call DeleteHttpsBasicConfiguration to destroy resource');
    $deleteHttpsBasicConfigurationRequest = new DeleteHttpsBasicConfigurationRequest([
      'siteId' => $httpBasicConfResponseBody->siteId,
      'configId' => $createHttpsBasicConfigurationResponseBody->configId,
    ]);
    $deleteHttpsBasicConfigurationResponse = $client->deleteHttpsBasicConfiguration($deleteHttpsBasicConfigurationRequest);
    Console::info('Call DeleteHttpsBasicConfiguration success, response: ');
    Console::info(Utils::toJSONString($deleteHttpsBasicConfigurationResponse));
  }

  /**
   * @remarks
   * Running code may affect the online resources of the current account, please proceed with caution!
   * @param string[] $args
   * @return void
   */
  static public function main($args)
  {
    // The code may contain api calls involving fees. Please ensure that you fully understand the charging methods and prices before running.
    // Set the environment variable COST_ACK to true or delete the following judgment to run the sample code.
    $costAcknowledged = getenv('COST_ACK');
    if (is_null($costAcknowledged) || !$costAcknowledged === 'true') {
      Console::warning('Running code may affect the online resources of the current account, please proceed with caution!');
      return null;
    }

    // Init client
    $esa20240910Client = self::createESA20240910Client();
    // Init resource
    //resource_RatePlanInstance_set_globle_test
    $ratePlanInstRespBody = self::ratePlanInst($esa20240910Client);
    //resource_HttpBasicConfiguration_set_global_test
    $httpBasicConfRespBody = self::httpBasicConf($ratePlanInstRespBody, $esa20240910Client);
    $httpsBasicConfRespBody = self::httpsBasicConf($httpBasicConfRespBody, $esa20240910Client);
    // update resource
    self::updateHttpsBasicConf($httpBasicConfRespBody, $httpsBasicConfRespBody, $esa20240910Client);
    // destroy resource
    self::destroyHttpsBasicConf($httpBasicConfRespBody, $httpsBasicConfRespBody, $esa20240910Client);
  }

}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
  require_once $path;
}
Sample::main(array_slice($argv, 1));
