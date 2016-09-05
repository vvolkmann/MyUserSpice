# MyUserSpice
My modifications to the UserSpice System.

Check the link bellow for more information about UserSpice.<br />
http://www.userspice.com/


## My Changes:

- Page for user creation by Admin (Available now at the main version of userspice, check userspice.com/)
- Updated FontAwesome
- LDAP Settings
- UserGroups

#### Explanation for User Groups
User Groups is an easier way to maintenance the permissions for a bigger group of users. <br />
Example: Changing permissions for 50 users (same permissions for them).

##### Adding an User to the Group
When you adding an user to the usergroup, the permissions defined to this group will be given to the users that were added.

##### Changing User Group Permissions
When you change add/remove any permissions to the group, the same will happen with the users that are included in this group.

##### Deleting a User Group
When you delete the user group, the system will first, <b>remove all usergroup permissions from all usergroup members</b>. After that, it will delete all informations about the group. (The user will stay in the system, it doesn't delete any user!)
