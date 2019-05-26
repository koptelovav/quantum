## QuantumSoft Test

#### Используемые технологии

PHP7, MySQL, Redis, Vue.js, Bootstrap 4, SCSS, Laravel, Docker

#### Описание классов

`App\Services\TreeCacheService` - Сохраняет и извлекает дерево элементов из кэше. Подключается через DI для гибкости.

`App\Services\NodeService` - Реализует бизнес логику сохранение кэша в базу данных и получение элементов по ID.

`App\Components\TreeBuilder\TreeBuilder` - Класс для работы с деревом элементов

`App\Models\Node` - Класс для работы с базой данных (Laravel Eloquent)

`Kalnoy\Nestedset\NodeTrait` - Трейт реализующий методы для работы c Nested Set.

`App\Components\Storages\RedisCacheStorage` - Класс реализующий хранение данных в Redis. Подключается через DI для гибкости.

`App\Providers\AppServiceProvider` - Описание DI (Класс Laravel)

#### Описание интерфейсов

`App\Components\TreeBuilder\TreeBuilderInterface` - Описывает методы для сохранения и извлечения дерева из кэша 

`App\Components\Storages\StorageInterface` - Описывает методы для сохранения и извлечения данных из кэша (в данной реализации Redis)

#### REST API

`GET /cache` Получение дерева элементов из кэша

`PUT /cache` Сохранение данных из кэша в БД

`GET /cache/{id}` Загрузка элемента по ID из кэша в БД

`POST /cache/{id}` Редактирование элемента в кэше

`PUT /cache/{id}` Добавление нового элемента в кэш

`DELETE /cache/{id}` Удаление элемента в кэше

`GET /db` Получение дерева элементов из БД (эмуляция для UI)

`POST /reset` Сброс приложения к первоначальному состоянию

#### Развертывание в Docker

`make start` - Запуск контейнеров

`make stop` - Остановка контейнеров

`make composer_up` - установка зависимостей Сomposer

`make migrate` - Накатывание миграций

`make all` - Все вышеперечисленные действия

#### Описание компонентов Vue.js

`Alert` - Отображение информации пользовательских действий

`NodeTree` - Компонент отображающий элемент дерева

`Panel` - Панель инструментов для работы с кэшем 

`TestComponent` - Главный компонент реализующий UI

`Tree` - Компонент для построения дерева
