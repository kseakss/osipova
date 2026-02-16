## Платформа «Сбор работ на конкурс»

Проект реализован на Laravel 11 и представляет собой упрощённую платформу для загрузки конкурсных работ с ролями **participant**, **jury**, **admin**, очередями и хранением файлов в S3/MinIO.

### Требования

- **PHP**: ^8.2  
- **Composer**  
- **База данных**: SQLite (по умолчанию) или любая, поддерживаемая Laravel  
- **S3-совместимое хранилище**: Amazon S3 или MinIO  

### Установка

1. **Склонировать репозиторий**

```bash
git clone <repo-url>
cd lara
```

2. **Установить зависимости**

```bash
composer install
npm install
```

3. **Скопировать `.env` и сгенерировать ключ**

```bash
cp .env.example .env   # в Windows: copy .env.example .env
php artisan key:generate
```

4. **Настроить БД**

По умолчанию используется SQLite:

- убедитесь, что существует файл `database/database.sqlite` (он создаётся автоматически скриптом composer)
- в `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/project/database/database.sqlite
```

Или настройте `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` под вашу СУБД.

5. **Настроить S3/MinIO**

В файле `.env` укажите:

```env
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
AWS_URL=
AWS_ENDPOINT=http://127.0.0.1:9000        # пример для MinIO
AWS_USE_PATH_STYLE_ENDPOINT=true          # обязательно для MinIO
```

Все файлы вложений сохраняются в диск `s3` через конфиг `config/filesystems.php`.

6. **Миграции и сиды**

```bash
php artisan migrate
php artisan db:seed
```

Будут созданы тестовые пользователи:

- admin: `admin@example.com` / `password` (роль `admin`)
- jury: `jury@example.com` / `password` (роль `jury`)
- participant: `participant@example.com` / `password` (роль `participant`)

7. **Запуск очереди**

По умолчанию используется драйвер `database` (таблицы `jobs`, `failed_jobs` уже есть в миграциях):

```bash
php artisan queue:work
```

Очередь обрабатывает:

- `ScanAttachmentJob` — проверка типа, размера и имени файла, установка статуса `scanned` или `rejected`
- `NotifyStatusChangedJob` — логирование изменения статуса `submission`

8. **Запуск приложения**

```bash
php artisan serve
```

Приложение будет доступно по адресу `http://127.0.0.1:8000`.

### Роли и доступ

- **participant**
  - создаёт `submission` в рамках активного `contest`
  - загружает до 3 файлов (pdf, zip, png, jpg; максимум 10MB каждый)
  - видит только свои submissions
  - может редактировать submission только в статусах `draft` и `needs_fix`
  - может отправлять на рассмотрение (`submitted`), если есть хотя бы один attachment со статусом `scanned`
  - может оставлять комментарии к своим submissions

- **jury**
  - видит все submissions
  - может менять статус по допустимым переходам:
    - `draft` → `submitted`
    - `submitted` → `accepted` / `rejected` / `needs_fix`
    - `needs_fix` → `submitted` / `rejected`
  - может оставлять комментарии и запрашивать доработку (`needs_fix`)

- **admin**
  - управляет конкурсами (`contests`)
  - управляет пользователями и их ролями

### Основные сущности

- **contests**
  - `id`, `title`, `description`, `deadline_at`, `is_active`, `timestamps`

- **submissions**
  - `id`, `contest_id`, `user_id`, `title`, `description`, `status`, `timestamps`
  - статусы: `draft | submitted | needs_fix | accepted | rejected`

- **submission_comments**
  - `id`, `submission_id`, `user_id`, `body`, `timestamps`

- **attachments**
  - `id`, `submission_id`, `user_id`, `original_name`, `mime`, `size`, `storage_key`, `status`, `rejection_reason`, `timestamps`

### Очереди и джобы

- **ScanAttachmentJob**
  - запускается при загрузке файла
  - проверяет:
    - расширение (pdf, zip, png, jpg, jpeg)
    - размер (≤ 10MB)
    - длину имени файла
  - при нарушении правил: `status = rejected` + `rejection_reason`
  - иначе: `status = scanned`

- **NotifyStatusChangedJob**
  - запускается при смене статуса `submission`
  - пишет запись в лог через `Log::info` с данными submission и старым/новым статусом

### API-эндпоинты (основные)

Все маршруты находятся в `routes/api.php` и защищены HTTP Basic Auth (`auth.basic`) + middleware `role`.  
В качестве логина используется email пользователя, пароль — поле `password` (см. тестовые пользователи выше).

- **Participant (`role:participant`)**
  - `GET /api/participant/submissions` — список своих submissions
  - `GET /api/participant/submissions/{submission}`
  - `POST /api/participant/submissions` — создать
  - `PUT /api/participant/submissions/{submission}` — редактировать (только `draft`/`needs_fix`)
  - `POST /api/participant/submissions/{submission}/submit` — отправить на рассмотрение
  - `POST /api/participant/submissions/{submission}/comments` — добавить комментарий
  - `POST /api/participant/submissions/{submission}/attachments` — загрузить файл
  - `GET /api/participant/attachments/{attachment}/download` — получить signed URL для скачивания (5 минут), с проверкой прав

- **Jury (`role:jury`)**
  - `GET /api/jury/submissions` — все submissions
  - `GET /api/jury/submissions/{submission}`
  - `POST /api/jury/submissions/{submission}/status` — смена статуса
  - `POST /api/jury/submissions/{submission}/comments` — комментарий

- **Admin (`role:admin`)**
  - `GET /api/admin/contests`
  - `POST /api/admin/contests`
  - `GET /api/admin/contests/{contest}`
  - `PUT /api/admin/contests/{contest}`
  - `DELETE /api/admin/contests/{contest}`
  - `GET /api/admin/users`
  - `PATCH /api/admin/users/{user}/role` — смена роли

### Архитектура

- **Контроллеры тонкие** — только вызывают сервисы и возвращают JSON
- **Валидация** — через `FormRequest` (`app/Http/Requests`)
- **Сервисный слой**:
  - `SubmissionService` — `create`, `update`, `submit`, `changeStatus`, `addComment`
  - `AttachmentService` — `upload`, `markScanned`, `reject`

