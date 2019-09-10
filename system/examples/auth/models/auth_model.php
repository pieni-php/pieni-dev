<?php
class auth_model extends super_auth_model {
	public function initialize($table, $actor)
	{
		$this->get_dbh()->beginTransaction();
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_unregistered`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_reset_password_succeeded`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_reset_password_attempted`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_change_email_succeeded`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_change_email_attempted`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_register_succeeded`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'_register_attempted`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `auth_'.$actor.'`
		');
		$this->pbe('
			DROP TABLE IF EXISTS `'.$table.'`
		');
		$this->pbe('
			CREATE TABLE `'.$table.'` (
				`'.$table.'_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`'.$table.'_name` varchar(255) NOT NULL
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'` (
				`'.$table.'_id` int(11) NOT NULL PRIMARY KEY,
				`'.$table.'_email` varchar(255) NOT NULL UNIQUE,
				`'.$table.'_password` varchar(255) NOT NULL,
				CONSTRAINT FOREIGN KEY (`'.$table.'_id`) REFERENCES `'.$table.'` (`'.$table.'_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_register_attempted` (
				`auth_'.$actor.'_register_attempted_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`'.$table.'_email` varchar(255) NOT NULL,
				`auth_'.$actor.'_register_attempted_token` varchar(255) NOT NULL,
				`auth_'.$actor.'_register_attempted_microtime` bigint(20) NOT NULL
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_register_succeeded` (
				`auth_'.$actor.'_register_succeeded_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`auth_'.$actor.'_register_attempted_id` int(11) NOT NULL,
				`'.$table.'_id` int(11) NOT NULL,
				`auth_'.$actor.'_register_succeeded_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`auth_'.$actor.'_register_attempted_id`) REFERENCES `auth_'.$actor.'_register_attempted` (`auth_'.$actor.'_register_attempted_id`),
				CONSTRAINT FOREIGN KEY (`'.$table.'_id`) REFERENCES `'.$table.'` (`'.$table.'_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_change_email_attempted` (
				`auth_'.$actor.'_change_email_attempted_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`'.$table.'_id` int(11) NOT NULL,
				`'.$table.'_email` varchar(255) NOT NULL,
				`auth_'.$actor.'_change_email_attempted_token` varchar(255) NOT NULL,
				`auth_'.$actor.'_change_email_attempted_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`'.$table.'_id`) REFERENCES `'.$table.'` (`'.$table.'_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_change_email_succeeded` (
				`auth_'.$actor.'_change_email_succeeded_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`auth_'.$actor.'_change_email_attempted_id` int(11) NOT NULL,
				`auth_'.$actor.'_change_email_succeeded_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`auth_'.$actor.'_change_email_attempted_id`) REFERENCES `auth_'.$actor.'_change_email_attempted` (`auth_'.$actor.'_change_email_attempted_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_reset_password_attempted` (
				`auth_'.$actor.'_reset_password_attempted_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`'.$table.'_id` int(11) NOT NULL,
				`auth_'.$actor.'_reset_password_attempted_token` varchar(255) NOT NULL,
				`auth_'.$actor.'_reset_password_attempted_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`'.$table.'_id`) REFERENCES `'.$table.'` (`'.$table.'_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_reset_password_succeeded` (
				`auth_'.$actor.'_reset_password_succeeded_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`auth_'.$actor.'_reset_password_attempted_id` int(11) NOT NULL,
				`auth_'.$actor.'_reset_password_succeeded_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`auth_'.$actor.'_reset_password_attempted_id`) REFERENCES `auth_'.$actor.'_reset_password_attempted` (`auth_'.$actor.'_reset_password_attempted_id`)
			)
		');
		$this->pbe('
			CREATE TABLE `auth_'.$actor.'_unregistered` (
				`'.$table.'_id` int(11) NOT NULL,
				`auth_'.$actor.'_unregistered_microtime` bigint(20) NOT NULL,
				CONSTRAINT FOREIGN KEY (`'.$table.'_id`) REFERENCES `'.$table.'` (`'.$table.'_id`)
			)
		');
		$this->get_dbh()->commit();
	}
}
