<?php

namespace Resync\Slack;

use GuzzleHttp\Client as Guzzle;
use Resync\Slack\InvalidPlaceException;

class SlackClient
{
    /**
     * The Guzzle instance to make HTTP requests
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Injects the Guzzle client
     *
     * @param Guzzle $guzzle
     */
    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Send a slack message to the #channel or @username
     *
     * @param string $channel
     * @param string $message
     * @return void
     */
    public function send($channel, $message)
    {
        if (! config('slack.enabled')) {
            return true;
        }

        if (! preg_match('/^[@#]/', $channel)) {
            throw new InvalidPlaceException("The channel must start with @ or #, \"{$channel}\" was given");
        }

        $this->guzzle->post(config('slack.web_hook'), [
            'json' => [
                'text' => $message,
                'channel' => $channel
            ]
        ]);
    }
}
