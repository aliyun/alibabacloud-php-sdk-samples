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
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateClientCertificateResponseBody;
use AlibabaCloud\SDK\ESA\V20240910\Models\CreateClientCertificateRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\RevokeClientCertificateRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\ActivateClientCertificateRequest;
use AlibabaCloud\SDK\ESA\V20240910\Models\DeleteClientCertificateRequest;
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
  static public function ratePlanInstCltCert($client)
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
   * @param PurchaseRatePlanResponseBody $ratePlanInstCltCertResponseBody
   * @param ESA $client
   * @return CreateSiteResponseBody
   */
  static public function siteCltCert($ratePlanInstCltCertResponseBody, $client)
  {
    Console::info('Begin Call CreateSite to create resource');
    $createSiteRequest = new CreateSiteRequest([
      'siteName' => 'gositecdn.cn',
      'instanceId' => $ratePlanInstCltCertResponseBody->instanceId,
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
   * @param CreateSiteResponseBody $siteCltCertResponseBody
   * @param ESA $client
   * @return CreateClientCertificateResponseBody
   */
  static public function cltCert($siteCltCertResponseBody, $client)
  {
    Console::info('Begin Call CreateClientCertificate to create resource');
    $createClientCertificateRequest = new CreateClientCertificateRequest([
      'siteId' => $siteCltCertResponseBody->siteId,
      'pkeyType' => 'RSA',
      'validityDays' => 365,
    ]);
    $createClientCertificateResponse = $client->createClientCertificate($createClientCertificateRequest);
    Console::info('Call CreateClientCertificate success, response: ');
    Console::info(Utils::toJSONString($createClientCertificateResponse));
    return $createClientCertificateResponse->body;
  }

  /**
   * @param CreateSiteResponseBody $siteCltCertResponseBody
   * @param CreateClientCertificateResponseBody $createClientCertificateResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateCltCert($siteCltCertResponseBody, $createClientCertificateResponseBody, $client)
  {
    Console::info('Begin Call RevokeClientCertificate to update resource');
    $revokeClientCertificateRequest = new RevokeClientCertificateRequest([
      'siteId' => $siteCltCertResponseBody->siteId,
      'id' => $createClientCertificateResponseBody->id,
    ]);
    $revokeClientCertificateResponse = $client->revokeClientCertificate($revokeClientCertificateRequest);
    Console::info('Call RevokeClientCertificate success, response: ');
    Console::info(Utils::toJSONString($revokeClientCertificateResponse));
  }

  /**
   * @param CreateSiteResponseBody $siteCltCertResponseBody
   * @param CreateClientCertificateResponseBody $createClientCertificateResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateCltCert1($siteCltCertResponseBody, $createClientCertificateResponseBody, $client)
  {
    Console::info('Begin Call ActivateClientCertificate to update resource');
    $activateClientCertificateRequest = new ActivateClientCertificateRequest([
      'siteId' => $siteCltCertResponseBody->siteId,
      'id' => $createClientCertificateResponseBody->id,
    ]);
    $activateClientCertificateResponse = $client->activateClientCertificate($activateClientCertificateRequest);
    Console::info('Call ActivateClientCertificate success, response: ');
    Console::info(Utils::toJSONString($activateClientCertificateResponse));
  }

  /**
   * @param CreateSiteResponseBody $siteCltCertResponseBody
   * @param CreateClientCertificateResponseBody $createClientCertificateResponseBody
   * @param ESA $client
   * @return void
   */
  static public function updateCltCert2($siteCltCertResponseBody, $createClientCertificateResponseBody, $client)
  {
    Console::info('Begin Call RevokeClientCertificate to update resource');
    $revokeClientCertificateRequest = new RevokeClientCertificateRequest([
      'siteId' => $siteCltCertResponseBody->siteId,
      'id' => $createClientCertificateResponseBody->id,
    ]);
    $revokeClientCertificateResponse = $client->revokeClientCertificate($revokeClientCertificateRequest);
    Console::info('Call RevokeClientCertificate success, response: ');
    Console::info(Utils::toJSONString($revokeClientCertificateResponse));
  }

  /**
   * @param CreateSiteResponseBody $siteCltCertResponseBody
   * @param CreateClientCertificateResponseBody $createClientCertificateResponseBody
   * @param ESA $client
   * @return void
   */
  static public function destroyCltCert($siteCltCertResponseBody, $createClientCertificateResponseBody, $client)
  {
    Console::info('Begin Call DeleteClientCertificate to destroy resource');
    $deleteClientCertificateRequest = new DeleteClientCertificateRequest([
      'siteId' => $siteCltCertResponseBody->siteId,
      'id' => $createClientCertificateResponseBody->id,
    ]);
    $deleteClientCertificateResponse = $client->deleteClientCertificate($deleteClientCertificateRequest);
    Console::info('Call DeleteClientCertificate success, response: ');
    Console::info(Utils::toJSONString($deleteClientCertificateResponse));
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
    $ratePlanInstCltCertRespBody = self::ratePlanInstCltCert($esa20240910Client);
    $siteCltCertRespBody = self::siteCltCert($ratePlanInstCltCertRespBody, $esa20240910Client);
    $cltCertRespBody = self::cltCert($siteCltCertRespBody, $esa20240910Client);
    // update resource
    self::updateCltCert($siteCltCertRespBody, $cltCertRespBody, $esa20240910Client);
    self::updateCltCert1($siteCltCertRespBody, $cltCertRespBody, $esa20240910Client);
    self::updateCltCert2($siteCltCertRespBody, $cltCertRespBody, $esa20240910Client);
    // destroy resource
    self::destroyCltCert($siteCltCertRespBody, $cltCertRespBody, $esa20240910Client);
  }

}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
  require_once $path;
}
Sample::main(array_slice($argv, 1));
