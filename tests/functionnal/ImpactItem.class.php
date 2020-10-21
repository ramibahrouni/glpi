<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2020 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
*/

namespace tests\units;

class ImpactItem extends \DbTestCase {

   public function testFindForItem_inexistent() {
      $computer = getItemByTypeName('Computer', '_test_pc02');

      $this->boolean(\ImpactItem::findForItem($computer, false))->isFalse();
   }

   public function testFindForItem_exist() {
      $impactItemManager = new \ImpactItem();
      $computer = getItemByTypeName('Computer', '_test_pc02');

      $id = $impactItemManager->add([
         'itemtype'  => "Computer",
         'items_id'  => $computer->fields['id'],
         'parent_id' => 0,
      ]);

      $impactItem = \ImpactItem::findForItem($computer);
      $this->integer((int) $impactItem->fields['id'])->isEqualTo($id);
   }
}
