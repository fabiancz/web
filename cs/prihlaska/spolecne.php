<?php
require_once dirname(__FILE__).'/../../config.php';
require_once dirname(__FILE__).'/../../lib/db.lib.php';
require_once dirname(__FILE__).'/../../lib/form.php';

if (!$db)
	$db = new sql_db (DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>

<label for="name">Doplňující údaje</label>
<div class="row">
	<div class="col-xs-12 form-group">
		<?php $f->input('how') ?>
	</div>

</div>

<div class="row">
	<div class="col-xs-12 form-group">
		<?php $f->input('note'); ?>
	</div>

</div>

<label for="name">Nastavení VPS</label>
<div class="row">
	<div class="col-xs-12 form-group">
		<?php
			$opts = array();

			while(
				$tpl = $db->findByColumn(
					"cfg_templates",
					"templ_supported",
					"1",
					"templ_order,
					templ_label")
			) {
				$opts[ $tpl['templ_id'] ] = $tpl['templ_label'];
			}
			$f->select('distribution', $opts);
		?>
	</div>

</div>

<div class="row">
	<div class="col-xs-12 form-group">
		<?php
			$opts = array();
			$sql = 'SELECT location_id, location_label
					FROM locations l
					INNER JOIN servers s ON l.location_id = s.server_location
					WHERE s.environment_id = '.$db->check(ENVIRONMENT_ID).'
					GROUP BY location_id
					ORDER BY location_id';
			$rs = $db->query($sql);
			while ($loc = $db->fetch_array($rs)) {
				$opts[ $loc['location_id'] ] = 'Master Internet '.$loc['location_label'];
			}
			$f->select('location', $opts);
		?>
	</div>

</div>

<div class="row">
	<div class="col-xs-12 form-group">
		<?php
			$f->select('currency', array(
				'czk' => '900 Kč na tři měsíce',
				'eur' => '36 € na tři měsíce',
			));
		?>
	</div>
<p>Chceš-li fakturu, pošli po schválení přihlášky na <a href="/kontakt/">podporu</a> fakturační údaje.</p>
</div>


<div class="row">
	<input class="btn btn-default" type="submit" id="send" value="Odeslat">
</div>
