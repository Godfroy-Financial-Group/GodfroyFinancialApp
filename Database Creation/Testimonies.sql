CREATE SCHEMA IF NOT EXISTS GodfroyFinancialGroup;

#DROP TABLE IF EXISTS testimonies;
CREATE TABLE IF NOT EXISTS testimonies (
	ID				INT 			PRIMARY	KEY AUTO_INCREMENT,
	Name			VARCHAR(255)	NOT NULL,
	Review			TEXT			NOT NULL,
    Timestamp		DATE			NOT NULL,
    Active			BOOLEAN			NOT NULL,
	Approved		BOOLEAN			NOT NULL
);

SELECT * FROM testimonies;