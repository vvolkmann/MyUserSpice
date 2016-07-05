<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
?>
<?php require_once '../users/init.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/header.php'; ?>
<?php require_once $abs_us_root.$us_url_root.'users/includes/navigation.php'; ?>
<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
$validation = new Validate();

$errors = [];
$successes = [];

$usergroupId = $_GET['id'];

//Check if selected user group exists
if(!usergroupIdExists($usergroupId)){
	Redirect::to("admin_usergroups.php"); die();
}

//Fetch information specific to user group
$usergroupsDetails = fetchUsergroupDetails($usergroupId);
//Forms posted
if(!empty($_POST)){
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}

  	//Delete selected user group
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteUserGroup($deletions)){
			$successes[] = "User Group Deleted";
			Redirect::to('admin_usergroups.php');
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	} else {
    	//Update user group name
		if($usergroupsDetails['name'] != $_POST['name']) {
			$usergroup = Input::get('name');
			$fields=array('name'=>$usergroup);
			//NEW Validations
			$validation->check($_POST,array(
				'name' => array(
					'display' => 'User Group Name',
					'required' => true,
					'unique' => 'user_groups',
					'min' => 1,
					'max' => 25
					)
				));
			if($validation->passed()){
				$db->update('user_groups',$usergroupId,$fields);
				$successes[] = "User Group Updated";
			}else{

			}
		}
	}

	//Remove group members
	if(!empty($_POST['removeMember'])){
		$remove = $_POST['removeMember'];
		if ($deletion_count = removeUserGroupMember($usergroupId, $remove)) {
			$successes[] = "User removed from the user group";
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}

    //Add group members
	if(!empty($_POST['addMember'])){
		$add = $_POST['addMember'];
		if ($addition_count = addUserGroupMember($usergroupId, $add)) {
			$successes[] = "User added to the user group";
		}
		else {
			$errors[] = lang("SQL_ERROR");
		}
	}

	//TODO
	//ADD/Remove permissions from group
}

//Fetch information specific to user group
$usergroupsDetails = fetchUsergroupDetails($usergroupId);
//Retrieve list of users in this group
$usergroupUsers = fetchUserGroupUsers($usergroupId);
//Fetch all users
$userData = fetchAllUsers();
//list of permissions of this group
$usergroupPermissions = fetchUserGroupPermissions($usergroupId);
//list of possible permissions
$permissionsData = fetchAllPermissions();
?>

<div id="page-wrapper">
	<div class="container">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-xs-12">
				<div id="form-errors">
					<?=$validation->display_errors();?>
				</div>
				<!-- Main Center Column -->
				<!-- Content Goes Here. Class width can be adjusted -->
				<h1>Configure Details for this User Group</h1>
				<?php
				echo resultBlock($errors,$successes);
				$errors = [];
				$successes = [];
				?>

				<form name='adminUsergroup' action='<?=$_SERVER['PHP_SELF']?>?id=<?=$usergroupId?>' method='post'>
					<table class='table'>
						<tr>
							<td>
								<h3>User Group Information</h3>
								<div id='regbox'>
									<p>
										<label>ID:</label>
										<?=$usergroupsDetails['id']?>
									</p>
									<p>
										<label>Name:</label>
										<input type='text' name='name' value='<?=$usergroupsDetails['name']?>' />
									</p>
									<h3>Delete this Level?</h3>
									<label>Delete:</label>
									<input type='checkbox' name='delete[<?=$usergroupsDetails['id']?>]' id='delete[<?=$usergroupsDetails['id']?>]' value='<?=$usergroupsDetails['id']?>'>
								</div>
							</td>
							<td>
								<h3>User Group Membership</h3>
								<div id='regbox'>
									<p>
										<strong>Remove Members:</strong>
										<?php
										//Display list of members of usergroups
										$usergroup_users = [];
										foreach($usergroupUsers as $gUser){
											$usergroup_users[] = $gUser->user_id;
										}
										foreach ($userData as $uData){
											if(in_array($uData->id,$usergroup_users)){ ?>
												<br><input type='checkbox' name='removeMember[]' id='removeMember[]' value='<?=$uData->id;?>'> <?=$uData->username;
											}
										}
										?>
									</p>
									<p>
										<strong>Add Members:</strong>
										<?php
										//List users without this usergroup
										$perm_losers = [];
										foreach($usergroupUsers as $gUser){
											$perm_losers[] = $gUser->user_id;
										}
										foreach ($userData as $uData){
											if(!in_array($uData->id,$perm_losers)){ ?>
												<br><input type='checkbox' name='addMember[]' id='addMember[]' value='<?=$uData->id?>'> <?=$uData->username;
											}
										}
										?>
									</p>
								</div>
							</td>
							<td>
								<h3>User Group Permissions</h3>
								<div id='regbox'>
									<p>
										<strong>Remove This Permission:</strong>
										<?php
										//Display list of permissions of usergroups
										$usergroup_perms = [];
										foreach($usergroupPermissions as $perm){
											$usergroup_perms[] = $perm->permission_id;
										}
										foreach ($permissionsData as $pData){
											if(in_array($pData->id, $usergroup_perms)){ ?>
												<br><input type='checkbox' name='removePermission[]' id='removePermission[]' value='<?=$pData->id;?>'> <?=$pData->name;
											}
										}
										?>
									</p>
									<p><strong>Add This Permission:</strong>
										<?php
										//List users without permission level
										$usergroup_losers = [];
										foreach($usergroupPermissions as $perm){
											$usergroup_losers[] = $perm->permission_id;
										}
										foreach ($permissionsData as $pData){
											if(!in_array($pData->id,$usergroup_losers)){ ?>
												<br><input type='checkbox' name='addPermission[]' id='addPermission[]' value='<?=$pData->id?>'> <?=$pData->name;
											}
										}
										?>
									</p>
								</div>
							</td>
						</tr>
					</table>
					<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
					<p>
						<label>&nbsp;</label>
						<input class='btn btn-primary' type='submit' value='Update User Group' class='submit' />
					</p>
				</form>
				<!-- End of main content section -->
			</div>
		</div><!-- /.row -->
	</div>
</div>

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
