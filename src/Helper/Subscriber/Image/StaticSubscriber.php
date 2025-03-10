<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Helper\Subscriber\Image;

use Ivory\GoogleMap\Helper\Event\StaticMapEvents;
use Ivory\GoogleMap\Helper\Subscriber\DelegateSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class StaticSubscriber implements DelegateSubscriberInterface
{
    /**
     * @param string $eventName
     */
    public function handle(Event $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        $dispatcher = LegacyEventDispatcherProxy::decorate($eventDispatcher);

        $delegates = static::getDelegatedSubscribedEvents();

        if (isset($delegates[$eventName]) && $dispatcher !== null) {
            foreach ($delegates[$eventName] as $delegate) {
                $dispatcher->dispatch($event, $delegate);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events = [];

        foreach (array_keys(static::getDelegatedSubscribedEvents()) as $event) {
            $events[$event] = 'handle';
        }

        return $events;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDelegatedSubscribedEvents()
    {
        return [
            StaticMapEvents::HTML => [
                StaticMapEvents::CENTER,
                StaticMapEvents::FORMAT,
                StaticMapEvents::SCALE,
                StaticMapEvents::SIZE,
                StaticMapEvents::STYLE,
                StaticMapEvents::TYPE,
                StaticMapEvents::EXTENDABLE,
                StaticMapEvents::ZOOM,
                StaticMapEvents::MARKER,
                StaticMapEvents::POLYLINE,
                StaticMapEvents::ENCODED_POLYLINE,
                StaticMapEvents::KEY,
            ],
        ];
    }
}
