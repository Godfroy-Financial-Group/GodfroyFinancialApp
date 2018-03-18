CREATE SCHEMA IF NOT EXISTS GodfroyFinancialGroup;

DROP TABLE IF EXISTS newsletter_subscriptions;
CREATE TABLE IF NOT EXISTS newsletter_subscriptions (
	ID							INT 			PRIMARY	KEY AUTO_INCREMENT,
	Name						VARCHAR(255)	NOT NULL,
	EmailAddress 				VARCHAR(255) 	NOT NULL,
	DateSubscriptionStarted		DATE			NOT NULL
);