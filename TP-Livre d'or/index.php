<?php
require_once 'vendor/autoload.php';

use App\Florian\{
    GuestBook as Teub,
    Message
};
// require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'Message.php';
// require_once __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'GuestBook.php';

$errors = null;
$success = false;
$file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'messages';
$guestbook = new Teub($file);


if (isset($_POST['username']) && isset($_POST['message'])) {

    $message = new Message($_POST['username'], $_POST['message']);
    
    if(!$message->isValid()) {
        $errors = $message->getErrors();

    } else {
        $guestbook->addMessage($message);
        $succes = true;
        $_POST = [];
    }
}

$messages = $guestbook->getMessages();
// var_dump($messages);
?>

<h1>Livre d'or</h1>
<?php if(!empty($errors)): ?> 
    <p>Formulaire invalide</p> 
<?php endif ?>
<?php if($success): ?> 
    <p>Merci pour votre message</p> 
<?php endif ?>

<form action="/index.php" method="POST">
    <div>
        <label for="">Nom d'utilisateur</label>
        <input type="text" name="username" value="<?php htmlentities($_POST['username'] ?? '')?>">
    </div>
    <?php if(isset($errors['username'])):?>
        <strong><?= $errors['username'] ?></strong>
    <?php endif ?>
    <div>
        <label for="">Message</label>
        <input type="text" name="message" value="<?php htmlentities($_POST['message'] ?? '')?>">
    </div>
    <?php if(isset($errors['message'])):?>
        <strong><?= $errors['message'] ?></strong>
    <?php endif ?>
    <button type="submit">Valider</button>
</form>

<?php if(!empty($messages)): ?>
    <h2>Vos messages :</h2>
    <?php foreach($messages as $message): ?>
        <?= $message->toHTML() ?>
    <?php endforeach ?>
<?php endif ?>

<?php 

?>