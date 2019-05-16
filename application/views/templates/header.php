	<!-- THIS HEADER SHOULD BE INCLUDED ON EVERY PAGE -->
<html>

<head>
	<title>TECHQR</title>
	<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/favicon.png')?>"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="<?= base_url('assets/js/main.js')?>"></script>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/styles.css')?>"/>
</head>

<body>
<!--	USED FOR DEBUGGING
	<?php //var_export($this->session->userdata()); ?>
	-->
	
	<!-- students and teachers/admins get different navbars, for security reasons -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<?php if(!isset($_COOKIE['TechQR'])):?>
	<a class="navbar-brand" href="<?= base_url(); ?>">TECHQR</a>
		<!-- User is a teacher or admin -->
		<ul class="navbar-nav mr-auto">
				<!-- gets the base_url from /config/config.php -->
				<!-- enable base_url by adding 'url' to the 'helper' array in /config/autoload.php -->
			<?php if($this->session->userdata('logged_in') && $this->session->userdata('permissions') == 'Admin'): ?>
				<li class="nav-item">
					<a href="<?= base_url("users/index/10/asc/username")?>" class="nav-link">Brugere</a>
				</li>
				<li class="nav-item">
					<a href="<?= base_url('departments/index')?>" class="nav-link">Afdelinger</a>
				</li>
			<?php endif; ?>
			<li class="nav-item">
				<a href="<?= base_url("events/index/10/asc/e_name")?>" class="nav-link">Events</a>
			</li>
			<li class="nav-item">
				<a href="<?= base_url("assignments/index/10/asc/title")?>" class="nav-link">Opgaver</a>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<?php if($this->session->userdata('logged_in')):?>
				<li class="nav-item">
					<a href="<?= base_url('users/view/'.$this->session->userdata('u_id')) ?>" class="nav-link">
						<?= $this->session->userdata('username'); ?>
					</a>
				</li>
				<li class="nav-item"><a href="<?= base_url('users/logout'); ?>" class="nav-link">Log ud</a></li>
			<?php else:?>
				<li class="nav-item"><a href="<?= base_url('users/login'); ?>" class="nav-link">Log ind</a></li>
			<?php endif;?>
		</ul>
	<?php else: ?>
	<a class="navbar-brand" href="<?php $cookie = unserialize($_COOKIE['TechQR']); echo base_url("teams/status/$cookie[e_id]"); ?>">TECHQR</a>
	<?php endif;?>
</nav>
<div class="container">
<br/>
	
	<!-- flashdata messages -->
<?php if($this->session->flashdata('department_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('department_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_edit_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('department_edit_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_edit_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('department_edit_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_user_added')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('department_user_added').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_user_remove_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('department_user_remove_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_user_remove_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('department_user_remove_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_delete_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('department_delete_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('department_delete_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('department_delete_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('user_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_edited')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('user_edited').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_delete_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('user_delete_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_delete_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('user_delete_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_login_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('user_login_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('user_login_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('user_login_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('old_password_mismatch')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('old_password_mismatch').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('new_password_mismatch')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('new_password_mismatch').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('password_changed')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('password_changed').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('ass_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('ass_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('ass_edited')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('ass_edited').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('ass_delete_fail')): ?>
	<?= '<p class="alert alert-warning">'.$this->session->flashdata('ass_delete_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('ass_delete_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('ass_delete_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('team_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('team_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('teams_deleted')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('teams_deleted').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('event_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_delete_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('event_delete_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_delete_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('event_delete_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_edited_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('event_edited_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_edited_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('event_edited_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_added_ass')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('event_added_ass').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_removed_ass')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('event_removed_ass').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('event_reset')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('event_reset').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('pdf_ass_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('pdf_ass_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('pdf_team_created')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('pdf_team_created').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('manage_points_fail')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('manage_points_fail').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('manage_points_success')): ?>
	<?= '<p class="alert alert-success">'.$this->session->flashdata('manage_points_success').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('team_already_answered')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('team_already_answered').'</p>'; ?>
<?php endif; ?>
<?php if($this->session->flashdata('team_wrong_event')): ?>
	<?= '<p class="alert alert-danger">'.$this->session->flashdata('team_wrong_event').'</p>'; ?>
<?php endif; ?>