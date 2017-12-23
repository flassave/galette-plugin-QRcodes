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
use Galette\Filters\MembersList;


define('GALETTE_BASE_PATH', '../../');
define('QRCODES_PREFIX', 'plugins|QRcodes');

require_once GALETTE_BASE_PATH . 'includes/galette.inc.php';

$modules = $plugins->getModules();

foreach ($modules as $key => $module) {
    if ($module['name'] == 'Galette Grades') {
        //Plugin PassagesDeGrades is installed, keep its path.
        define('PASSAGESDEGRADES_PREFIX', '../' . $key . '/');
    }
}

//Constants and classes from plugin
require_once '_config.inc.php';

//Chargement des fonctions
include("includes/t0k4rt-phpqrcode-d213c48/qrlib.php");

//create data directory
if (!file_exists(PLUGIN_QRCODE_DATA_PATH)) {
    mkdir(PLUGIN_QRCODE_DATA_PATH);
}

//récupération du header de la page précédente

if (isset($_GET['id_adh']) AND isset($_GET['enr'])){
	
	$id_adh = $_GET['id_adh'];

	$deps = array(
		'picture'   => true,
		'groups'    => true,
		'dues'      => true,
		'parent'    => true,
		'children'  => true
	);
	$member = new Adherent((int)$id_adh, $deps);

	$id_m = $member->id;

	//Créer QRcode PassagesDeGrades
	if (!file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.png")){
        if (defined('PASSAGESDEGRADES_PREFIX')) {
		    QRcode::png(PASSAGESDEGRADES_PREFIX . "PassagesDeGrades.php?id_adh=$id_adh", PLUGIN_QRCODE_DATA_PATH . "$id_adh.png", "L", 4, 4);
        }
	}
	
	//Créer QRcode Téléphone
	$phone = $member->phone;

	//if member phone is missing but there is a parent,
	//take the parent phone
	if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png")){
		unlink(PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png");
	}
	if (empty($phone) && $member->hasParent()){
		$phone = $member->parent->phone;
		}
	if (!empty($phone)){
	    QRcode::png("tel:$phone", PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png", "L", 4, 4);
	}

	//Créer QRcode Mail
	$email = $member->email;

	//if member email is missing but there is a parent,
	//take the parent email
	if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png")){
		unlink(PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png");
	}
	if (empty($email) && $member->hasParent()){
		$email = $member->parent->email;
		}
	if (!empty($email)){
	    QRcode::png("mailto:$email", PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png", "L", 4, 4);
	}
	
} else {
	$select = $zdb->select(Adherent::TABLE);
	$result = $zdb->execute($select);
	
	foreach ($result as $r){
		
		$id_adh = $r->id_adh;

		$deps = array(
			'picture'   => true,
			'groups'    => true,
			'dues'      => true,
			'parent'    => true,
			'children'  => true
		);
		$member = new Adherent((int)$id_adh, $deps);
		$id_m = $member->id;

		//Créer QRcode PassagesDeGrades
		if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.png")){
			unlink(PLUGIN_QRCODE_DATA_PATH . "$id_adh.png");
            if (defined('PASSAGESDEGRADES_PREFIX')) {
			    QRcode::png(PASSAGESDEGRADES_PREFIX . "PassagesDeGrades.php?id_adh=$id_adh", PLUGIN_QRCODE_DATA_PATH . "$id_adh.png", "L", 4, 4);
            }
		}
			
		//Créer QRcode Téléphone
		$phone = $member->phone;

		//if member phone is missing but there is a parent,
		//take the parent phone
		if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png")){
			unlink(PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png");
		}
		if (empty($phone) && $member->hasParent()){
			$phone = $member->parent->phone;
			}
		if (!empty($phone)){
		    QRcode::png("tel:$phone", PLUGIN_QRCODE_DATA_PATH . "$id_adh.tel.png", "L", 4, 4);
		}	

		//Créer QRcode Mail
		$email = $member->email;

		//if member email is missing but there is a parent,
		//take the parent email
		if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png")){
			unlink(PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png");
		}
		if (empty($email) && $member->hasParent()){
			$email = $member->parent->email;
			}
		if (!empty($email)){
		    QRcode::png("mailto:$email", PLUGIN_QRCODE_DATA_PATH . "$id_adh.mail.png", "L", 4, 4);
		}
		
	}
	
}

header('location: ' .$_SERVER['HTTP_REFERER']);
die();
