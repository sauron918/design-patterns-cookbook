<?php

namespace DesignPatterns\Behavioral;

/**
 * General interface of mediators
 */
interface Mediator
{
    public function notify($sender, $event, $data);
}

/**
 * Concrete Mediator receives notices from components and knows how to react to them
 * @package DesignPatterns\Behavioral
 */
class ChatMediator implements Mediator
{
    /**
     * When something happens, component sends an notification to the mediator.
     * After receiving the notification, the mediator can either do something on his own, or redirect
     * the request to another component.
     */
    public function notify($sender, $event, $data)
    {
        if ($sender instanceof User && $event == 'sendMessage') {
            echo "{$sender->name}: $data->message\n";
        } elseif ($sender instanceof Bot && $event == 'banUser') {
            echo "User {$data->name} was banned by {$sender->name}";
        }
    }
}

/**
 * Concrete components are not directly related.
 * There is only one channel of communication - by sending notifications to the mediator.
 */
class User
{
    public $name;
    protected $mediator;

    public function __construct(string $name, Mediator $mediator)
    {
        $this->name = $name;
        $this->mediator = $mediator;
    }

    public function sendMessage(string $message)
    {
        $data = new \stdClass();
        $data->message = $message;
        $this->mediator->notify($this, 'sendMessage', $data);
    }
}

class Bot
{
    public $name = 'Bot';
    protected $mediator;
    
    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    public function banUser(User $user)
    {
        $this->mediator->notify($this, 'banUser', $user);
    }
}

# Client code example
$chat = new ChatMediator();

$john = new User('John', $chat);
$jane = new User('Jane', $chat);
$bot = new Bot($chat);

// every chat member interacts with mediator,
// but not with with each other directly
$john->sendMessage("Hi!");
$jane->sendMessage("What's up?");
$bot->banUser($john);

/* Output:
John: Hi!
Jane: What's up?
User John was banned by Bot */