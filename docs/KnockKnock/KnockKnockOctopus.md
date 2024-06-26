# KnockKnockOctopus
_use [andy87\knock_knock\KnockKnockOctopus](../../src/KnockKnockOctopus.php);_

PHP Фасад для класса `KnockKnock` отправляющего запросы через ext cURL

<p style="text-align:center"><img src="../../assets/logo/KnockKnockOctopus_320.png" style="height:220px; width: auto" alt="KnockKnock php curl facade"/></p>

Расширяет класс [KnockKnock](../../README.md) и предоставляет доступ к "простым" методам отправки запросов через ext cURL

Доступные методы:
- `get( string $path, array $params = [] )` - отправка GET запроса
- `post( string $path, array $params = [] )` - отправка POST запроса
- `patch( string $path, array $params = [] )` - отправка PATCH запроса
- `put( string $path, array $params = [] )` - отправка PUT запроса
- `delete( string $path, array $params = [] )` - отправка DELETE запроса
- `head( string $path, array $params = [] )` - отправка HEAD запроса
- `options( string $path, array $params = [] )` - отправка OPTIONS запроса
- `trace( string $path, array $params = [] )` - отправка TRACE запроса

### Пример использования
Ниже примеры абстрактных эндпоинтов для различных методов HTTP запросов

#### GET
Получения профиля пользователя
```php
$knockKnockOctopus = new KnockKnockOctopus([
    Request::HOST => 'https://api.example.com',
]);

$response = $knockKnockOctopus->get( '/profile', [ 'id' => 806034 ] );
```

#### POST
Создания новой новости
```php
$response = $knockKnockOctopus
    ->post( '/new', [ 
        'name' => 'Новая новость',
        'content' => 'Текст новости' 
    ]);
```
#### PATCH
Обновления данных поста
```php
$response = $knockKnockOctopus
    ->patch( '/post', [ 
        'created_at' => '1987-09-08 12:00:00',
        'title' => 'Важное обновление'
        // ...
    ]);
```

#### PUT
Обновления части данных поста
```php
$response = $knockKnockOctopus
    ->put( '/post', [ 
        'created_at' => '1987-09-08 12:00:00',
        'title' => 'Важное обновление' 
    ]);
```

#### DELETE
Удаление поста
```php
$response = $knockKnockOctopus
    ->delete( '/post', [ 'id' => 806034 ]);
```

#### HEAD
Получения заголовков поста
```php
$response = $knockKnockOctopus
    ->head( '/post', [ 'id' => 806034 ]);
```
#### OPTIONS
Получения доступных методов поста
```php
$response = $knockKnockOctopus
    ->options( '/post', [ 'id' => 806034 ]);
```
#### TRACE
Получения ответного сообщения от сервера
```php
$response = $knockKnockOctopus->trace( '/post' );
```