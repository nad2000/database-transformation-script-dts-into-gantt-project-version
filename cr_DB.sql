USE accounts;
delimiter ;

DROP TABLE IF EXISTS account CASCADE;
CREATE TABLE account (
    id INT NOT NULL PRIMARY KEY,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ssl_enabled BOOLEAN,
    updated_at TIMESTAMP,
    name VARCHAR(255),
    plan VARCHAR(255),
    account_holder_id INT,
    created_on DATE);


DROP TABLE IF EXISTS default_attachment_categorie CASCADE;
CREATE TABLE default_attachment_categorie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_id INT NOT NULL,
    category VARCHAR(255),
    UNIQUE (account_id, category) );

DROP TABLE IF EXISTS default_post_categorie CASCADE;
CREATE TABLE default_post_categorie (
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
    zip VARCHAR(255) NULL,
    name_1 VARCHAR(255) NULL);
    
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
    name_1 VARCHAR(255) NULL,
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
    name_1 VARCHAR(255) NULL,
    type VARCHAR(255) NULL );

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
    extended_body  VARCHAR(255) NULL);


todo_list>
<completed_count type="integer">0</completed_count>
<description>stage 3 action item list description</description>
<id type="integer">17201671</id>
<milestone_id type="integer">26365028</milestone_id>
<name>Stage 3 action items</name>
<position type="integer">1</position>
<project_id type="integer">8464822</project_id>
<tracked type="boolean">false</tracked>
<uncompleted_count type="integer">2</uncompleted_count>
<name>Stage 3 action items</name>
          


<projects type="array">
    <project>


    <todo_lists type="array">
        <todo_list>
          <completed_count type="integer">0</completed_count>
          <description>stage 3 action item list description</description>
          <id type="integer">17201671</id>
          <milestone_id type="integer">26365028</milestone_id>
          <name>Stage 3 action items</name>
          <position type="integer">1</position>
          <project_id type="integer">8464822</project_id>
          <tracked type="boolean">false</tracked>
          <uncompleted_count type="integer">2</uncompleted_count>
          <name>Stage 3 action items</name>
          <todo_items type="array">
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Create prototype 1</content>
              <created_at type="datetime">2011_11_16T15:24:08Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2011_11_17T06:00:00Z</due_at>
              <id type="integer">114637894</id>
              <position type="integer">1</position>
              <responsible_party_id type="integer">8470255</responsible_party_id>
              <responsible_party_type>Person</responsible_party_type>
              <todo_list_id type="integer">17201671</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:24:08Z</updated_at>
              <comments type="array"/>
              <responsible_party_name>Jane Doe</responsible_party_name>
              <created_on type="datetime" deprecated="true">2011_11_16T15:24:08Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Create prototype 2</content>
              <created_at type="datetime">2011_11_16T15:24:55Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2012_01_02T06:00:00Z</due_at>
              <id type="integer">114638086</id>
              <position type="integer">2</position>
              <responsible_party_id type="integer">8470255</responsible_party_id>
              <responsible_party_type>Person</responsible_party_type>
              <todo_list_id type="integer">17201671</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:24:55Z</updated_at>
              <comments type="array"/>
              <responsible_party_name>Jane Doe</responsible_party_name>
              <created_on type="datetime" deprecated="true">2011_11_16T15:24:55Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
          </todo_items>
          <completed type="boolean">false</completed>
          <complete deprecated="true">false</complete>
        </todo_list>
        <todo_list>
          <completed_count type="integer">0</completed_count>
          <description></description>
          <id type="integer">17201625</id>
          <milestone_id type="integer">26364997</milestone_id>
          <name>Stage 2 Action Items</name>
          <position type="integer">2</position>
          <project_id type="integer">8464822</project_id>
          <tracked type="boolean">false</tracked>
          <uncompleted_count type="integer">2</uncompleted_count>
          <name>Stage 2 Action Items</name>
          <todo_items type="array">
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Geometric Envelope</content>
              <created_at type="datetime">2011_11_16T15:22:18Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2011_12_09T06:00:00Z</due_at>
              <id type="integer">114637538</id>
              <position type="integer">1</position>
              <responsible_party_id type="integer">8470206</responsible_party_id>
              <responsible_party_type>Person</responsible_party_type>
              <todo_list_id type="integer">17201625</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:22:18Z</updated_at>
              <comments type="array"/>
              <responsible_party_name>John Adams</responsible_party_name>
              <created_on type="datetime" deprecated="true">2011_11_16T15:22:18Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Determine test plan</content>
              <created_at type="datetime">2011_11_16T15:22:41Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2011_12_07T06:00:00Z</due_at>
              <id type="integer">114637605</id>
              <position type="integer">2</position>
              <responsible_party_id type="integer">8470206</responsible_party_id>
              <responsible_party_type>Person</responsible_party_type>
              <todo_list_id type="integer">17201625</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:22:41Z</updated_at>
              <comments type="array"/>
              <responsible_party_name>John Adams</responsible_party_name>
              <created_on type="datetime" deprecated="true">2011_11_16T15:22:41Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
          </todo_items>
          <completed type="boolean">false</completed>
          <complete deprecated="true">false</complete>
        </todo_list>
        <todo_list>
          <completed_count type="integer">0</completed_count>
          <description></description>
          <id type="integer">17201581</id>
          <milestone_id type="integer">26364977</milestone_id>
          <name>Milestone 1 Action Items</name>
          <position type="integer">3</position>
          <project_id type="integer">8464822</project_id>
          <tracked type="boolean">false</tracked>
          <uncompleted_count type="integer">2</uncompleted_count>
          <name>Milestone 1 Action Items</name>
          <todo_items type="array">
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Internal Project proposal</content>
              <created_at type="datetime">2011_11_16T15:20:48Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2011_11_17T06:00:00Z</due_at>
              <id type="integer">114637287</id>
              <position type="integer">1</position>
              <todo_list_id type="integer">17201581</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:20:48Z</updated_at>
              <comments type="array"/>
              <created_on type="datetime" deprecated="true">2011_11_16T15:20:48Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
            <todo_item>
              <commented_at type="datetime" nil="true"></commented_at>
              <comments_count type="integer">0</comments_count>
              <completed type="boolean">false</completed>
              <content>Marketing Specification</content>
              <created_at type="datetime">2011_11_16T15:21:13Z</created_at>
              <creator_id type="integer">8470206</creator_id>
              <due_at type="datetime">2011_11_17T06:00:00Z</due_at>
              <id type="integer">114637346</id>
              <position type="integer">2</position>
              <responsible_party_id type="integer">8470255</responsible_party_id>
              <responsible_party_type>Person</responsible_party_type>
              <todo_list_id type="integer">17201581</todo_list_id>
              <updated_at type="datetime">2011_11_16T15:21:13Z</updated_at>
              <comments type="array"/>
              <responsible_party_name>Jane Doe</responsible_party_name>
              <created_on type="datetime" deprecated="true">2011_11_16T15:21:13Z</created_on>
              <creator_name>John Adams</creator_name>
            </todo_item>
          </todo_items>
          <completed type="boolean">false</completed>
          <complete deprecated="true">false</complete>
        </todo_list>
      </todo_lists>
      <attachment_categories type="array">
        <attachment_category>
          <elements_count type="integer">1</elements_count>
          <id type="integer">85850064</id>
          <name>Documents</name>
          <project_id type="integer">8464822</project_id>
          <name>Documents</name>
          <type>AttachmentCategory</type>
        </attachment_category>
        <attachment_category>
          <elements_count type="integer">0</elements_count>
          <id type="integer">85850065</id>
          <name>Pictures</name>
          <project_id type="integer">8464822</project_id>
          <name>Pictures</name>
          <type>AttachmentCategory</type>
        </attachment_category>
        <attachment_category>
          <elements_count type="integer">0</elements_count>
          <id type="integer">85850066</id>
          <name>Sounds</name>
          <project_id type="integer">8464822</project_id>
          <name>Sounds</name>
          <type>AttachmentCategory</type>
        </attachment_category>
      </attachment_categories>
      <participants>
        <person type="integer">8470206</person>
        <person type="integer">8470255</person>
      </participants>
    </project>
  </projects>
</account>
