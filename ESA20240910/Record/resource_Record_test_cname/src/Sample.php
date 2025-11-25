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
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateRecordResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateRecordRequest\authConf;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateRecordRequest\data;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateRecordRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateRecordResponse;
use AlibabaCloud\SDK\ESA\V20240910\Models\UpdateRecordRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\UpdateRecordResponse;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteRecordRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteRecordResponse;
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
   * @return CreateRecordResponseBody
   */
  static public function recordCname($siteResponseBody, $client)
  {
    Console::info('Begin Call CreateRecord to create resource');
    $authConf = new authConf([
      'secretKey' => 'hijklmnhi**********mnhijklmn',
      'version' => 'v4',
      'region' => 'us-east-1',
      'authType' => 'private',
      'accessKey' => 'abcdefgab*********efgabcdefg',
    ]);
    $data = new data([
      'value' => 'www.idltestr.com',
    ]);
    $createRecordRequest = new CreateRecordRequest([
      'recordName' => 'www.gositecdn.cn',
      'comment' => 'This is a remark',
      'proxied' => true,
      'siteId' => $siteResponseBody->siteId,
      'type' => 'CNAME',
      'sourceType' => 'S3',
      'data' => $data,
      'bizName' => 'api',
      'hostPolicy' => 'follow_hostname',
      'ttl' => 100,
      'authConf' => $authConf,
    ]);
    $createRecordResponse = self::createRecordWithRetry($client, $createRecordRequest);
    Console::info('Call CreateRecord success, response: ');
    Console::info(Utils::toJSONString($createRecordResponse));
    return $createRecordResponse->body;
  }

  /**
   * @param ESA $client
   * @param CreateRecordRequest $createRecordRequest
   * @return CreateRecordResponse
   */
  static public function createRecordWithRetry($client, $createRecordRequest)
  {
    $errorCode = '';
    $retry1 = 0;
    $interval1 = 5000;
    $retry2 = 0;
    $interval2 = 5000;

    while (($retry1 < 10) || ($retry2 < 20)) {
      try {
        $createRecordResponse = $client->createRecord($createRecordRequest);
        Console::info('Call CreateRecord success, response: ');
        Console::info(Utils::toJSONString($createRecordResponse));
        return $createRecordResponse;
      } catch (DaraException $error) {
        $errorCode = $error->code;
      }      
      if ($errorCode === 'Site.ServiceBusy') {
        Console::info('Call CreateRecord failed, errorCode: Site.ServiceBusy, please retry');
        usleep($interval1 * 1000);
        $retry1++;
      }

      if ($errorCode === 'TooManyRequests') {
        Console::info('Call CreateRecord failed, errorCode: TooManyRequests, please retry');
        usleep($interval2 * 1000);
        $retry2++;
      }

    }
    throw new DaraException([
      'message' => 'Call CreateRecord failed',
    ]);
  }

  /**
   * @param CreateRecordResponseBody $createRecordResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateRecordCname($createRecordResponseBody, $client)
  {
    Console::info('Begin Call UpdateRecord to update resource');
    $authConf = new \AlibabaCloud\SDK\ESA\V20240910\Models\UpdateRecordRequest\authConf([
      'secretKey' => 'secretkey0***********dcbafedcba',
      'version' => 'v2',
      'region' => 'us-gov-west-1',
      'authType' => 'private',
      'accessKey' => 'AccessKey0**********edcbafedcba',
    ]);
    $data = new \AlibabaCloud\SDK\ESA\V20240910\Models\UpdateRecordRequest\data([
      'value' => 'www.plexample.com',
    ]);
    $updateRecordRequest = new UpdateRecordRequest([
      'comment' => 'test_record_comment',
      'proxied' => true,
      'sourceType' => 'S3',
      'data' => $data,
      'bizName' => 'api',
      'hostPolicy' => 'follow_origin_domain',
      'ttl' => 86400,
      'authConf' => $authConf,
      'recordId' => $createRecordResponseBody->recordId,
    ]);
    $updateRecordResponse = self::updateRecordWithRetry($client, $updateRecordRequest);
    Console::info('Call UpdateRecord success, response: ');
    Console::info(Utils::toJSONString($updateRecordResponse));
  }

  /**
   * @param ESA $client
   * @param UpdateRecordRequest $updateRecordRequest
   * @return UpdateRecordResponse
   */
  static public function updateRecordWithRetry($client, $updateRecordRequest)
  {
    $errorCode = '';
    $retry1 = 0;
    $interval1 = 5000;
    $retry2 = 0;
    $interval2 = 3000;

    while (($retry1 < 20) || ($retry2 < 10)) {
      try {
        $updateRecordResponse = $client->updateRecord($updateRecordRequest);
        Console::info('Call UpdateRecord success, response: ');
        Console::info(Utils::toJSONString($updateRecordResponse));
        return $updateRecordResponse;
      } catch (DaraException $error) {
        $errorCode = $error->code;
      }      
      if ($errorCode === 'TooManyRequests') {
        Console::info('Call UpdateRecord failed, errorCode: TooManyRequests, please retry');
        usleep($interval1 * 1000);
        $retry1++;
      }

      if ($errorCode === 'Record.ServiceBusy') {
        Console::info('Call UpdateRecord failed, errorCode: Record.ServiceBusy, please retry');
        usleep($interval2 * 1000);
        $retry2++;
      }

    }
    throw new DaraException([
      'message' => 'Call UpdateRecord failed',
    ]);
  }

  /**
   * @param CreateRecordResponseBody $createRecordResponseBody
   * @param ESA $client
   * @return void
   */
  static public function destroyRecordCname($createRecordResponseBody, $client)
  {
    Console::info('Begin Call DeleteRecord to destroy resource');
    $deleteRecordRequest = new DeleteRecordRequest([
      'recordId' => $createRecordResponseBody->recordId,
    ]);
    $deleteRecordResponse = self::deleteRecordWithRetry($client, $deleteRecordRequest);
    Console::info('Call DeleteRecord success, response: ');
    Console::info(Utils::toJSONString($deleteRecordResponse));
  }

  /**
   * @param ESA $client
   * @param DeleteRecordRequest $deleteRecordRequest
   * @return DeleteRecordResponse
   */
  static public function deleteRecordWithRetry($client, $deleteRecordRequest)
  {
    $errorCode = '';
    $retry1 = 0;
    $interval1 = 5000;
    $retry2 = 0;
    $interval2 = 1000;

    while (($retry1 < 20) || ($retry2 < 10)) {
      try {
        $deleteRecordResponse = $client->deleteRecord($deleteRecordRequest);
        Console::info('Call DeleteRecord success, response: ');
        Console::info(Utils::toJSONString($deleteRecordResponse));
        return $deleteRecordResponse;
      } catch (DaraException $error) {
        $errorCode = $error->code;
      }      
      if ($errorCode === 'TooManyRequests') {
        Console::info('Call DeleteRecord failed, errorCode: TooManyRequests, please retry');
        usleep($interval1 * 1000);
        $retry1++;
      }

      if ($errorCode === 'Record.ServiceBusy') {
        Console::info('Call DeleteRecord failed, errorCode: Record.ServiceBusy, please retry');
        usleep($interval2 * 1000);
        $retry2++;
      }

    }
    throw new DaraException([
      'message' => 'Call DeleteRecord failed',
    ]);
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
    $recordCnameRespBody = self::recordCname($siteRespBody, $esa20240910Client);
    // update resource
    self::updateRecordCname($recordCnameRespBody, $esa20240910Client);
    // destroy resource
    self::destroyRecordCname($recordCnameRespBody, $esa20240910Client);
  }

}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
  require_once $path;
}
Sample::main(array_slice($argv, 1));
