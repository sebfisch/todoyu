RENAME TABLE `invoice_approval`  TO `ext_projectbilling_invoiceapproval`

;

ALTER TABLE	`ext_projectbilling_invoiceapproval`
CHANGE `uid` `id` INT NOT NULL AUTO_INCREMENT ,
CHANGE `last_modified` `date_update` INT UNSIGNED NOT NULL ,
CHANGE `cruser` `id_user_create` INT UNSIGNED NOT NULL ,
CHANGE `crdate` `date_create` INT UNSIGNED NOT NULL DEFAULT '0' ,
CHANGE `user` `id_user` INT UNSIGNED NOT NULL ,
CHANGE `reminder` `id_invoicereminder` INT UNSIGNED NOT NULL ,
CHANGE `role` `id_projectrole` INT UNSIGNED NOT NULL ,
CHANGE `confirmed` `is_confirmed` BOOL NOT NULL DEFAULT '0'
