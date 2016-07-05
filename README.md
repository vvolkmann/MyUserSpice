# MyUserSpice
My modifications to the UserSpice System.

Check the link bellow for more information about UserSpice.<br />
http://www.userspice.com/


## My Changes:

- Page for user creation by Admin
- Updated FontAwesome
- LDAP Settings (SQL Bellow)
- TODO UserGroups (SQL Bellow)

### SQLs For LDAP
    ALTER TABLE `users` ADD `ldap` INT(1) NOT NULL DEFAULT '0' AFTER `custom5`;
<br />
    ALTER TABLE `settings` ADD `ldap_address` VARCHAR(50) NOT NULL AFTER `custom3`, ADD `ldap_prefix` VARCHAR(20) NOT NULL AFTER `ldap_address`;

### SQLs For User Groups
    /* Not sure why these ids were with lenght 15, adjusted to 11 (FK Table is 11) */<br />
    ALTER TABLE `permission_page_matches` CHANGE `permission_id` `permission_id` INT(11) NOT NULL, CHANGE `page_id` `page_id` INT(11) NOT NULL;
<br />
    /* Tables related with the new logic of user groups */<br />
    CREATE TABLE `user_groups` (`id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(150) NOT NULL, PRIMARY KEY (`id`));
    <br />
    CREATE TABLE `user_groups_user_matches` (`id` INT(11) NOT NULL AUTO_INCREMENT , `group_id` INT(11) NOT NULL , `user_id` INT(11) NOT NULL , PRIMARY KEY (`id`));
    <br />
    CREATE TABLE `user_groups_permissions_matches` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `group_id` INT(11) NOT NULL , `permission_id` INT(11) NOT NULL , PRIMARY KEY (`id`));
