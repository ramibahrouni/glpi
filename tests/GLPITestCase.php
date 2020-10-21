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

use Glpi\Cache\SimpleCache;

// Main GLPI test case. All tests should extends this class.

class GLPITestCase extends atoum {
   private $int;
   private $str;
   protected $cached_methods = [];
   protected $nscache;

   public function setUp() {
      // By default, no session, not connected
      $_SESSION = [
         'glpi_use_mode'         => Session::NORMAL_MODE,
         'glpi_currenttime'      => date("Y-m-d H:i:s"),
         'glpiis_ids_visible'    => 0
      ];
   }

   public function beforeTestMethod($method) {
      if (in_array($method, $this->cached_methods)) {
         $this->nscache = 'glpitestcache' . GLPI_VERSION;
         global $GLPI_CACHE;
         //run with cache
         define('CACHED_TESTS', true);
         //LaminasCache does not works with PHP5 acpu...
         $adapter = (version_compare(PHP_VERSION, '7.0.0') >= 0) ? 'apcu' : 'apc';
         $storage = \Laminas\Cache\StorageFactory::factory([
            'adapter'   => $adapter,
            'options'   => [
               'namespace' => $this->nscache
            ]
         ]);
         $GLPI_CACHE = new SimpleCache($storage, GLPI_CACHE_DIR, false);
      }
   }

   public function afterTestMethod($method) {
      if (in_array($method, $this->cached_methods)) {
         global $GLPI_CACHE;
         if ($GLPI_CACHE != null) {
            $GLPI_CACHE->clear();
         }
         $GLPI_CACHE = false;
      }

      global $PHPLOGGER;
      $handlers = $PHPLOGGER->getHandlers();
      foreach ($handlers as $handler) {
         $records  = $handler->getRecords();
         $messages = array_column($records, 'message');
         $this->integer(count($records))
            ->isEqualTo(
               0,
               sprintf(
                  'Unexpected logs records found in %s::%s() test: %s',
                  static::class,
                  $method,
                  "\n" . implode("\n", $messages)
               )
            );
      }
   }

   /**
    * Get a unique random string
    */
   protected function getUniqueString() {
      if (is_null($this->str)) {
         return $this->str = uniqid('str');
      }
      return $this->str .= 'x';
   }

   /**
    * Get a unique random integer
    */
   protected function getUniqueInteger() {
      if (is_null($this->int)) {
         return $this->int = mt_rand(1000, 10000);
      }
      return $this->int++;
   }
}
