CREATE TABLE tx_in2glossar_domain_model_definition (
	word varchar(255) DEFAULT '' NOT NULL,
	synonyms tinytext DEFAULT '' NOT NULL,
	short_description tinytext DEFAULT '' NOT NULL,
	description text DEFAULT '' NOT NULL,
	tooltip tinyint(4) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tt_content (
	tx_in2glossar_exclude tinyint(4) unsigned DEFAULT '0' NOT NULL
);
