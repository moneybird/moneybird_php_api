<?php

namespace Moneybird;

require_once dirname(__FILE__) . '/../ApiConnector.php';

/**
 * Test class for Invoice.
 * Generated by PHPUnit on 2012-07-15 at 12:56:35.
 */
class XmlMapperTest extends \PHPUnit_Framework_TestCase {

	protected static $config;
	protected static $contact;
	protected static $taxRateId;
	protected static $taxRateIdPurchase;

	/**
	 * @var ApiConnector
	 */
	protected $apiConnector;

	public static function setUpBeforeClass() {
		include ('config.php');
		require_once dirname(__FILE__) . '/subclasses.php';

		self::$config = $config;
		$transport = getTransport($config);
		$mapper = new XmlMapper();
		$connector = new ApiConnector($config['clientname'], $transport, $mapper);

		$rates = $connector->getService('TaxRate')->getAll('sales');
		self::$taxRateId = current($rates)->id;
		$incomingRates = $connector->getService('TaxRate')->getAll('purchase');
		self::$taxRateIdPurchase = current($incomingRates)->id;
		self::$contact = $connector->getService('Contact')->getById($config['testcontact']);
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		include ('config.php');

		$transport = getTransport($config);
		$mapper = new XmlMapper();
		$this->apiConnector = new ApiConnector($config['clientname'], $transport, $mapper);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @coversNothing
	 */
	public function testToXmlContact() {
		$contact = new \SubclassContact;
		$contact->setData(array(
			'address1' => 'Address line 1',
			'address2' => 'Address line 2',
			'attention' => 'Attention',
			'bankAccount' => 'Bank account',
			'chamberOfCommerce' => '1234567',
			'city' => 'City name',
			'companyName' => 'My Test company',
			'contactName' => 'Contact name',
			'country' => 'Country name',
			'email' => 'email@fake.fake',
			'firstname' => 'John',
			'lastname' => 'Doe',
			'phone' => '073-1234567',
			'sendMethod' => 'email',
			'taxNumber' => '12345678B01',
			'zipcode' => '1111 AA',
		));

		$contact->save($this->apiConnector->getService('Contact'));

		$this->assertInstanceOf('Moneybird\Contact', $contact);
		$this->assertInstanceOf('\SubclassContact', $contact);
		$this->assertNotNull($contact->id);
		$this->assertGreaterThan(0, $contact->id);
	}

	/**
	 * @coversNothing
	 */
	public function testToXmlInvoice() {
		$details = new \Subclass_Invoice_Detail_Array();
		$details->append(new \Subclass_Invoice_Detail(array(
			'amount' => 5,
			'description' => 'My invoice line',
			'price' => 20,
			'taxRateId' => self::$taxRateId,
		)));
		$details->append(new \Subclass_Invoice_Detail(array(
			'amount' => 1,
			'description' => 'My second invoice line',
			'price' => 12,
			'taxRateId' => self::$taxRateId,
		)));

		$invoice = new \SubclassInvoice(array(
			'poNumber' => 'PO Number',
			'details' => $details,
			'lastname' => 'Custom lastname',
		), self::$contact);

		$invoice->save($this->apiConnector->getService('Invoice'));
		$this->assertInstanceOf('Moneybird\Invoice', $invoice);
		$this->assertInstanceOf('\SubclassInvoice', $invoice);
		$this->assertNotNull($invoice->id);
		$this->assertGreaterThan(0, $invoice->id);
	}

	/**
	 * @coversNothing
	 */
	public function testToXmlIncomingInvoice() {
		$details = new \Subclass_IncomingInvoice_Detail_Array();
		$details->append(new \Subclass_IncomingInvoice_Detail(array(
			'amount' => 5,
			'description' => 'My invoice line',
			'price' => 20,
			'taxRateId' => self::$taxRateIdPurchase,
		)));
		$details->append(new \Subclass_IncomingInvoice_Detail(array(
			'amount' => 1,
			'description' => 'My second invoice line',
			'price' => 12,
			'taxRateId' => self::$taxRateIdPurchase,
		)));

		$invoice = new \SubclassIncomingInvoice(array(
			'invoiceId' => '2012-'.time(),
			'invoiceDate' => new \DateTime(),
			'details' => $details,
			'currency' => 'EUR',
		), self::$contact);

		$invoice->save($this->apiConnector->getService('IncomingInvoice'));
		$this->assertInstanceOf('Moneybird\IncomingInvoice', $invoice);
		$this->assertInstanceOf('\SubclassIncomingInvoice', $invoice);
		$this->assertNotNull($invoice->id);
		$this->assertGreaterThan(0, $invoice->id);
	}

	/**
	 * @coversNothing
	 */
	public function testToXmlEstimate() {
		$details = new \Subclass_Estimate_Detail_Array();
		$details->append(new \Subclass_Estimate_Detail(array(
			'amount' => 5,
			'description' => 'My estimate line',
			'price' => 20,
			'taxRateId' => self::$taxRateId,
		)));
		$details->append(new \Subclass_Estimate_Detail(array(
			'amount' => 1,
			'description' => 'My second estimate line',
			'price' => 12,
			'taxRateId' => self::$taxRateId,
		)));

		$estimate = new \SubclassEstimate(array(
			'poNumber' => 'PO Number',
			'details' => $details,
			'lastname' => 'Custom lastname',
		), self::$contact);

		$estimate->save($this->apiConnector->getService('Estimate'));
		$this->assertInstanceOf('Moneybird\Estimate', $estimate);
		$this->assertInstanceOf('\SubclassEstimate', $estimate);
		$this->assertNotNull($estimate->id);
		$this->assertGreaterThan(0, $estimate->id);
	}

	/**
	 * @coversNothing
	 */
	public function testToXmlRecurringTemplate() {
		$details = new \Subclass_RecurringTemplate_Detail_Array();
		$details->append(new \Subclass_RecurringTemplate_Detail(array(
			'amount' => 5,
			'description' => 'My template line',
			'price' => 20,
			'taxRateId' => self::$taxRateId,
		)));
		$details->append(new \Subclass_RecurringTemplate_Detail(array(
			'amount' => 1,
			'description' => 'My second template line',
			'price' => 12,
			'taxRateId' => self::$taxRateId,
		)));

		$template = new \SubclassRecurringTemplate(array(
			'poNumber' => 'PO Number',
			'details' => $details,
			'frequencyType' => RecurringTemplate::FREQUENCY_YEAR,
		), self::$contact);

		$template->save($this->apiConnector->getService('RecurringTemplate'));
		$this->assertInstanceOf('Moneybird\RecurringTemplate', $template);
		$this->assertInstanceOf('\SubclassRecurringTemplate', $template);
		$this->assertNotNull($template->id);
		$this->assertGreaterThan(0, $template->id);
	}

	public function testToXmlCurrentSession() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	public function testToXmlTaxRate() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	public function testToXmlProduct() {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

}