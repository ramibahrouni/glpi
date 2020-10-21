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

use DbTestCase;

/* Test for inc/planningcsv.class.php */

class PlanningCsv extends \DbTestCase {

   protected function quoteProvider() {
      return [
         ['A simple string'],
         ['A "double quoted" string', '"A ""double quoted"" string"'],
         ['Une chaîne accentuée']
      ];
   }

   /**
    * @dataProvider quoteProvider
    */
   public function testQuote($input, $expected = null) {
      $csv = new \PlanningCsv(1);

      if ($expected === null) {
         $expected = $csv->quote . $input . $csv->quote;
      }

      $this->string($csv->quote($input))->isIdenticalTo($expected);
   }

   public function testList() {
      $this->login();

      //create calendar entryies
      $reminder = new \Reminder();
      $begin = new \DateTime();
      $begin->sub(new \DateInterval('P10D'));
      $fbegin = $begin->format('Y-m-d H:i:s');
      $end = new \DateTime();
      $end->add(new \DateInterval('P5D'));
      $fend = $end->format('Y-m-d H:i:s');
      $rid = (int)$reminder->add([
         'name'            => 'This is a "test"',
         'is_planned'      => 1,
         'begin_view_date' => $fbegin,
         'end_view_date'   => $fend,
         'plan'            => [
            'begin'           => $fbegin,
            'end'             => $fend
         ]
      ]);
      $this->integer($rid)->isGreaterThan(0);

      $ticket = new \Ticket();
      $tid = (int)$ticket->add([
            'name'         => 'ticket title',
            'description'  => 'a description',
            'content'      => '',
            'entities_id'  => getItemByTypeName('Entity', '_test_root_entity', true)
      ]);
      $this->integer($tid)->isGreaterThan(0);
      $this->boolean($ticket->isNewItem())->isFalse();

      $task = new \TicketTask();
      $tasksstates = [
         \Planning::TODO,
         \Planning::TODO,
         \Planning::INFO
      ];
      $date = new \DateTime();
      $date->sub(new \DateInterval('P6M'));
      $tasks = [];
      foreach ($tasksstates as $taskstate) {
         $edate = clone $date;
         $edate->add(new \DateInterval('P2D'));
         $input = [
            'state'           => $taskstate,
            'tickets_id'      => $tid,
            'users_id_tech'   => \Session::getLoginUserID(),
            'begin'           => $date->format('Y-m-d H:i:s'),
            'end'             => $edate->format('Y-m-d H:i:s'),
            'actiontime'      => 172800
         ];
         $ttid = (int)$task->add($input);
         $this->integer($ttid)->isGreaterThan(0);
         $this->boolean($task->getFromDB($ttid))->isTrue();
         $input['id'] = $task->fields['id'];
         if ($taskstate !== \Planning::INFO) {
            //INFO are not present in planning
            $tasks[] = $input;
         }
         $date->add(new \DateInterval('P1Y'));
      }

      $csv = new \PlanningCsv(\Session::getLoginUserID(), 0);

      $user = new \User();
      $this->boolean($user->getFromDB(\Session::getLoginUserID()))->isTrue();

      $expected = [
         [
            'actor'     => $user->getFriendlyName(),
            'title'     => 'This is a "test"',
            'itemtype'  => 'Reminder',
            'items_id'  => (string)$rid,
            'begindate' => $fbegin,
            'enddate'   => $fend
         ]
      ];

      foreach ($tasks as $input) {
         $expected[] = [
            'actor'     => $user->getFriendlyName(),
            'title'     => 'ticket title',
            'itemtype'  => 'Ticket task',
            'items_id'  => (string)$input['id'],
            'begindate' => $input['begin'],
            'enddate'   => $input['end']
         ];
      }

      $this->array($csv->getLines())->isEqualTo($expected);

      $sexpected = "\"Actor\";\"Title\";\"Item type\";\"Item id\";\"Begin date\";\"End date\"".$csv->eol;
      $sexpected .= "\"".$user->getFriendlyName()."\";\"This is a \"\"test\"\"\";\"Reminder\";\"$rid\";\"$fbegin\";\"$fend\"{$csv->eol}";
      foreach ($tasks as $input) {
         $sexpected .= "\"".$user->getFriendlyName()."\";\"ticket title\";\"Ticket task\";\"{$input['id']}\";\"{$input['begin']}\";\"{$input['end']}\"{$csv->eol}";
      }
      $this->output(
         function () use ($csv) {
            $csv->output(false);
         }
      )->isIdenticalTo($sexpected);

      $csv = new \PlanningCsv(\Session::getLoginUserID(), 0, 'Reminder');
      $this->array($csv->getLines())->isEqualTo([$expected[0]]);
   }
}
