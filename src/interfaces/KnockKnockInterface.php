<?php

namespace andy87\knock_knock\interfaces;

use andy87\knock_knock\core\KnockRequest;
use andy87\knock_knock\core\KnockResponse;

/**
 * Interface KnockSender
 *
 * @package andy87\knock_knock\interfaces
 */
interface KnockKnockInterface
{
    /** @var string  */
    public const EVENT_AFTER_INIT = 'afterInit';
    public const EVENT_CONSTRUCT_REQUEST = 'constructRequest';
    /** @var string  */
    public const EVENT_BEFORE_SEND = 'beforeSend';
    /** @var string  */
    public const EVENT_CONSTRUCT_RESPONSE = 'constructResponse';
    /** @var string  */
    public const EVENT_AFTER_SEND = 'afterSend';


    /**
     * @param string $host
     * @param array $commonKnockRequestParams
     */
    public function __construct( string $host, array $commonKnockRequestParams = [] );

    /**
     * @param array $commonKnockRequestParams
     *
     * @return self
     */
    public static function getInstance( array $commonKnockRequestParams ): self;

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $knockRequestConfig
     *
     * @return KnockRequest
     */
    public function constructRequest( string $method, string $endpoint, array $knockRequestConfig = [] ): KnockRequest;

    /**
     * @param array $responseParams
     * @param ?KnockRequest $knockRequest
     *
     * @return KnockResponse
     */
    public function constructResponse( array $responseParams, ?KnockRequest $knockRequest = null ): KnockResponse;

    /**
     * @param KnockRequest $knockRequest
     * @param array $options
     *
     * @return self
     */
    public function setupRequest( KnockRequest $knockRequest, array $options = [] ): self;

    /**
     * @param array $fakeResponse
     *
     * @return KnockResponse
     */
    public function send( array $fakeResponse = [] ): KnockResponse;

    /**
     * @param string $event
     * @param callable $callbacks
     *
     * @return ?bool
     */
    public function on( string $event, callable $callbacks ): ?bool;

    /**
     * @param string $event
     * @param mixed $data
     *
     * @return mixed
     */
    public function event( string $event, mixed $data ): mixed;
}