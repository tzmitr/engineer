SET NAMES 'utf8';
USE test;
CREATE TABLE test.users (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_type varchar(255) DEFAULT NULL,
  picture blob DEFAULT NULL,
  first_name varchar(255) DEFAULT NULL,
  last_name varchar(255) DEFAULT NULL,
  email varchar(50) DEFAULT NULL,
  username varchar(255) DEFAULT NULL,
  password varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  gender char(1) DEFAULT NULL,
  birhday date DEFAULT NULL,
  hobby1 tinyint(4) DEFAULT NULL,
  hobby2 tinyint(4) DEFAULT NULL,
  hobby3 tinyint(4) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB,
CHARACTER SET utf8,
COLLATE utf8_general_ci;