<?php

namespace DesignPatterns\Structural;

/**
 * Facade pattern provides a simplified interface to a complex subsystem (hides the complexity)
 */

class Validator
{
    public function isValidMail($userMail)
    {
        if (!filter_var($userMail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}

class User
{
    public function create($userName, $userPass, $userMail)
    {
        // some user registration logic ..
        echo "User '{$userName}' was crated..\n";
    }
}

class Mail
{
    protected $to;
    protected $subject;

    public function to($mailAddress)
    {
        $this->to = $mailAddress;
        return $this;
    }

    public function subject($mailSubject)
    {
        $this->subject = $mailSubject;
        return $this;
    }

    public function send()
    {
        // sending of mail ..
        echo "Email to {$this->to} with subject '{$this->subject}' was sent..\n";
    }
}

class SignUpFacade
{
    private $validate;
    private $user;
    private $mail;

    public function __construct()
    {
        $this->validate = new Validator();
        $this->user = new User();
        $this->mail = new Mail();
    }

    /**
     * Facade hides the complexity
     */
    public function signUpUser($userName, $userPass, $userMail)
    {
        if (!$this->validate->isValidMail($userMail)) {
            throw new \Exception('Invalid email');
        }

        $this->user->create($userName, $userPass, $userMail);
        $this->mail->to($userMail)->subject('Welcome')->send();
    }
}

# Client code example
// We simple call signUpUser() method and don't care about details of registration process
try {
    (new SignUpFacade())->signUpUser('Sergey', '123456', 'test@mail.com');
} catch (\Exception $e) {
    echo "User registration error";
}

/* Output:
User 'Sergey' was crated..
Email to test@mail.com with subject 'Welcome' was sent.. */