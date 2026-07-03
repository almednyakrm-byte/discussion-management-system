CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE discussions (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE topics (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE open_topics (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_discussions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  discussion_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (discussion_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (discussion_id) REFERENCES discussions(id)
);

CREATE TABLE user_topics (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  topic_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (topic_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (topic_id) REFERENCES topics(id)
);

CREATE TABLE user_open_topics (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  open_topic_id INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY (user_id),
  KEY (open_topic_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (open_topic_id) REFERENCES open_topics(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO discussions (title, content)
VALUES ('Discussion 1', 'This is the content of discussion 1'),
       ('Discussion 2', 'This is the content of discussion 2');

INSERT INTO topics (title, description)
VALUES ('Topic 1', 'This is the description of topic 1'),
       ('Topic 2', 'This is the description of topic 2');

INSERT INTO open_topics (title, description)
VALUES ('Open Topic 1', 'This is the description of open topic 1'),
       ('Open Topic 2', 'This is the description of open topic 2');