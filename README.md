# MyUserSpice
My modifications to the UserSpice System.

Check the link bellow for more information about UserSpice.<br />
http://www.userspice.com/


## My Changes:

- Page for user creation by Admin
- Updated FontAwesome
- LDAP Settings (SQL Bellow)
- UserGroups (SQL Bellow)

### SQLs For LDAP
    ALTER TABLE `users` ADD `ldap` INT(1) NOT NULL DEFAULT '0' AFTER `custom5`;
    ALTER TABLE `settings` ADD `ldap_address` VARCHAR(50) NOT NULL AFTER `custom3`, ADD `ldap_prefix` VARCHAR(20) NOT NULL AFTER `ldap_address`;

### SQLs For User Groups
    /* Tables related with the new logic of user groups */
    CREATE TABLE `user_groups` (`id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(150) NOT NULL, PRIMARY KEY (`id`));
    CREATE TABLE `user_groups_user_matches` (`id` INT(11) NOT NULL AUTO_INCREMENT , `group_id` INT(11) NOT NULL , `user_id` INT(11) NOT NULL , PRIMARY KEY (`id`));
    CREATE TABLE `user_groups_permissions_matches` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `group_id` INT(11) NOT NULL , `permission_id` INT(11) NOT NULL , PRIMARY KEY (`id`));

### Other SQLs
    /* Not sure why these ids were with lenght 15, adjusted to 11 (FK Table is 11) */
    ALTER TABLE `permission_page_matches` CHANGE `permission_id` `permission_id` INT(11) NOT NULL, CHANGE `page_id` `page_id` INT(11) NOT NULL;


#### Explanation for User Groups
User Groups is an easier way to maintenance the permissions for a bigger group of users. <br />
Example: Changing permissions for 50 users (same permissions for them).

##### Adding an User to the Group
When you adding an user to the usergroup, the permissions defined to this group will be given to the users that were added.

##### Changing User Group Permissions
When you change add/remove any permissions to the group, the same will happen with the users that are included in this group.

##### Deleting a User Group
When you delete the user group, the system will first, <b>remove all usergroup permissions from all usergroup members</b>. After that, it will delete all informations about the group. (The user will stay in the system, it doesn't delete any user!)