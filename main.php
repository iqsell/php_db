<?php
require 'vendor/autoload.php';

use App\Database;
use App\User;

// Путь к бд
$dbPath = __DIR__ . '/database.sqlite';

// Создание  бд если нет
if (!file_exists($dbPath)) {
    touch($dbPath);
    echo "Файл базы данных создан.\n";
}

// Подключение к бд
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Создание таблицы
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE
    );
");
echo "Таблица users проверена/создана.\n";

// Использование классов
$db = new Database($dbPath);
$user = new User($db);

// Добавление пользователя
try {
    $user->addUser('John Doe', 'john@example.com');
    echo "Пользователь добавлен.\n";
} catch (Exception $e) {
    echo "Ошибка добавления пользователя: " . $e->getMessage() . "\n";
}

// Обновление пользователя
try {
    $user->updateUser(1, 'Jane Doe', 'jane@example.com');
    echo "Пользователь обновлён.\n";
} catch (Exception $e) {
    echo "Ошибка обновления пользователя: " . $e->getMessage() . "\n";
}

// Поиск пользователя
try {
    $results = $user->searchUsers('Jane');
    echo "Результаты поиска:\n";
    print_r($results);
} catch (Exception $e) {
    echo "Ошибка поиска пользователей: " . $e->getMessage() . "\n";
}

// Удаление пользователя
try {
    $user->deleteUser(1);
    echo "Пользователь удалён.\n";
} catch (Exception $e) {
    echo "Ошибка удаления пользователя: " . $e->getMessage() . "\n";
}
