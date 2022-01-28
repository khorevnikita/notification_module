# Пакет для быстрого создания чата

## Установка

### 1. Ставим пакет
composer require khonik/notifications

### 2. Добавляем в config/app.php в массив providers
Khonik\Notifications\Providers\ChatsServiceProvider::class

### 3. Публикуем миграции
php artisan vendor:publish --provider="Khonik\Notifications\NotificationsServiceProvider"

### 4. Выполняем миграции
php artisan migrate

### 5. Добавляем trait в модель User
use Notifiable

## Использование

### Получить список уведомления

#### METHOD: GET

#### URL: /api/notifications

#### RESPONSE: 
```
{
    "status": "success",
    "notifications": [
        {
            "id": 2,
            "type": "email",
            "title": "первое уведомление",
            "text": "Привет всем!",
            "sent_at": null,
            "created_at": "2022-01-28T10:47:45.000000Z",
            "updated_at": "2022-01-28T10:47:45.000000Z",
            "users_count": 0
        },
    ],
    "total": 1,
    "pages": 1
}
```


### Создать уведомление

#### METHOD: POST

#### URL: /api/notifications

#### BODY

```
{
    "type": "email",
    "title": "первое уведомление",
    "text": "Привет всем!"
}
```

#### RESPONSE: 
```
{
    "status": "success",
    "notification": {
        "type": "email",
        "title": "первое уведомление",
        "text": "Привет всем!",
        "updated_at": "2022-01-28T10:47:10.000000Z",
        "created_at": "2022-01-28T10:47:10.000000Z",
        "id": 1
    }
}
```

### Изменить уведомление

#### METHOD: PUT

#### URL: /api/notifications/{NOTIFICATION_ID}

#### BODY

```
{
    "type": "email",
    "title": "первое уведомление!",
    "text": "Привет всем!"
}
```

#### RESPONSE: 
```
{
    "status": "success",
    "notification": {
        "id": 3,
        "type": "email",
        "title": "первое уведомление!",
        "text": "Привет всем!",
        "sent_at": null,
        "created_at": "2022-01-28T10:47:49.000000Z",
        "updated_at": "2022-01-28T10:47:49.000000Z"
    }
}
```


### Удалить сообщение

#### METHOD: DELETE

#### URL: /api/notifications/{NOTIFICATION_ID}

#### RESPONSE: 
```
{
    "status": "success",
}
```

### Получить список прикреплённых пользователей

#### METHOD: GET

#### URL: /api/notifications/{NOTIFICATION_ID}/get-users

#### RESPONSE:
```
{
    "status": "success",
    "users": [
        {
            "id": 1,
            "name": "Nikita",
        }
    ]
}
```

### Синхронизировать список пользователей

#### METHOD: POST

#### URL: /api/notifications/{NOTIFICATION_ID}/set-users

#### BODY

```
{
    "users": [
        1,
        2,
        3
    ]
}
```

#### RESPONSE:
```
{
    "status": "success"
}
```


### Поставить на отправку

#### METHOD: POST

#### URL: /api/notifications/{NOTIFICATION_ID}/send

#### RESPONSE:
```
{
    "status": "success"
}
```


