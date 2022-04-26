<?php
require_once '../vendor/autoload.php';

try {

// Create the Transport
    $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
        ->setUsername('mvc_anton@mail.ru')
        ->setPassword('Eejt2ydH5s9Wn4AVS6ah')
    ;

// Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);

// Create a message
    $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom(['mvc_anton@mail.ru' => 'y.anton@eml.ru'])
        ->setTo(['y.anton@eml.ru'])
        ->setBody('Here is the message itself')
        ->attach(Swift_Attachment::fromPath('test.php'))
    ;

// Send the message
    $result = $mailer->send($message);
    var_dump(['res' => $result]);
} catch (Exception $e) {
    var_dump($e->getMessage());
    echo '<pre>' . print_r($e->getTrace(), 1);
}