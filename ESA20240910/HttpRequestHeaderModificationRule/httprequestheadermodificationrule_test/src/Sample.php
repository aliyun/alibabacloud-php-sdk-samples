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
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateHttpRequestHeaderModificationRuleResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateHttpRequestHeaderModificationRuleRequest\requestHeaderModification;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateHttpRequestHeaderModificationRuleRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\UpdateHttpRequestHeaderModificationRuleRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteHttpRequestHeaderModificationRuleRequest;
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
  static public function site($ratePlanInstResponseBody, $client)
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
   * @param CreateSiteResponseBody $siteResponseBody
   * @param ESA $client
   * @return CreateHttpRequestHeaderModificationRuleResponseBody
   */
  static public function reqHdrModRule($siteResponseBody, $client)
  {
    Console::info('Begin Call CreateHttpRequestHeaderModificationRule to create resource');
    $requestHeaderModification = new requestHeaderModification([
      'type' => 'static',
      'value' => 'add',
      'operation' => 'add',
      'name' => 'testadd',
    ]);
    $requestHeaderModification1 = new requestHeaderModification([
      'operation' => 'del',
      'name' => 'testdel',
    ]);
    $requestHeaderModification2 = new requestHeaderModification([
      'type' => 'dynamic',
      'value' => 'ip.geoip.country',
      'operation' => 'modify',
      'name' => 'testmodify',
    ]);
    $createHttpRequestHeaderModificationRuleRequest = new CreateHttpRequestHeaderModificationRuleRequest([
      'siteId' => $siteResponseBody->siteId,
      'ruleEnable' => 'on',
      'rule' => '(http.host eq "video.example.com")',
      'sequence' => 1,
      'siteVersion' => 0,
      'ruleName' => 'test',
      'requestHeaderModification' => [
        $requestHeaderModification,
        $requestHeaderModification1,
        $requestHeaderModification2
      ],
    ]);
    $createHttpRequestHeaderModificationRuleResponse = $client->createHttpRequestHeaderModificationRule($createHttpRequestHeaderModificationRuleRequest);
    Console::info('Call CreateHttpRequestHeaderModificationRule success, response: ');
    Console::info(Utils::toJSONString($createHttpRequestHeaderModificationRuleResponse));
    return $createHttpRequestHeaderModificationRuleResponse->body;
  }

  /**
   * @param CreateSiteResponseBody $siteResponseBody
   * @param CreateHttpRequestHeaderModificationRuleResponseBody $createHttpRequestHeaderModificationRuleResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateReqHdrModRule($siteResponseBody, $createHttpRequestHeaderModificationRuleResponseBody, $client)
  {
    Console::info('Begin Call UpdateHttpRequestHeaderModificationRule to update resource');
    $requestHeaderModification = new \AlibabaCloud\SDK\ESA\V20240910\Models\UpdateHttpRequestHeaderModificationRuleRequest\requestHeaderModification([
      'type' => 'static',
      'value' => 'modify1',
      'operation' => 'modify',
      'name' => 'testmodify1',
    ]);
    $updateHttpRequestHeaderModificationRuleRequest = new UpdateHttpRequestHeaderModificationRuleRequest([
      'siteId' => $siteResponseBody->siteId,
      'ruleEnable' => 'off',
      'rule' => '(http.request.uri eq "/content?page=1234")',
      'sequence' => 1,
      'ruleName' => 'test_modify',
      'requestHeaderModification' => [
        $requestHeaderModification
      ],
      'configId' => $createHttpRequestHeaderModificationRuleResponseBody->configId,
    ]);
    $updateHttpRequestHeaderModificationRuleResponse = $client->updateHttpRequestHeaderModificationRule($updateHttpRequestHeaderModificationRuleRequest);
    Console::info('Call UpdateHttpRequestHeaderModificationRule success, response: ');
    Console::info(Utils::toJSONString($updateHttpRequestHeaderModificationRuleResponse));
  }

  /**
   * @param CreateSiteResponseBody $siteResponseBody
   * @param CreateHttpRequestHeaderModificationRuleResponseBody $createHttpRequestHeaderModificationRuleResponseBody
   * @param ESA $client
   * @return void
   */
  static public function destroyReqHdrModRule($siteResponseBody, $createHttpRequestHeaderModificationRuleResponseBody, $client)
  {
    Console::info('Begin Call DeleteHttpRequestHeaderModificationRule to destroy resource');
    $deleteHttpRequestHeaderModificationRuleRequest = new DeleteHttpRequestHeaderModificationRuleRequest([
      'siteId' => $siteResponseBody->siteId,
      'configId' => $createHttpRequestHeaderModificationRuleResponseBody->configId,
    ]);
    $deleteHttpRequestHeaderModificationRuleResponse = $client->deleteHttpRequestHeaderModificationRule($deleteHttpRequestHeaderModificationRuleRequest);
    Console::info('Call DeleteHttpRequestHeaderModificationRule success, response: ');
    Console::info(Utils::toJSONString($deleteHttpRequestHeaderModificationRuleResponse));
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
    //resource_HttpRequestHeaderModificationRule_test
    $ratePlanInstRespBody = self::ratePlanInst($esa20240910Client);
    //resource_Site_HttpRequestHeaderModificationRule_test
    $siteRespBody = self::site($ratePlanInstRespBody, $esa20240910Client);
    $reqHdrModRuleRespBody = self::reqHdrModRule($siteRespBody, $esa20240910Client);
    // update resource
    self::updateReqHdrModRule($siteRespBody, $reqHdrModRuleRespBody, $esa20240910Client);
    // destroy resource
    self::destroyReqHdrModRule($siteRespBody, $reqHdrModRuleRespBody, $esa20240910Client);
  }

}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
  require_once $path;
}
Sample::main(array_slice($argv, 1));
