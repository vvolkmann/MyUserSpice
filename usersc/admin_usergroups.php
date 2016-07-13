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

//Forms posted
if(!empty($_POST)) {
	$token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}

  	//Delete user group
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteUserGroup($deletions)){
			$successes[] = "User Group Deleted";
		}
	}

  	//Create new user group
	if(!empty($_POST['name'])) {
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
			$db->insert('user_groups',$fields);
			$successes[] = "User Group Created";
		}else{

		}
	}
}

$usergroupsData = fetchAllUserGroups(); //Retrieve list of all user groups
?>

<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-sm-12">
				<!-- Left Column -->
				<div class="class col-sm-3"></div>
				
				<!-- Main Center Column -->
				<div class="class col-sm-6">
					<div id="form-errors">
						<?=$validation->display_errors();?>
					</div>
					<!-- Content Goes Here. Class width can be adjusted -->
					<?php
					echo resultBlock($errors,$successes);
					$errors = [];
					$successes = [];
					?>
					<form name='adminGroups' action='<?=$_SERVER['PHP_SELF']?>' method='post'>
						<h2>Create a new User Group</h2>
						<p>
							<label>Group Name:</label>
							<input type='text' name='name' />
						</p>

						<br>
						<table class='table table-hover table-list-search'>
							<tr>
								<th>Delete</th><th>User Group Name</th>
							</tr>

							<?php
				      			//List each permission level
							foreach ($usergroupsData as $group) {
								?>
								<tr>
									<td><input type='checkbox' name='delete[<?=$group->id?>]' id='delete[<?=$group->id?>]' value='<?=$group->id?>'></td>
									<td><a href='admin_usergroup.php?id=<?=$group->id?>'><?=$group->name?></a></td>
								</tr>
								<?php
							}
							?>
						</table>

						<input type="hidden" name="csrf" value="<?=Token::generate();?>" >
						<input class='btn btn-primary' type='submit' name='Submit' value='Add/Delete' /><br><br>
					</form>
					<!-- End of main content section -->
				</div>

				<!-- Right Column -->
				<div class="class col-sm-1"></div>
			</div>
		</div>
	</div>
</div>

<!-- /.row -->

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->
<script src="<?=$us_url_root?>users/js/search.js" charset="utf-8"></script>

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
