Проект на Docker для ЕСИА при помощи PHP.

## Технологии

- Docker. Если на вашем ПК его нет, можете установить по [инструкции](https://docs.docker.com/engine/install/).
- PHP 8
- Nginx
- OpenSSL, с поддержкой ГОСТ (gost engine)

## Настройка сертификатов

При попытке получить данные пользователя с портала ЕСИА может возникнуть ошибка:

```
Fatal error: Uncaught GuzzleHttp\Exception\RequestException:
cURL error 60: SSL certificate problem: unable to get local issuer certificate
(see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://esia-portal1.test.gosuslugi.ru/aas/oauth2/te
```

**Важно:** Для устранения этой ошибки отключить проверку сертификатов, т.к. это самый простой способ:

```php
$client = new GuzzleHttpClient(
    new Client([
        'verify' => false,
    ])
);
```

Также ошибку можно устранить, импортировав сертификат тестового стенда ЕСИА в хранилище доверенных сертификатов в контейнере `php-fpm`:

1. Заходим в работающий контейнер php-fpm: `docker compose exec php-fpm sh`
2. Получаем информацию о сертификате сервера
   ```bash
   openssl s_client esia-portal1.test.gosuslugi.ru:443
   ```
   В выводе этой команды нужно найти сертификат, его примерный вид:
   ```
   -----BEGIN CERTIFICATE-----
   MIIHrTCCB1qgAwIBAgILAN/pVOMAAAAABiYwCgYIKoUDBwEBAwIwggE7MSEwHwYJ
   ...
   wzkYntwevFT0QnGIf6vUFRQJhadWgnX+OdPq4e3oq/2cNOi5eeYUDDpDHud1LOL/
   yg==
   -----END CERTIFICATE-----
   ```
3. Этот сертификат копируем в файл, к примеру `esia-test.crt`.
4. Помещаем файл в `/usr/local/share/ca-certificates`. Это папка для локальных CA сертификатов, которые должны иметь расширение `.crt`.
5. Выполняем команду для импорта сертификата: `update-ca-certificates`

### Полезная информация

- В [Методических рекомендациях](https://digital.gov.ru/ru/documents/6186/) говорится, что сертификаты тестовой и
  продуктивной сред ЕСИА, используемые для формирования электронных подписей ответов как поставщика, доступны
  по ссылке: http://esia.gosuslugi.ru/public/esia.zip
- Скопируем сертификаты с сайта Госуслуг и распакуем:
  ```
  cd /usr/local/src
  wget --no-check-certificate https://esia.gosuslugi.ru/public/esia.zip
  unzip esia.zip
  ```
- Проверка корректности работы:

  ```
  openssl s_client -CApath /etc/ssl/certs -connect esia.gosuslugi.ru:443

  openssl s_client -CApath /etc/ssl/certs -connect esia-portal1.test.gosuslugi.ru:443
  ```

  В случае ошибки вы увидите: `Verify return code: 21 (unable to verify the first certificate)`, в случае успеха:
  `Verify return code: 0 (ok)`.
