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
    date_created DATETIME NOT NULL,
    code_token VARCHAR(9),
    password_count TINYINT DEFAULT 0,
    token_count TINYINT DEFAULT 0,
    send_email_notification boolean,
    send_sms_notification boolean,
    settings VARCHAR(3000),
    PRIMARY KEY(id)
);

"SELECT (SELECT COUNT(*) FROM comments WHERE predictions.id=comments.id) AS total_comments,
                    predictions.prediction, predictions.id, predictions.created_at, predictions.start_date, 
                    predictions.end_date, predictions.won, predictions.type, users.id AS user_id, users.name, users.sex, users.image_path
                    FROM predictions 
                    INNER JOIN users ON predictions.user_id = users.id 
                    WHERE users.id=" . $userId . ($approved === null ? '' : " AND predictions.approved = " . $approved) . 
                    " ORDER BY predictions.start_date desc";

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
    updated_by int,
    date_updated DATETIME,
    booking_code VARCHAR(50),
    
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
    user_id int NOT NULL references users(`id`),
    ref_id  int NOT NULL,
    link VARCHAR(100),
    created_at DATETIME NOT NULL,
    is_read boolean DEFAULT 0,
    is_seen boolean DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

CREATE TABLE bugs(
    problem VARCHAR(100) NOT NULL,
    note VARCHAR (400),
    user_id int,
    prediction_id int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



ALTER TABLE `predictions`  
    ADD `get_each_game_update` BOOLEAN NOT NULL DEFAULT FALSE
    AFTER `type`,  
    ADD `get_all_game_update` BOOLEAN NOT NULL DEFAULT FALSE  
    AFTER `get_each_game_update`;
