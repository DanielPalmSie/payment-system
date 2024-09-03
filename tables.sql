CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       email VARCHAR(255) UNIQUE NOT NULL,
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE wallets (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         user_id INT NOT NULL,
                         currency VARCHAR(3) NOT NULL, -- Код валюты, например, 'USD', 'EUR'
                         balance INT NOT NULL DEFAULT 0, -- Баланс хранится в копейках (или центах)
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE transactions (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              payment_token VARCHAR(255) NOT NULL, -- Токен платежа, идентификатор транзакции
                              wallet_id INT NOT NULL, -- Ссылка на кошелек, с которым связана транзакция
                              client_order_id VARCHAR(255) NOT NULL, -- Идентификатор заказа клиента
                              comment TEXT, -- Комментарий к транзакции
                              expire INT NOT NULL, -- Время жизни транзакции в секундах
                              user_ip VARCHAR(45) NOT NULL, -- IP-адрес пользователя
                              type ENUM('deposit', 'withdraw', 'transfer') NOT NULL,
                              amount INT NOT NULL, -- Сумма операции в копейках (или центах)
                              balance_before INT NOT NULL, -- Баланс до операции
                              balance_after INT NOT NULL, -- Баланс после операции
                              status ENUM('pending', 'confirmed', 'canceled') NOT NULL DEFAULT 'pending', -- Статус транзакции
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
