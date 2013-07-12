<?php



?>

<strong><?php echo __('Welcome for this App')?></strong>
<br/>
<?php //debug(AuthFlexComponent::user()); ?>


<?php debug(AuthFlexComponent::isPermitted("FlexAuth.Roles.index"));?>