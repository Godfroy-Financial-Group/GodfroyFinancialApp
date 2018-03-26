CREATE SCHEMA IF NOT EXISTS GodfroyFinancialGroup;

#DROP TABLE IF EXISTS application_settings;
CREATE TABLE IF NOT EXISTS application_settings (
	ID							INT 			PRIMARY	KEY AUTO_INCREMENT,
	Name						VARCHAR(255)	NOT NULL,
	Group						VARCHAR(255)	NOT NULL,
	Value		 				TEXT 			NOT NULL
);

#INSERT INTO application_settings (Name, Value) ("publicUserCreationEnabled", "UserCreation", "false");

SELECT * FROM application_settings;