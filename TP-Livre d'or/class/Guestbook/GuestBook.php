<?php 

namespace App\GuestBook;

// use Florian\Message; => Pas utile car par défault php va chercher la classe dans le dossier du name space qui est déclaré (soit ici Florian\Message)

require_once 'Message.php';

class GuestBook {

    public $file;

    public function __construct(string $file) 
    {
        $directory = dirname($file);

        if(!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if(!file_exists($file)) {
            touch($file);
        }

        $this->file = $file;
    }

    public function addMessage(Message $message): void
    {
        file_put_contents($this->file, $message->toJSON() . PHP_EOL, FILE_APPEND);
    }

    public function getMessages(): array 
    {
        // trim permet d'enlever notamment la dernière ligne vide qui se générait dans le tableau
        $content = trim(file_get_contents($this->file));
        $lines = explode(PHP_EOL, $content);
        $messages = [];
        
        
        if($content != "") {
            foreach($lines as $line) {
                $messages[] = Message::fromJSON($line);
            }
            return array_reverse($messages);
        } else {
            return [];
        }
    }
}