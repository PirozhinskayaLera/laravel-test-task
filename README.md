**Для запуска проекта**

```cp .env.example .env``` - в папке src генерируем файл .env

```docker-compose up -d``` - запуск контейнеров

```docker-compose run composer install``` - устанавка зависимостей

```docker-compose run artisan migrate --seed``` - устанавка миграций и запуск сидеров

---
**Пользователь -**
```логин user, пароль password ```

---
**API**

Для запросов используется Bearer Token

**Авторизация**

```
POST /api/login 
Request:
{
    "login": "user",
    "password": "password"
}
```

**Выход из системы**

```
POST /api/logout 
```

**Добавление курьеров**

```
POST /api/couriers
Request:
{
  "data": [
    {
      "courier_type": "foot",
      "regions": [
        1,
        5
      ],
      "working_hours": [
        "11.00",
        "12:00"
      ]
    },
    {
      "courier_type": "car",
      "regions": [
        3,
        10
      ],
      "working_hours": [
        "14:00",
        "15:00"
      ]
    }
  ]
}
```

**Получение информации о курьере**

```
GET /api/couriers/2
```

**Изменение информации о курьере**

```
PATCH /api/couriers/2
Request:
{
  "courier_type": "car",
  "regions": [
        1,
        5
    ],
  "working_hours": [
       "11:00",
       "12:00"
  ]
}
```

**Импорт заказов**

```
PATCH /api/orders
Request:
{
  "data": [
    {
      "weight": 14,
      "region": 1,
      "delivery_hours": [
        "12:00",
        "13:00"
      ]
    },
    {
      "weight": 10,
      "region": 1,
      "delivery_hours": [
        "10:00",
        "11:00"
      ]
    }
  ]
}
```

**Назначение заказа курьеру**

```
POST /api/orders/assign
Request:
{
  "courier_id": 1
}
```

**Отметка о выполнении заказа**

```
POST /api/orders/complete
Request:
{
  "courier_id": 1,
  "order_id": 2,
  "complete_time": "2023-01-14T13:05:14.424Z"
}
```
