<?php

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\MessageEvents;
use Symfony\Component\Mime\Email;

require_once __DIR__.'/vendor/autoload.php';

$messageEvents = new MessageEvents();
$messageEvents->add(new MessageEvent($message1 = (new Email())->to('alice@example.com'), Envelope::create($message1), 'null://null'));
$messageEvents->add(new MessageEvent($message2 = (new Email())->to('bob@example.com'), Envelope::create($message2), 'null://null'));

var_dump($messageEvents); // Comment/uncomment to trigger the bug

var_dump('headers_before', $messageEvents->getEvents()[0]->getMessage()->getHeaders() === $messageEvents->getEvents()[1]->getMessage()->getHeaders());

$ser = igbinary_serialize($messageEvents);

$messageEvents = igbinary_unserialize($ser);

// should dump "false", but dumps "true" the "var_dump($messageEvents)" is not commented
var_dump('headers_after', $messageEvents->getEvents()[0]->getMessage()->getHeaders() === $messageEvents->getEvents()[1]->getMessage()->getHeaders());
