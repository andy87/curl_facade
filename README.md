# KnockKnock

PHP Фасад\Адаптер для отправки запросов через ext cURL

![IN PROGRESS](http://www.bc-energy.it/wp-content/uploads/2013/08/work-in-progress.png)

# KnockKnock
Получение объекта/экземпляра класса и его настройка

### Нативный 
```php
$knockKnock = new KnockKnock([
    KnockRequest::HOST => 'some.domain',
    KnockRequest::CONTENT_TYPE => KnockRequest::CONTENT_TYPE_FORM,
]);
```

### Singleton
```php
$knockKnock = KnockKnock::getInstance([
    KnockRequest::HOST => 'domain.zone',
    KnockRequest::PROTOCOL => 'http',
    KnockRequest::HEADER => KnockRequest::CONTENT_TYPE_JSON,
])->useAuthorization( 'myToken', KnockKnock::TOKEN_BEARER );
```
`getInstance( array $knockKnockConfig = [] ): self`


## Настройка параметров запросов
Доступны отдельные методы для настройки некоторых, отдельных, свойств,
которые в дальнейшем будут передаваться всем запросам отправляемыми объектом `$knockKnock`

Все подобные методы возвращают `static` объект / экземпляр класса `KnockKnock`

Отдельными вызовами.
```php
$knockKnock->useAuthorization( 'myToken', KnockKnock::TOKEN_BEARER );
$knockKnock->useConfigHeaders(['api-secret' => 'secretKey12']);
$knockKnock->useConfigContentType(KnockRequest::CONTENT_TYPE_MULTIPART);
```

Цепочка вызовов:
```php
$knockKnock
    ->useAuthorization('token', KnockKnock::TOKEN_BASIC )
    ->useConfigHeaders(['api-secret' => 'secretKey23'])
    ->useConfigContentType(KnockRequest::CONTENT_TYPE_MULTIPART);

$bearer = $knockKnock->getAuthorization(); // string
```


# Обработчики событий
Задать обработчики событий
 - после создания объекта knockKnock
 - после создания объекта запроса
 - перед отправкой запроса
 - после создания объекта ответа
 - после получения ответа

```php
$knockKnock->setupCallback([
    KnockKnock::EVENT_AFTER_CONSTRUCT => fn( static $knockKnock ) => {
        // создание объекта knockKnock
    },
    KnockKnock::EVENT_CREATE_REQUEST => fn( static $knockKnock, KnockRequest $knockRequest ) => {
        // создание объекта запроса
    },
    KnockKnock::EVENT_BEFORE_SEND => fn(  static $knockKnock, KnockRequest $knockRequest ) => {
        // отправка запроса
    },
    KnockKnock::EVENT_CREATE_RESPONSE => fn( static $knockKnock, KnockResponse $knockResponse ) => {
        // создание объекта ответа
    },
    KnockKnock::EVENT_AFTER_SEND => fn( static $knockKnock, KnockResponse $knockResponse ) => {
        // получение ответа
    }
]);
```
`setupCallback( array $callbacks ): self`


# KnockRequest, Запрос

Нативное создание объекта / экземпляра класса с данными для конкретного запроса
```php
$knockRequest = new KnockRequest( 'info/me', [
    KnockRequest::METHOD => KnockMethod::POST,
    KnockRequest::DATA => [ 'client_id' => 34 ],
    KnockRequest::HEADERS => [ 'api-secret-key' => 'secretKey34' ],
    KnockRequest::CURL_OPTIONS => [ CURLOPT_TIMEOUT => 10 ],
    KnockRequest::CURL_INFO => [
        CURLINFO_CONTENT_TYPE,
        CURLINFO_HEADER_SIZE,
        CURLINFO_TOTAL_TIME
    ],
    KnockRequest::CONTENT_TYPE => KnockContentType::FORM_DATA,
]);
```

Доступно создание - через метод фасада (с вызовом callback функции )
```php
$knockRequest = $knockKnock->constructKnockRequest( 'info/me', [
    KnockRequest::METHOD => KnockMethod::POST,
    KnockRequest::DATA => [ 'client_id' => 45 ],
    KnockRequest::HEADERS => [ 'api-secret-key' => 'secretKey45' ],
    KnockRequest::CURL_OPTIONS => [ CURLOPT_TIMEOUT => 10 ],
    KnockRequest::CURL_INFO => [
        CURLINFO_CONTENT_TYPE,
        CURLINFO_HEADER_SIZE,
        CURLINFO_TOTAL_TIME
    ],
    KnockRequest::CONTENT_TYPE => KnockContentType::FORM_DATA,
]);
```
`constructKnockRequest( string $url, array $paramsKnockRequest = [] ): KnockRequest`

### Назначение/Изменение/Получение отдельных параметров запроса (set/get)

Таблица set/get методов для взаимодействия с отдельными свойствами запроса

| Параметр | Сеттер                                | Геттер |
| --- |---------------------------------------| --- |
| Протокол | setProtocol( string $protocol )       | getProtocol(): string |
| Хост | setHost( string $host )               | getHost(): string |
| URL | setUrl( string $url )                 | getUrl(): string |
| Метод | setMethod( string $method )           | getMethod(): string |
| Заголовки | setHeaders( array $headers )          | getHeaders(): array |
| Тип контента | setContentType( string $contentType ) | getContentType(): string |
| Данные | setData( mixed $data )                | getData(): mixed |
| Опции cURL | setCurlOptions( array $curlOptions )  | getCurlOptions(): array |
| Информация cURL | setCurlInfo( array $curlInfo )        | getCurlInfo(): array |

```php
$knockRequest = $knockKnock->constructKnockRequest('info/me');

$knockRequest->setMethod(KnockMethod::GET);
$knockRequest->setData(['client_id' => 67]);
$knockRequest->setHeaders(['api-secret-key' => 'secretKey67']);
$knockRequest->setCurlOptions([
    CURLOPT_TIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true
]);
$knockRequest->setCurlInfo([
    CURLINFO_CONTENT_TYPE,
    CURLINFO_HEADER_SIZE,
    CURLINFO_TOTAL_TIME
]);
$knockRequest->setContentType(KnockContentType::JSON);

$protocol = $knockRequest->getPrococol(); // string
$host = $knockRequest->getHost(); // string
// ... аналогичным образом доступны и другие подобные методы для получения свойств запроса
```

### Микс параметров создаваемого запроса с данными переданными опционально

Можно создать запрос, на основе уже созданного объекта 
и дополнительным аргументом передать уникальные собственные параметры.
```php
$knockKnock->setupRequest( $knockRequest, [
    KnockRequest::HOST => 'domain.zone',
    KnockKnock::BEARER => 'token-bearer-2',
    KnockKnock::HEADERS => [
        'api-secret' => 'secretKey78'
    ],
]);
```
`setupRequest( KnockRequest $knockRequest, array $options = [] ): self`


## KnockResponse: Ответ

Конструктор KnockResponse с вызовом callback функции, если она установлена
```php
$knockResponse = $knockKnock->constructKnockResponse([
    'id' => 806034,
    'name' => 'and_y87'
], $knockRequest );
```
`constructKnockResponse( array $KnockResponseParams, ?KnockRequest $knockRequest = null ): KnockResponse`
 
## KnockResponse: Отправка запроса и получение ответа

Получение ответа отправленного запроса и вызов callback функции, если она установлена
```php
$knockKnock->setupRequest( $knockRequest );
$knockResponse = $knockKnock->send();
```
`send( array $prepareKnockResponseParams = [] ): KnockResponse`
возвращает объект/экземпляр класса KnockResponse

Получение ответа с отправкой запроса - цепочкой вызовов
```php
$knockResponse = $knockKnock->setRequest( $knockRequest )->send(); // return KnockResponse
```


## Отправка запроса с фэйковым ответом

Цепочка вызовов, возвращает подготовленный ответ и вызывает callback функцию, если она установлена
```php
// параметры возвращаемого ответа
$prepareFakeKnockResponseParams = [
    KnockResponse::HTTP_CODE => 200,
    KnockResponse::CONTENT => [
        'id' => 806034,
        'name' => 'and_y87'
    ],
];

$knockResponse = $knockKnock->setupRequest( $knockRequest )->send( $prepareFakeKnockResponseParams );
```
объект `$knockResponse` будет содержать данные переданные в аргументе `$prepareFakeKnockResponseParams`


## Данные в ответе

Задаются данные
```php
$knockResponse = $knockKnock->setupRequest( $knockRequest )->send();

$knockResponse
    ->setHttpCode(200)
    ->setContent('{"id" => 8060345, "nickName" => "and_y87"}');
```
Если данные уже установлены, вывозится `Exception`, для замены используется `replace`

Подменяются данные
```php
$knockResponse = $knockKnock->setupRequest( $knockRequest )->send();

$knockResponse
    ->replace(KnockResponse::HTTP_CODE, 200)
    ->replace(KnockResponse::CONTENT, '{"id" => 8060345, "nickName" => "and_y87"}');
```

## Данные запроса из ответа

Получение массива с данными ответа
```php
// Получение опций запроса (  KnockRequest::CURL_OPTIONS )
$curlOptions =  $knockResponse->get( KnockResponse::CURL_OPTIONS ); // return array

// Получение данных о запросе ( KnockRequest::CURL_INFO )
$curlInfo =  $knockResponse->get( KnockResponse::CURL_INFO ); // return array

```

# Custom реализация

Custom реализация Базового класса, к примеру с добавлением логирования работающим "под капотом"
```php
class KnockKnockYandex implements KnockKnockInterface
{
    private const AFTER_CREATE_REQUEST = 'afterCreateRequest';
    private const LOGGER = 'logger';



    private string $host = 'https://api.yandex.ru/'
    private string $contentType = KnockContentType::JSON

    private YandexLogger $logger;



    public function init()
    {
        $this->event[self::AFTER_CREATE_REQUEST] = fn( KnockRequest $knockRequest ) => 
        {
            $this->addYandexLog([
                'url' => $knockRequest->getUrl(),
                'method' => $knockRequest->getMethod(),
                'data' => $knockRequest->getData(),
                'headers' => $knockRequest->getHeaders(),
            ]);
        };

        $this->event[self::EVENT_AFTER_SEND] = fn( KnockResponse $knockResponse ) => 
        {
            $knockRequest = $knockResponse->getRequest();

            $this->addYandexLog([
                'url' => $knockRequest->getUrl(),
                'method' => $knockRequest->getMethod(),
                'data' => $knockRequest->getData(),
                'headers' => $knockRequest->getHeaders(),
            ]);
        };
    }

    public function createRequest( string $url, array $requestParams ): KnockRequest
    {
        $knockRequest = new KnockRequest( $url, $requestParams );

        $this->event( self::AFTER_CREATE_REQUEST, $knockRequest );

        return $knockRequest;
    }

    private function addYandexLog( array $params ) 
    {
        $logger->log($params);
    }

}

```
Пример использования custom реализации
```php

$knockKnockYandex = KnockKnockYandex::getInstanse([
    KnockKnock::LOGGER => new YandexLogger(),
]);

$knockResponse = $knockKnockYandex->setupRequest('profile', [ 
    KnockRequest::METHOD => KnockMethod::PATCH,
    KnockRequest::DATA => [ 'city' => 'Moscow' ],
]); // Логирование `afterCreateRequest`

$knockResponse = $knockKnockYandex->send(); // Логирование `afterSend`

```

# Расширения

Расширения работают через "магию", поэтому лучше описывать их в анотациях класса

Реализация расширения
```php
/**
 * @method static setupCorrectHost( KnockKnock $knockKnock )
 */
class VkontakteKnockKnock extends KnockKnock
{
    /** @var callable[] */
    private array $extensions = [];



    public function init()
    {
        $this->addExtension( 'setupCorrectHost', fn( $knockKnock ) => 
        {
            switch ($knockKnock->host)
            {
                case 'vk.com':
                    $knockKnock->useHeaders(['Host' => 'client.ru']);
                    break;

                case 'api.vk.com':
                    $knockKnock->useAuthorization('myToken', KnockKnock::TOKEN_BEARER );
                    break;
            }
        });
    }
}
```

Использование расширения
```php
$vkontakteKnockKnock = VkontakteKnockKnock::getInstance([
    KnockRequest::HOST => 'api.vk.com',
]);

$vkontakteKnockKnock->setupCorrectHost();

$knockResponse = $vkontakteKnockKnock->setRequest('profile', [
    KnockRequest::METHOD => KnockMethod::PATCH,
    KnockRequest::DATA => [ 'homepage' => 'www.andy87.ru' ],
])->send();

```
