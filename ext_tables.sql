
#
# Table structure for table 'tx_fnnpowermailaltcha_domain_model_challenge'
#
CREATE TABLE tx_fnnpowermailaltcha_domain_model_challenge (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	challenge VARCHAR(1024) DEFAULT '' NOT NULL,
	expires int(11) unsigned DEFAULT '0' NOT NULL,
	solved tinyint(4) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY challenge (challenge)
);
