<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Configuration file for QRcodes plugin
 *
 * PHP version 5
 *
 * Copyright © 2013 The Galette Team
 *
 * This file is part of Galette (http://galette.eu).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugins
 * @package   QRcodes
 *
 * @author    Frédéric LASSAVE <f.lassave@free.fr>
 * @copyright 2011 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or later
 * @version   SVN: $Id$
 * @link      http://galette.eu
 * @since     Available since 0.8.2.3
 */

use Galette\Entity\Adherent;
use Galette\Entity\FieldsConfig;
use Galette\Entity\Texts;
use Galette\Repository\Members;
use Galette\Repository\PdfModels;
use Galette\Entity\DynamicFields;


define('GALETTE_BASE_PATH', '../../');
define('QRCODES_PREFIX', 'plugins|QRcodes');
//Lien du plugin PassagesDeGrades, à écrire (en dur, en fonction du serveur), pour coder dans le QRcode 
define('PASSAGESDEGRADES_PREFIX','http://ascjudocazeres.legtux.org/galette/plugins/PassagesDeGrades/');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

//Constants and classes from plugin
require_once '_config.inc.php';

//Chargement des fonctions
include("includes/fonctions.inc.php");
include("includes/t0k4rt-phpqrcode-d213c48/qrlib.php");

//récupération du header de la page précédente
$qstring = $_SERVER['HTTP_REFERER'];

if (isset($_GET['id_adh']) AND isset($_GET['enr'])){
	
	$id_adh = $_GET['id_adh'];
	$dyn_fields = new DynamicFields();

	$deps = array(
		'picture'   => true,
		'groups'    => true,
		'dues'      => true,
		'parent'    => true,
		'children'  => true
	);
	$member = new Adherent((int)$id_adh, $deps);

	// flagging fields visibility
	$fc = new FieldsConfig(Adherent::TABLE, $members_fields, $members_fields_cats);
	$visibles = $fc->getVisibilities();
	// declare dynamic field values
	$adherent['dyn'] = $dyn_fields->getFields('adh', $id_adh, true);

	// - declare dynamic fields for display
	$disabled['dyn'] = array();
	$dynamic_fields = $dyn_fields->prepareForDisplay(
		'adh',
		$adherent['dyn'],
		$disabled['dyn'],
		0
	);

	$id_m = $member->id;

	//Créer QRcode PassagesDeGrades
	QRcode::png(PASSAGESDEGRADES_PREFIX . "PassagesDeGrades.php?id_adh=$id_adh", "$id_adh.png", "L", 4, 4);

	if (!rename("$id_adh.png", "datas/qrcodes/$id_adh.png")){
		echo "Impossible de renommer.";
	}

	//Créer QRcode Téléphone
	QRcode::png("tel:$member->phone", "$id_adh.tel.png", "L", 4, 4);

	if (!rename("$id_adh.tel.png", "datas/qrcodes/$id_adh.tel.png")){
		echo "Impossible de renommer.";
	}

	//Créer QRcode Mail
	QRcode::png("mailto:$member->email", "$id_adh.mail.png", "L", 4, 4);

	if (!rename("$id_adh.mail.png", "datas/qrcodes/$id_adh.mail.png")){
		echo "Impossible de renommer.";
	}
	
	//Si appel depuis la liste adhérents, retour à la liste adhérents
	if (isset($_GET['enr']) AND $_GET['enr'] == 1){
		header('location: '.$qstring);
		die();
	}
	
} else {
	
	echo "toto";
}
?>