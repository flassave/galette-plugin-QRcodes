<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Logo handling
 *
 * PHP version 5
 *
 * Copyright © 2017 The Galette Team
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
 * @package   GaletteQRCodes
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2017 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7dev - 2009-09-26
 */

namespace GaletteQRCodes;

use Analog\Analog;
use Galette\Core\Picture as GalettePicture;
use Galette\Core\Plugins;

/**
 * Logo handling
 *
 * @category  Plugins
 * @name      Picture
 * @package   GaletteQRCodes
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2017 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://galette.tuxfamily.org
 * @since     Available since 0.7dev - 2009-03-16
 */
class Picture extends GalettePicture
{
    private $plugins;

    /**
    * Default constructor.
    *
    * @param Plugins $plugins Plugins
    * @param int     $id_adh  ID of the member
    * @param string  $code    Code to retrieve (either mail or phone)
    */
    public function __construct(Plugins $plugins, $id_adh = '', $code = 'phone')
    {
        $this->plugins = $plugins;
        $this->store_path = PLUGIN_QRCODE_DATA_PATH;

        if ($code == 'mail') {
            $id_adh .= '.mail';
        }

        if (!file_exists($this->store_path)) {
            if (!mkdir($this->store_path)) {
                Analog::log(
                    'Unable to create photo dir `' . $this->store_path . '`.',
                    Analog::ERROR
                );
            } else {
                Analog::log(
                    'New directory `' . $this->store_path . '` has been created',
                    Analog::INFO
                );
            }
        } elseif (!is_dir($this->store_path)) {
            Analog::log(
                'Unable to store plugin images, since `' . $this->store_path .
                '` is not a directory.',
                Analog::WARNING
            );
        }
        parent::__construct($id_adh);
    }

    /**
    * Gets the default picture to show, anyways
    *
    * @see Logo::getDefaultPicture()
    *
    * @return void
    */
    protected function getDefaultPicture()
    {
        global $plugins;
        $this->file_path = $this->plugins->getTemplatesPathFromName('Galette QRCodes') .
            '/images/empty.png';
        $this->format = 'png';
        $this->mime = 'image/png';
        $this->has_picture = false;
    }
}
