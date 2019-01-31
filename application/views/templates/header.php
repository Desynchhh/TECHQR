<!-- THIS HEADER SHOULD BE INCLUDED ON EVERY PAGE -->
<html>

<head>
	<title>TECHQR</title>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/styles.css')?>"/>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="<?= base_url(); ?>">TECHQR</a>
		<ul class="navbar-nav mr-auto">
			<!-- gets the base_url from /config/config.php -->
			<!-- enable base_url by adding 'url' to the 'helper' array in /config/autoload.php -->
			<li class="nav-item">
				<a href="<?= base_url('users')?>" class="nav-link">Brugere</a>
			</li>
			<li class="nav-item">
				<a href="<?= base_url('departments')?>" class="nav-link">Afdelinger</a>
			</li>
			<li class="nav-item">
				<a href="<?= base_url('events')?>" class="nav-link">Events</a>
			</li>
			<li class="nav-item">
				<a href="<?= base_url('assignments')?>" class="nav-link">Opgaver</a>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
				<li class="nav-item"><a href="<?= base_url('users/login'); ?>" class="nav-link">Log ind</a></li>
				<li class="nav-item"><a href="<?= base_url('users/register'); ?>" class="nav-link">Opret</a></li>
				<li class="nav-item"><a href="<?= base_url('users/logout'); ?>" class="nav-link">Log ud</a></li>
		</ul>
	</nav>
	<div class="container">
		<br/>
		
		<!-- flashdata messages -->
	<?php if($this->session->flashdata('department_created')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('department_created').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('department_edited')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('department_edited').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('department_user_added')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('department_user_added').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('department_deleted')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('department_deleted').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('user_created')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('user_created').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('user_edited')): ?>
		<?= '<p class="alert alert-success">'.$this->session->flashdata('user_edited').'</p>'; ?>
	<?php endif; ?>
	<?php if($this->session->flashdata('user_deleted')): ?>
		<?= '<p class="alert alert-warning">'.$this->session->flashdata('user_deleted').'</p>'; ?>
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