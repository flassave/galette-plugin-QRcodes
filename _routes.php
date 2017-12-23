<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Fullcard routes
 *
 * PHP version 5
 *
 * Copyright © 2016 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
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
 * @package   GaletteMaps
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2016 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     0.9dev 2016-03-02
 */

use Galette\Entity\Adherent;

//Constants and classes from plugin
require_once $module['root'] . '/_config.inc.php';

$this->get(
    __('/member', 'qrcodes_routes') . __('/qrcode', 'qrcodes_routes') . '/{id:\d+}',
    function ($request, $response, $args) use ($module, $module_id) {
        $id = $args['id'];
        include_once __DIR__ . '/includes/t0k4rt-phpqrcode-d213c48/qrlib.php';

        $deps = array(
            'picture'   => false,
            'groups'    => false,
            'dues'      => false,
            'parent'    => true,
        );
        $member = new Adherent($this->zdb, (int)$id, $deps);

        //generate phone QR code
        $phone = $member->phone;
        if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id.tel.png")) {
            unlink(PLUGIN_QRCODE_DATA_PATH . "$id.tel.png");
        }

        //if member phone is missing but there is a parent,
        //take the parent phone
        if (empty($phone) && $member->hasParent()) {
            $phone = $member->parent->phone;
        }

        if (!empty($phone)) {
            QRcode::png("tel:$phone", PLUGIN_QRCODE_DATA_PATH . "$id.tel.png", "L", 4, 4);
        }

        //generate mail QR code
        //Créer QRcode Mail
        $email = $member->getEmail();
        if (file_exists(PLUGIN_QRCODE_DATA_PATH . "$id.mail.png")) {
            unlink(PLUGIN_QRCODE_DATA_PATH . "$id.mail.png");
        }

        if (!empty($email)) {
            QRcode::png("mailto:$email", PLUGIN_QRCODE_DATA_PATH . "$id.mail.png", "L", 4, 4);
        }

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->router->pathFor('member', ['id' => $member->id]));
    }
)->setName('generate_qrcode_member')->add($authenticate);

$this->get(
    __('/show', 'qrcodes_routes') . '/{type:' . __('phone', 'qrcodes_routes') . '|'
    . __('email', 'qrcodes_routes') .  '}/{id:\d+}',
    function ($request, $response, $args) {
        $id_adh = (int)$args['id'];
        $deps = array(
            'picture'   => false,
            'groups'    => false,
            'dues'      => false
        );

        //if loggedin user is a group manager, we have to check
        //he manages a group requested member belongs to.
        if ($this->login->isGroupManager() && $this->login->id != $id_adh) {
            $deps['groups'] = true;
        }

        $adh = new Galette\Entity\Adherent($this->zdb, $id_adh, $deps);

        $is_manager = false;
        if (!$this->login->isAdmin()
            && !$this->login->isStaff()
            && $this->login->id != $id_adh
            && $this->login->isGroupManager()
        ) {
            $groups = $adh->groups;
            foreach ($groups as $group) {
                if ($this->login->isGroupManager($group->getId())) {
                    $is_manager = true;
                    break;
                }
            }
        }

        $picture = null;
        if ($this->login->isAdmin()
            || $this->login->isStaff()
            || $adh->appearsInMembersList()
            || $this->login->login == $adh->login
            || $is_manager
        ) {
            $picture = new GaletteQRCodes\Picture($this->plugins, $id_adh, $args['type']);
        } else {
            $picture = new GaletteQRCodes\Picture();
        }
        $picture->display();
    }
)->setName('show_qrcode');
