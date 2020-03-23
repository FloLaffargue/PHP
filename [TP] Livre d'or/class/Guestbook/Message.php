<?php

namespace App\GuestBook;

use \DateTime;
use \DateTimeZone;

class Message {

    const LIMIT_USERNAME = 3;
    const LIMIT_MESSAGE = 10;

    private $username;
    private $message;
    private $date;

    public static function fromJSON(string $json): Message 
    {
        $data = json_decode($json, true); // je récupére un tableau associatif
        return new Message($data['username'], $data['message'], new DateTime("@" . $data['date']));
    }

    public function toHTML(): string 
    {
        $username = htmlentities($this->username);
        $this->date->setTimezone(new DateTimeZone('Europe/Paris'));
        $date = $this->date->format('d/m/Y ) H:i');
        $message = nl2br(htmlentities($this->message));

        return $html = <<<HTML
        <p>
            <strong>$username</strong> <em>le {$date}</em>
        </p><br>
        {$message}
HTML;
    
    }

    public function __construct(string $username, string $message, ?DateTime $date = null) 
    {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date ? $date : new DateTime();
    }

    public function isValid(): bool 
    {
        // Si le tableau d'erreurs est vide,alors c'est bon
        return empty($this->getErrors());
    }

    public function getErrors(): array 
    {
        $errors = [];

        if(strlen($this->username) < self::LIMIT_USERNAME) {
            $errors['username'] = 'Le nom d\'utilisateur doit comporter 3 caractères';
        }
        if(strlen($this->message) < self::LIMIT_MESSAGE) {
            $errors['message'] = 'Le message doit comporter 10 caractères';
        }

        return $errors;
    }

    public function toJSON(): string 
    {
        return json_encode(
        [
            'username' => $this->username,
            'message' => $this->message,
            'date' => $this->date->getTimestamp()
        ]);
    }



}