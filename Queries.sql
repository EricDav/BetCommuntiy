/**
    Users table
*/
CREATE TABLE users (
    id int NOT NULL AUTO_INCREAMENT,
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

/**
    Query to create Predictions table
*/

CREATE TABLE predictions (
    id int NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    prediction TEXT,
    won boolean,
    user_id int references users(id),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    PRIMARY KEY(id)
)

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
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE payslip(
    amount VARCHAR(125),
    user_id int references users(id),
    payer_id int references users(id),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
)
