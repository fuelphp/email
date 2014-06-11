<?php
/**
 * @package   Fuel\Email
 * @version   2.0
 * @author    Fuel Development Team
 * @license   MIT License
 * @copyright 2010 - 2014 Fuel Development Team
 * @link      http://fuelphp.com
 */

namespace Fuel\Email;

use Codeception\TestCase\Test;

/**
 * Tests for Message
 *
 * @package Fuel\Email
 * @author  Fuel Development Team
 *
 * @coversDefaultClass Fuel\Email\Message
 */
class MessageTest extends Test
{
	/**
	 * @var Fuel\Email\Message
	 */
	protected $object;

	public function _before()
	{
		$this->object = new Message;
	}

	/**
	 * @covers ::getFrom
	 * @covers ::setFrom
	 * @group  Email
	 */
	public function testFrom()
	{
		$from = \Mockery::mock('Fuel\\Email\\Address');

		$this->assertSame($this->object, $this->object->setFrom($from));

		$this->assertEquals($from, $this->object->getFrom());
	}

	/**
	 * @covers ::addRecipient
	 * @covers ::getRecipients
	 * @covers ::getRecipientsOfType
	 * @covers ::clearRecipients
	 * @group  Email
	 */
	public function testRecipient()
	{
		$recipient = \Mockery::mock('Fuel\\Email\\Recipient');
		$recipient->shouldReceive('isType')
			->andReturn(true);

		$this->assertSame($this->object, $this->object->addRecipient($recipient));

		$this->assertEquals([$recipient], $this->object->getRecipients());

		$this->assertEquals([$recipient], $this->object->getRecipientsOfType('to'));

		$this->assertSame($this->object, $this->object->clearRecipients());

		$this->assertEquals([], $this->object->getRecipients());
	}

	/**
	 * @covers ::addReplyTo
	 * @covers ::getReplyTo
	 * @covers ::clearReplyTo
	 * @group  Email
	 */
	public function testReplyTo()
	{
		$replyTo = \Mockery::mock('Fuel\\Email\\Address');

		$this->assertSame($this->object, $this->object->addReplyTo($replyTo));

		$this->assertEquals([$replyTo], $this->object->getReplyTo());

		$this->assertSame($this->object, $this->object->clearReplyTo());

		$this->assertEquals([], $this->object->getReplyTo());
	}

	/**
	 * @covers ::getSubject
	 * @covers ::setSubject
	 * @group  Email
	 */
	public function testSubject()
	{
		$subject = 'subject';

		$this->assertSame($this->object, $this->object->setSubject($subject));

		$this->assertEquals($subject, $this->object->getSubject());
	}

	/**
	 * @covers ::getBody
	 * @covers ::setBody
	 * @covers ::htmlBody
	 * @covers ::getAltBody
	 * @covers ::setAltBody
	 * @group  Email
	 */
	public function testBody()
	{
		$body = 'body';

		$this->assertSame($this->object, $this->object->setBody($body));

		$this->assertEquals($body, $this->object->getBody());

		$this->assertSame($this->object, $this->object->setAltBody($body));

		$this->assertEquals($body, $this->object->getAltBody());

		$this->assertEquals('plain', $this->object->getType());

		$this->assertSame($this->object, $this->object->htmlBody($body));

		$this->assertEquals($body, $this->object->getAltBody());

		$this->assertEquals('html', $this->object->getType());
	}

	/**
	 * @covers ::attach
	 * @covers ::getAttachments
	 * @covers ::clearAttachments
	 * @covers ::hasAttachments
	 * @group  Email
	 */
	public function testAttachment()
	{
		$attachment = \Mockery::mock('Fuel\\Email\\Attachment');

		$this->assertSame($this->object, $this->object->attach($attachment));

		$this->assertEquals([$attachment], $this->object->getAttachments());

		$this->assertTrue($this->object->hasAttachments());

		$this->assertSame($this->object, $this->object->clearAttachments());

		$this->assertEquals([], $this->object->getAttachments());

		$this->assertFalse($this->object->hasAttachments());
	}

	/**
	 * @covers ::getPriority
	 * @covers ::setPriority
	 * @covers ::getPriorityName
	 * @covers ::getPriorities
	 * @group  Email
	 */
	public function testPriority()
	{
		$priority = 'HIGHEST';

		$this->assertSame($this->object, $this->object->setPriority(Message::HIGHEST));

		$this->assertEquals($priority, $this->object->getPriority());

		$this->assertTrue(array_key_exists($priority, $this->object->getPriorities()));
	}

	/**
	 * @covers            ::getPriorityName
	 * @expectedException InvalidArgumentException
	 * @group             Email
	 */
	public function testInvalidPriority()
	{
		$this->object->getPriorityName(0);
	}

	/**
	 * @covers ::addHeader
	 * @covers ::getHeaders
	 * @covers ::clearHeaders
	 * @group  Email
	 */
	public function testHeaders()
	{
		$header = 'X-Test';
		$value = 'Header Value';

		$this->assertSame($this->object, $this->object->addHeader($header, $value));

		$this->assertEquals([$header => $value], $this->object->getHeaders());

		$this->assertSame($this->object, $this->object->clearHeaders());

		$this->assertEquals([], $this->object->getHeaders());

		$this->assertSame($this->object, $this->object->addHeader([$header => $value]));
	}

	/**
	 * @covers            ::addHeader
	 * @expectedException InvalidArgumentException
	 * @group             Email
	 */
	public function testHeadersFailure()
	{
		$this->object->addHeader('X-Test');
	}

	/**
	 * @covers ::getType
	 * @covers ::setType
	 * @group  Email
	 */
	public function testType()
	{
		$this->assertSame($this->object, $this->object->setType(Message::HTML));

		$this->assertEquals(Message::HTML, $this->object->getType());
	}

	/**
	 * @covers            ::setType
	 * @expectedException InvalidArgumentException
	 * @group             Email
	 */
	public function testInvalidType()
	{
		$this->object->setType('invalid');
	}
}
