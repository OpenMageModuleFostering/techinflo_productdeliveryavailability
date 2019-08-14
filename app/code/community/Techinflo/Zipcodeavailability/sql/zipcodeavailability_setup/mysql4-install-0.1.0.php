<?php

$installer = $this;

$installer->startSetup();

$installer->run("

	-- DROP TABLE IF EXISTS {$this->getTable('zipcodeavailability')};
	CREATE TABLE {$this->getTable('zipcodeavailability')} (
		`zipcodeavailability_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`title` varchar(255) NOT NULL DEFAULT '',
		`country` varchar(255) NOT NULL DEFAULT '',
		`state` varchar(120) NOT NULL DEFAULT '0',
		`city` varchar(60) NOT NULL DEFAULT '0',
		`zipcode_range` varchar(255) NOT NULL DEFAULT '0',
		`zipcode_excerpt` varchar(220) NOT NULL,
		`status` int(6) NOT NULL,
		`created_time` datetime DEFAULT NULL,
		`update_time` datetime DEFAULT NULL,
		PRIMARY KEY (`zipcodeavailability_id`)
	
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	
	DROP TABLE IF EXISTS {$this->getTable('zipcodeavailability_productzipcode')};
	CREATE TABLE {$this->getTable('zipcodeavailability_productzipcode')} (
		  `productzipcode_id` int(11) NOT NULL AUTO_INCREMENT,
		  `product` int(11) NOT NULL,
		  `zipcode_template` text NOT NULL,
		  `product_state` varchar(220) NOT NULL,
		  `product_city` varchar(220) NOT NULL,
		  `product_zipcode` varchar(220) NOT NULL,
		  `product_zipcode_exp` varchar(220) NOT NULL,
		  `created_time` timestamp NOT NULL,
		  `update_time` timestamp NOT NULL,
	PRIMARY KEY (`productzipcode_id`)
	
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 