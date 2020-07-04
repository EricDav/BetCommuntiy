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
    image_path VARCHAR(125),
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
    scores_finished boolean,
    approved_by int,
    type VARCHAR(30),
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

CREATE TABLE featured_users(
    user_id int references users(id),
    featured_date DATETIME NOT NULL
);

CREATE TABLE comments(
    id int NOT NULL AUTO_INCREMENT,
    prediction_id int references predictions(id),
    user_id int references users(id),
    comment VARCHAR(301),
    PRIMARY KEY(id)
);

CREATE TABLE likes(
    user_id int references users(id),
    prediction_id int references predictions(id),
);


CREATE TABLE `temp_request` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `special_id` varchar(255) NOT NULL,
  `request` varchar(200) NOT NULL,
  `request_time` datetime NOT NULL,
  `expires` datetime NOT NULL,
  `identity` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
