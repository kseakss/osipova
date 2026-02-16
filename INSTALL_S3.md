# Установка пакета S3

## Проблема
Ошибка: `Класс "League\Flysystem\AwsS3V3\PortableVisibilityConverter" не найден`

## Решение

### Шаг 1: Установите пакет S3

Выполните в терминале в папке проекта `lara`:

```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

Или если composer не найден глобально:

```bash
php composer.phar require league/flysystem-aws-s3-v3 "^3.0"
```

### Шаг 2: Проверьте установку

После установки убедитесь, что пакет появился в `vendor/league/flysystem-aws-s3-v3/`

### Шаг 3: Очистите кэш

```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

### Шаг 4: Настройте .env

Убедитесь, что в `.env` есть все настройки S3:

```env
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=L9IOBCEF0DVY5VPQNBOH
AWS_SECRET_ACCESS_KEY=B8M304AkKBaxDFVEIdU1xo0KYDRsd6VCaB0hjToZ
AWS_DEFAULT_REGION=ru1
AWS_BUCKET=108441407d0b-college
AWS_URL=
AWS_ENDPOINT=https://s3.ru1.storage.beget.cloud
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_SSL_VERIFY=true
```

### Альтернативное решение (если пакет не устанавливается)

Если возникают проблемы с установкой пакета, можно временно использовать локальное хранилище:

В `.env` измените:
```env
FILESYSTEM_DISK=local
```

Затем выполните:
```bash
php artisan config:clear
```

Файлы будут сохраняться локально в `storage/app/private/submissions/` до тех пор, пока не настроите S3.

