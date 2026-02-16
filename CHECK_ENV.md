# Проверка настроек .env для S3 Beget

Убедитесь, что в файле `.env` есть следующие строки:

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

**ВАЖНО:**
1. Убедитесь, что пакет установлен: `composer install` или `composer update`
2. После изменения .env выполните: `php artisan config:clear`
3. Если возникают проблемы с SSL, попробуйте: `AWS_SSL_VERIFY=false`

**Проверка маршрутов:**
- `/participant/` - дашборд участника (правильный маршрут)
- `/participant/submissions` - такого маршрута нет (должен быть `/participant/submissions/create` или `/participant/submissions/{id}`)

**Если ошибка "connectionfailure" на странице списка заявок:**
1. Проверьте логи: `storage/logs/laravel.log`
2. Убедитесь, что все параметры S3 в .env заполнены
3. Попробуйте временно установить `FILESYSTEM_DISK=local` для проверки

