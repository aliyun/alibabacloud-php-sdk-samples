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
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateVideoProcessingResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateVideoProcessingRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\UpdateVideoProcessingRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteVideoProcessingRequest;
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
   * @return CreateVideoProcessingResponseBody
   */
  static public function videoProc($siteResponseBody, $client)
  {
    Console::info('Begin Call CreateVideoProcessing to create resource');
    $createVideoProcessingRequest = new CreateVideoProcessingRequest([
      'videoSeekEnable' => 'on',
      'siteId' => $siteResponseBody->siteId,
      'ruleEnable' => 'on',
      'flvVideoSeekMode' => 'by_byte',
      'mp4SeekEnd' => 'end',
      'flvSeekStart' => 'start',
      'rule' => '(http.host eq "video.example.com")',
      'sequence' => 1,
      'mp4SeekStart' => 'start',
      'siteVersion' => 0,
      'flvSeekEnd' => 'end',
      'ruleName' => 'test',
    ]);
    $createVideoProcessingResponse = $client->createVideoProcessing($createVideoProcessingRequest);
    Console::info('Call CreateVideoProcessing success, response: ');
    Console::info(Utils::toJSONString($createVideoProcessingResponse));
    return $createVideoProcessingResponse->body;
  }

  /**
   * @param CreateSiteResponseBody $siteResponseBody
   * @param CreateVideoProcessingResponseBody $createVideoProcessingResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateVideoProc($siteResponseBody, $createVideoProcessingResponseBody, $client)
  {
    Console::info('Begin Call UpdateVideoProcessing to update resource');
    $updateVideoProcessingRequest = new UpdateVideoProcessingRequest([
      'videoSeekEnable' => 'off',
      'siteId' => $siteResponseBody->siteId,
      'ruleEnable' => 'off',
      'flvVideoSeekMode' => 'by_time',
      'mp4SeekEnd' => 'e',
      'flvSeekStart' => 's',
      'rule' => '(http.request.uri eq "/content?page=1234")',
      'sequence' => 1,
      'mp4SeekStart' => 's',
      'flvSeekEnd' => 'e',
      'ruleName' => 'test_modify',
      'configId' => $createVideoProcessingResponseBody->configId,
    ]);
    $updateVideoProcessingResponse = $client->updateVideoProcessing($updateVideoProcessingRequest);
    Console::info('Call UpdateVideoProcessing success, response: ');
    Console::info(Utils::toJSONString($updateVideoProcessingResponse));
  }

  /**
   * @param CreateSiteResponseBody $siteResponseBody
   * @param CreateVideoProcessingResponseBody $createVideoProcessingResponseBody
   * @param ESA $client
   * @return void
   */
  static public function destroyVideoProc($siteResponseBody, $createVideoProcessingResponseBody, $client)
  {
    Console::info('Begin Call DeleteVideoProcessing to destroy resource');
    $deleteVideoProcessingRequest = new DeleteVideoProcessingRequest([
      'siteId' => $siteResponseBody->siteId,
      'configId' => $createVideoProcessingResponseBody->configId,
    ]);
    $deleteVideoProcessingResponse = $client->deleteVideoProcessing($deleteVideoProcessingRequest);
    Console::info('Call DeleteVideoProcessing success, response: ');
    Console::info(Utils::toJSONString($deleteVideoProcessingResponse));
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
    $ratePlanInstRespBody = self::ratePlanInst($esa20240910Client);
    $siteRespBody = self::site($ratePlanInstRespBody, $esa20240910Client);
    $videoProcRespBody = self::videoProc($siteRespBody, $esa20240910Client);
    // update resource
    self::updateVideoProc($siteRespBody, $videoProcRespBody, $esa20240910Client);
    // destroy resource
    self::destroyVideoProc($siteRespBody, $videoProcRespBody, $esa20240910Client);
  }

}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
  require_once $path;
}
Sample::main(array_slice($argv, 1));
