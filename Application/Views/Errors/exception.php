<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo get_class($exception); ?></title>
	<style>
		<!--
		tr{background-color:#CCCCFF}
		tr:hover{background-color:#ff0000;}

		-->
	</style>
</head>

<body>

<?php


//phpinfo();
if( $exception instanceof Exception ) {

	?>
	<div style="margin:10px;">
		<table cellpadding="0" cellspacing="0" style="font-family:Courier New; font-size:12px; font-weight:bold; border:solid 1px #999999; border-width: 0 0 1px 1px;">
			<tr>
				<th colspan="2" style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#9999CC"><?php echo get_class($exception); ?></th>
			</tr>

			<tr>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF">Message: </td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#dddddd"><?php echo $exception->getMessage(); ?></td>
			</tr>

			<tr>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF">Code: </td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#dddddd"><?php echo $exception->getCode(); ?></td>
			</tr>

			<tr>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF">File: </td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#dddddd"><?php echo $exception->getFile(); ?></td>
			</tr>

			<tr>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF">Line: </td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#dddddd"><?php echo $exception->getLine(); ?></td>
			</tr>

		</table>
	</div>



	<div style="margin:10px;">

		<table cellpadding="0" cellspacing="0" style="font-family:Courier New; font-size:12px; font-weight:bold; border:solid 1px #999999; border-width: 0 0 1px 1px;">
			<tr>
				<th style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#9999CC">Stack Trace</th>
			</tr>
			<?php foreach (explode(chr(10),str_replace("/home/stefan/workspace/okazii.ro/shops/","",$exception->getTraceAsString())) as $line) { ?>
				<tr>
					<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF"><?php echo $line; ?></td>
				</tr>
			<?php } ?>
		</table>

	</div>
<?php } ?>

<?php

$stackTrace = $exception->getTrace();
//CoreMisc::printr($a);

?>

<div style="margin:10px;">

	<table cellpadding="0" cellspacing="0" style="font-family:Courier New; font-size:12px; font-weight:bold; border:solid 1px #999999; border-width: 0 0 1px 1px;">
		<tr>
			<th colspan="4" style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#9999CC">Stack Trace</th>
		</tr>
		<?php for ($i=0 ; $i<count($stackTrace) ; $i++ ) { ?>
			<tr>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF"><?php echo "#$i"; ?></td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF"><?php echo str_replace("/home/stefan/workspace/okazii.ro/shops/","",$stackTrace[$i]['file']); ?></td>
				<td align="center" style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF"><?php echo $stackTrace[$i]['line']; ?></td>
				<td style="border:solid 1px #999999; border-width:1px 1px 0 0; padding: 3px; background-color:#CCCCFF"><?php echo ( isset($stackTrace[$i]['class']) ? $stackTrace[$i]['class'] . $stackTrace[$i]['type'] : "" ) . $stackTrace[$i]['function']. "('".implode("','", $stackTrace[$i]['args'])."')"; ?></td>
			</tr>
		<?php } ?>
	</table>
</div>


</body>
</html>

