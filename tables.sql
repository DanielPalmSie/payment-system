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
                         currency VARCHAR(3) NOT NULL,
                         balance INT NOT NULL DEFAULT 0,
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE transactions (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              payment_token VARCHAR(255) NOT NULL,
                              wallet_id INT NOT NULL,
                              client_order_id VARCHAR(255) NOT NULL,
                              comment TEXT,
                              expire INT NOT NULL,
                              user_ip VARCHAR(45) NOT NULL,
                              type ENUM('deposit', 'withdraw', 'transfer') NOT NULL,
                              amount INT NOT NULL,
                              balance_before INT NOT NULL,
                              balance_after INT NOT NULL,
                              status ENUM('pending', 'confirmed', 'canceled') NOT NULL DEFAULT 'pending',
                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
