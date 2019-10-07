<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Tests\Unit\Telegram;

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class ConversationTest extends TestCase
{
    /**
     * @var Telegram
     */
    private $telegram;

    protected function setUp():void
    {

    }

    public function testConversationThatDoesntExistPropertiesSetCorrectly()
    {
        $conversation = new Conversation(123, 456);
        $this->assertAttributeE(123, 'user_id', $conversation);
        $this->assertAttributeE(456, 'chat_id', $conversation);
        $this->assertAttributeE(null, 'command', $conversation);
    }

    public function testConversationThatExistsPropertiesSetCorrectly()
    {
        $info         = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertAttributeE($info['user_id'], 'user_id', $conversation);
        $this->assertAttributeE($info['chat_id'], 'chat_id', $conversation);
        $this->assertAttributeE('command', 'command', $conversation);
    }

    public function testConversationThatDoesntExistWithoutCommand()
    {
        $conversation = new Conversation(1, 1);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    /**
     * @expectException \Longman\TelegramBot\Exception\TelegramException
     */
    public function testConversationThatDoesntExistWithCommand()
    {
        new Conversation(1, 1, 'command');
    }

    public function testNewConversationThatWontExistWithoutCommand()
    {
        TestHelpers::startFakeConversation();
        $conversation = new Conversation(0, 0);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    public function testNewConversationThatWillExistWithCommand()
    {
        $info         = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $this->assertEquals('command', $conversation->getCommand());
    }

    public function testStopConversation()
    {
        $info         = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->stop();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    public function testCancelConversation()
    {
        $info         = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->cancel();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    public function testUpdateConversationNotes()
    {
        $info                = TestHelpers::startFakeConversation();
        $conversation        = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $conversation->notes = 'newnote';
        $conversation->update();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertSame('newnote', $conversation2->notes);

        $conversation3 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertSame('newnote', $conversation3->notes);
    }
}
