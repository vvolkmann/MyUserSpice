# MyUserSpice
My edits to the UserSpice System.

Check the link bellow for more information about UserSpice.
- http://www.userspice.com/


## My Changes:

- Page for user creation by Admin
- Updated FontAwesome
- LDAP Settings (See SQLs to update the DB to make it works)

    ALTER TABLE `users` ADD `ldap` INT(1) NOT NULL DEFAULT '0' AFTER `custom5`;
    ALTER TABLE `settings` ADD `ldap_address` VARCHAR(50) NOT NULL AFTER `custom3`, ADD `ldap_prefix` VARCHAR(20) NOT NULL AFTER `ldap_address`;
