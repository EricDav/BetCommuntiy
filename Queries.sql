/**
    Users table
*/
CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    email VARCHAR(100),
    password VARCHAR(225) NOT NULL,
    role int NOT NULL,
    name VARCHAR(225),
    socials VARCHAR(1000),
    special_id VARCHAR(50),
    country VARCHAR(125),
    city VARCHAR(125),
    phone_number VARCHAR(100),
    sex VARCHAR(2),
    PRIMARY KEY(id)
);

SELECT predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, predictions.end_date, users.id AS user_id, users.name, users.sex FROM predictions INNER JOIN users ON predictions.user_id = users.id

/**
    Query to create Predictions table
*/

CREATE TABLE predictions (
    id int NOT NULL  AUTO_INCREMENT,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    prediction TEXT,
    won boolean,
    user_id int references users(id),
    created_at DATETIME NOT NULL,
    total_odds VARCHAR(100),
    approved boolean NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE followers(
    user_id int references users(id),
    follower_id int  references users(id)
);

CREATE TABLE reviews (
    rate int,
    reviewer_id int references users(id),
    user_id int references users(id),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE notifications (
    notification VARCHAR(500),
    user_id int references users(id),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read boolean
);

CREATE TABLE payslip(
    amount VARCHAR(125),
    user_id int references users(id),
    payer_id int references users(id),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
);

