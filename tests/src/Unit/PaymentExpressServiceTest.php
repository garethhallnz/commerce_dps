<?php

namespace Drupal\Tests\commerce_dps\Unit;

use Drupal\commerce_dps\PaymentExpress\PaymentExpressService;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Tests\UnitTestCase;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \Drupal\commerce_dps\PaymentExpress\PaymentExpressService
 *
 * @group commerce_dps
 */
class PaymentExpressServiceTest extends UnitTestCase {

  protected $paymentExpressMock;

  /**
   * PxPay gateway.
   *
   * @var \Drupal\commerce_dps\PaymentExpress\PaymentExpressService
   */
  protected $paymentExpressService;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    parent::setUp();

    $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
      ->disableOriginalConstructor()
      ->getMock();

    $this->container = new ContainerBuilder();

    $this->container->set('logger.factory', $this->loggerMock);

    $this->paymentExpressService = new PaymentExpressService($this->loggerMock);
  }

  /**
   * @covers ::getGateway
   */
  public function testGetGateway() {
    $this->paymentExpressService->gateway = TRUE;
    $this->assertTrue($this->paymentExpressService->getGateway());
  }

  /**
   * @covers ::getUserId
   * @dataProvider configurationProvider
   */
  public function testGetUserId($data) {
    $this->paymentExpressService->setConfiguration($data);
    $this->assertEquals('foo', $this->paymentExpressService->getUserId());
  }

  /**
   * @covers ::getKey
   * @dataProvider configurationProvider
   */
  public function testGetKey($data) {
    $this->paymentExpressService->setConfiguration($data);
    $this->assertEquals('bar', $this->paymentExpressService->getKey());
  }

  /**
   * @covers ::getReference
   * @dataProvider configurationProvider
   */
  public function testGetReference($data) {
    $this->paymentExpressService->setConfiguration($data);
    $this->assertEquals('baz', $this->paymentExpressService->getReference());
  }

  /**
   * @covers ::getConfiguration
   * @dataProvider configurationProvider
   */
  public function testGetConfiguration($data) {
    $this->paymentExpressService->setConfiguration($data);
    $this->assertEquals($data, $this->paymentExpressService->getConfiguration());
    $this->assertEquals($data['foo'], $this->paymentExpressService->getConfiguration('foo'));
  }

  /**
   * @covers ::setConfiguration
   */
  public function testSetConfiguration() {
    $data = ['foo'];
    $this->paymentExpressService->setConfiguration(['foo']);
    $this->assertEquals($data, $this->paymentExpressService->configuration);
  }

  /**
   * Data provider for ::testValidateSecurityCode.
   *
   * @return array
   *   A list of testValidateSecurityCode function arguments.
   */
  public function configurationProvider() {
    return [
      [
        [
          'pxpay_user_id' => 'foo',
          'pxpay_key' => 'bar',
          'pxpay_ref_prefix' => 'baz',
          'foo' => 'bar',
        ],
      ],
    ];
  }

}
