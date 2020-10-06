<?php

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\MessageEvents;
use Symfony\Component\Mime\Email;

require_once __DIR__.'/vendor/autoload.php';

$messageEvents = new MessageEvents();
$messageEvents->add(new MessageEvent($message1 = (new Email())->to('alice@example.com'), Envelope::create($message1), 'null://null'));
$messageEvents->add(new MessageEvent($message2 = (new Email())->to('bob@example.com'), Envelope::create($message2), 'null://null'));

$cache = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('message_events');
$item = $cache->getItem($key = 'my_key');
$item->set($messageEvents);

var_dump($item); // Comment/uncomment to trigger the bug

$cache->save($item);

$cache = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('message_events');
$item = $cache->getItem($key);

// should dump "false"
var_dump('messages', $item->get()->getEvents()[0]->getMessage() === $item->get()->getEvents()[1]->getMessage());
// should dump "false", but dump "true" the "var_dump($item)" is not commented
var_dump('headers', $item->get()->getEvents()[0]->getMessage()->getHeaders() === $item->get()->getEvents()[1]->getMessage()->getHeaders());
