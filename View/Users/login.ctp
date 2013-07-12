<?php



?>
<?php echo $this->Session->flash("auth"); ?>

<h1><?php echo __("Please identify")?></h1>

<?php echo $this->Form->create("FlexAuth.User",array('action'=>'login'))?>

<?php echo $this->Form->input('username')?>

<?php echo $this->Form->input('password')?>

<?php echo $this->Form->submit(__("Enter"));?>

<?php echo $this->Form->end()?>

