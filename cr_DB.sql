USE accounts;
delimiter ;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP TABLE IF EXISTS account CASCADE;
CREATE TABLE account (
    id INT NOT NULL PRIMARY KEY,
    created_at DATETIME,
    ssl_enabled BOOLEAN,
    updated_at TIMESTAMP,
    name VARCHAR(255),
    plan VARCHAR(255),
    account_holder_id INT,
    created_on DATE);

DROP TABLE IF EXISTS default_attachment_category CASCADE;
CREATE TABLE default_attachment_category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    category VARCHAR(255),
    UNIQUE (account_id, category) );

DROP TABLE IF EXISTS default_post_category CASCADE;
CREATE TABLE default_post_category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    category VARCHAR(255),
    UNIQUE (account_id, category));

DROP TABLE IF EXISTS subscription CASCADE;
CREATE TABLE subscription (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    `ssl` BOOLEAN,
    projects INTEGER,
    writeboards INTEGER,
    name VARCHAR(255),
    storage INTEGER,
    time_tracking BOOLEAN );

DROP TABLE IF EXISTS firm CASCADE;
CREATE TABLE firm (
    id INT PRIMARY KEY,
    account_id INT NOT NULL,
    address_one VARCHAR(255) NULL,
    address_two VARCHAR(255) NULL,
    city VARCHAR(255) NULL,
    country VARCHAR(255) NULL,
    locale VARCHAR(255),
    name VARCHAR(255),
    phone_number_fax VARCHAR(255) NULL,
    phone_number_office VARCHAR(255) NULL,
    state VARCHAR(255) NULL,
    time_zone_id VARCHAR(255) NULL,
    uuid VARCHAR(255) NULL,
    web_address VARCHAR(255) NULL,
    zip VARCHAR(255) NULL);
    
DROP TABLE IF EXISTS person CASCADE;
CREATE TABLE person (
    id INT PRIMARY KEY,
    firm_id INT NOT NULL, -- person/company-id 
    client_id INTEGER,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    im_handle VARCHAR(255) NULL,
    im_service VARCHAR(255) NULL,
    phone_number_fax VARCHAR(255) NULL,
    phone_number_home VARCHAR(255) NULL,
    phone_number_mobile VARCHAR(255) NULL,
    phone_number_office VARCHAR(255) NULL,
    phone_number_office_ext VARCHAR(255) NULL,
    title VARCHAR(255) NULL,
    updated_at TIMESTAMP NULL,
    uuid VARCHAR(255) NULL,
    name VARCHAR(255) NULL,
    first_name VARCHAR(255) NULL,
    last_name VARCHAR(255) NULL,
    company_id INTEGER,
    email_address VARCHAR(255) NULL,
    avatar_url VARCHAR(255) NULL);

DROP TABLE IF EXISTS project CASCADE;
CREATE TABLE project (
    id INT PRIMARY KEY,
    account_id INT NOT NULL,
    created_on DATE,
    last_changed_on TIMESTAMP,
    name VARCHAR(255) NULL,
    status VARCHAR(255) NULL,
    firm_id INTEGER); -- project/company/id
    
DROP TABLE IF EXISTS calendar_event CASCADE;
CREATE TABLE calendar_event (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    all_day BOOLEAN,
    created_on TIMESTAMP,
    creator_id INTEGER,
    title VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    creator_name VARCHAR(255) NULL,
    due_at DATE NULL,
    start_at DATE NULL);
    
DROP TABLE IF EXISTS milestone CASCADE;
CREATE TABLE milestone (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    all_day BOOLEAN,
    commented_at DATETIME,
    comments_count INTEGER,
    completed BOOLEAN,
    created_on DATETIME,
    creator_id INTEGER,
    responsible_party_id INTEGER,
    responsible_party_type VARCHAR(255) NULL,
    title VARCHAR(255) NULL,
    wants_notification BOOLEAN,
    type VARCHAR(255) NULL,
    creator_name VARCHAR(255) NULL,
    deadline DATE,
    start_at DATE,
    responsible_party_name VARCHAR(255) NULL );

DROP TABLE IF EXISTS post_category CASCADE;
CREATE TABLE post_category (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    elements_count INTEGER,
    name VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    UNIQUE(project_id,name));

DROP TABLE IF EXISTS post CASCADE;
CREATE TABLE post (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    attachments_count INTEGER,
    author_id INTEGER,
    body VARCHAR(2000),
    category_id INTEGER,
    commented_at DATETIME,
    comments_count INTEGER,
    milestone_id INTEGER,
    posted_on DATETIME,
    title VARCHAR(255) NULL,
    use_textile BOOLEAN,
    author_name VARCHAR(255) NULL,
    extended_body VARCHAR(255) NULL);

DROP TABLE IF EXISTS todo_list CASCADE;
CREATE TABLE todo_list (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    completed_count INTEGER,
    description VARCHAR(255) NULL,
    milestone_id INTEGER,
    name VARCHAR(255) NULL,
    position INTEGER,
    tracked BOOLEAN,
    uncompleted_count INTEGER,
    completed BOOLEAN);
          
DROP TABLE IF EXISTS todo_item CASCADE;
CREATE TABLE todo_item (
    id INT PRIMARY KEY,
    todo_list_id INT NOT NULL,
    commented_at DATETIME,
    comments_count INTEGER,
    completed BOOLEAN,
    content VARCHAR(255) NULL,
    created_at DATETIME,
    creator_id INTEGER,
    due_at DATETIME,
    position INTEGER,
    responsible_party_id INTEGER,
    responsible_party_type VARCHAR(255) NULL,
    updated_at DATETIME,
    responsible_party_name VARCHAR(255) NULL,
    created_on DATETIME,
    creator_name VARCHAR(255) NULL );

DROP TABLE IF EXISTS attachment_category CASCADE;
CREATE TABLE attachment_category (
    id INT PRIMARY KEY,
    project_id INT NOT NULL,
    elements_count INTEGER,
    name VARCHAR(255) NULL,
    type VARCHAR(255) NULL,
    UNIQUE(project_id,name));
    
DROP TABLE IF EXISTS participant CASCADE;
CREATE TABLE participant (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    person_id INT NOT NULL,
    UNIQUE (project_id,person_id));
    
ALTER TABLE attachment_category 
  ADD CONSTRAINT fk_attachment_category_project
  FOREIGN KEY (project_id )
  REFERENCES project (id );

ALTER TABLE calendar_event 
  ADD CONSTRAINT fk_calendar_event_project
  FOREIGN KEY (project_id )
  REFERENCES project (id );

ALTER TABLE default_attachment_category 
  ADD CONSTRAINT fk_default_attachment_category_account
  FOREIGN KEY (account_id )
  REFERENCES account (id );

ALTER TABLE default_post_category 
  ADD CONSTRAINT fk_default_post_category_account
  FOREIGN KEY (account_id )
  REFERENCES account (id );

ALTER TABLE firm 
  ADD CONSTRAINT fk_firm_account
  FOREIGN KEY (account_id )
  REFERENCES account (id );

ALTER TABLE milestone 
  ADD CONSTRAINT fk_milestone_project
  FOREIGN KEY (project_id )
  REFERENCES project (id );

ALTER TABLE participant 
  ADD CONSTRAINT fk_participant_project
  FOREIGN KEY (project_id )
  REFERENCES project (id ), 
  ADD CONSTRAINT fk_participant_person
  FOREIGN KEY (person_id )
  REFERENCES person (id );

ALTER TABLE person 
  ADD CONSTRAINT fk_person_firm
  FOREIGN KEY (firm_id )
  REFERENCES firm (id );

ALTER TABLE post 
  ADD CONSTRAINT fk_post_project
  FOREIGN KEY (project_id )
  REFERENCES project (id ), 
  ADD CONSTRAINT fk_post_milestone
  FOREIGN KEY (milestone_id )
  REFERENCES milestone (id );

ALTER TABLE post_category 
  ADD CONSTRAINT fk_post_category_project
  FOREIGN KEY (project_id )
  REFERENCES project (id );

ALTER TABLE project 
  ADD CONSTRAINT fk_project_account
  FOREIGN KEY (account_id )
  REFERENCES account (id ), 
  ADD CONSTRAINT fk_project_firm
  FOREIGN KEY (firm_id )
  REFERENCES firm (id );

ALTER TABLE subscription 
  ADD CONSTRAINT fk_subscription_account
  FOREIGN KEY (account_id )
  REFERENCES account (id );

ALTER TABLE todo_item 
  ADD CONSTRAINT fk_todo_item_todo_list
  FOREIGN KEY (todo_list_id )
  REFERENCES todo_list (id );

ALTER TABLE todo_list 
  ADD CONSTRAINT fk_todo_list_project
  FOREIGN KEY (project_id )
  REFERENCES project (id ), 
  ADD CONSTRAINT fk_todo_list_milestone
  FOREIGN KEY (milestone_id )
  REFERENCES milestone (id );

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;