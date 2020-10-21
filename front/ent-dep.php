<?php
include('../inc/includes.php');


Html::simpleHeader("Entités");
//echo ($_SESSION['glpiactive_entity']);
$entity = new Entity();
$entity->getFromDB($_SESSION['glpiactive_entity']);

//echo ($entity);
//echo $entity->getName();
//echo Session::getCurrentInterface();
//print_r($_SESSION);

//
//echo '<h3>Hello</h3>';
//Html::header($this->getTypeName(1), '', "admin", "entity");
//global $CFG_GLPI;
//
///// Prefs / Logout link
//echo "<div id='c_preference' >";
//echo "<ul>";
//
//echo "<li id='deconnexion'>";
//echo "<a href='" . $CFG_GLPI["root_doc"] . "/front/logout.php";
///// logout witout noAuto login for extauth
//if (isset($_SESSION['glpiextauth']) && $_SESSION['glpiextauth']) {
//    echo "?noAUTO=1";
//}
//echo "' title=\"" . __s('Logout') . "\" class='fa fa-sign-out-alt'>";
//// check user id : header used for display messages when session logout
//echo "<span class='sr-only'>" . __s('Logout') . "></span>";
//echo "</a>";
//echo "</li>\n";
//
//$username = '';
//$title = __s('My settings');
//if (Session::getLoginUserID()) {
//    $username = formatUserName(0, $_SESSION["glpiname"], $_SESSION["glpirealname"],
//        $_SESSION["glpifirstname"], 0, 20);
//    $title = sprintf(
//        __s('%1$s - %2$s'),
//        __s('My settings'),
//        $username
//    );
//}
//echo "<li id='preferences_link'><a href='" . $CFG_GLPI["root_doc"] . "/front/preference.php' title=\"" .
//    $title . "\" class='fa fa-cog'>";
//echo "<span class='sr-only'>" . __s('My settings') . "</span>";
//
//// check user id : header used for display messages when session logout
//if (Session::getLoginUserID()) {
//    echo "<span id='myname'>{$username}</span>";
//}
//echo "</a></li>";
////class Ent
////{
////
////    function displayHeader() {
////        Html::header($this->getTypeName(1), '', "admin", "entity");
////    }
////
////    private static function displayTopMenu($full)
////    {
////    global $CFG_GLPI;
////
////    /// Prefs / Logout link
////    echo "<div id='c_preference' >";
////    echo "<ul>";
////
////    echo "<li id='deconnexion'>";
////    echo "<a href='" . $CFG_GLPI["root_doc"] . "/front/logout.php";
////    /// logout witout noAuto login for extauth
////    if (isset($_SESSION['glpiextauth']) && $_SESSION['glpiextauth']) {
////        echo "?noAUTO=1";
////    }
////    echo "' title=\"" . __s('Logout') . "\" class='fa fa-sign-out-alt'>";
////    // check user id : header used for display messages when session logout
////    echo "<span class='sr-only'>" . __s('Logout') . "></span>";
////    echo "</a>";
////    echo "</li>\n";
////
////    $username = '';
////    $title = __s('My settings');
////    if (Session::getLoginUserID()) {
////        $username = formatUserName(0, $_SESSION["glpiname"], $_SESSION["glpirealname"],
////            $_SESSION["glpifirstname"], 0, 20);
////        $title = sprintf(
////            __s('%1$s - %2$s'),
////            __s('My settings'),
////            $username
////        );
////    }
////    echo "<li id='preferences_link'><a href='" . $CFG_GLPI["root_doc"] . "/front/preference.php' title=\"" .
////        $title . "\" class='fa fa-cog'>";
////    echo "<span class='sr-only'>" . __s('My settings') . "</span>";
////
////    // check user id : header used for display messages when session logout
////    if (Session::getLoginUserID()) {
////        echo "<span id='myname'>{$username}</span>";
////    }
////    echo "</a></li>";
////
////    if (Config::canUpdate()) {
////        $is_debug_active = $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE;
////        $class = 'debug' . ($is_debug_active ? 'on' : 'off');
////        $title = sprintf(
////            __s('%1$s - %2$s'),
////            __s('Change mode'),
////            $is_debug_active ? __s('Debug mode enabled') : __s('Debug mode disabled')
////        );
////        echo "<li id='debug_mode'>";
////        echo "<a href='{$CFG_GLPI['root_doc']}/ajax/switchdebug.php' class='fa fa-bug $class'
////                title='$title'>";
////        echo "<span class='sr-only'>" . __s('Change mode') . "</span>";
////        echo "</a>";
////        echo "</li>";
////    }
////
////    /// Bookmark load
////    echo "<li id='bookmark_link'>";
////    Ajax::createSlidePanel(
////        'showSavedSearches',
////        [
////            'title' => __('Saved searches'),
////            'url' => $CFG_GLPI['root_doc'] . '/ajax/savedsearch.php?action=show',
////            'icon' => '/pics/menu_config.png',
////            'icon_url' => SavedSearch::getSearchURL(),
////            'icon_txt' => __('Manage saved searches')
////        ]
////    );
////    echo "<a href='#' id='showSavedSearchesLink' class='fa fa-star' title=\"" .
////        __s('Load a saved search') . "\">";
////    echo "<span class='sr-only'>" . __('Saved searches') . "</span>";
////    echo "</a></li>";
////
////    if (Session::getCurrentInterface() == 'central') {
////        $url_help_link = (empty($CFG_GLPI["central_doc_url"])
////            ? "http://glpi-project.org/help-central"
////            : $CFG_GLPI["central_doc_url"]);
////    } else {
////        $url_help_link = (empty($CFG_GLPI["helpdesk_doc_url"])
////            ? "http://glpi-project.org/help-central"
////            : $CFG_GLPI["helpdesk_doc_url"]);
////    }
////
////    echo "<li id='help_link'>" .
////        "<a href='" . $url_help_link . "' target='_blank' title=\"" .
////        __s('Help') . "\" class='fa fa-question'>" .
////        "<span class='sr-only'>" . __('Help') . "</span>";
////    echo "</a></li>";
////
////    if (!GLPI_DEMO_MODE) {
////        echo "<li id='language_link'><a href='" . $CFG_GLPI["root_doc"] .
////            "/front/preference.php?forcetab=User\$1' title=\"" .
////            addslashes(Dropdown::getLanguageName($_SESSION['glpilanguage'])) . "\">" .
////            Dropdown::getLanguageName($_SESSION['glpilanguage']) . "</a></li>";
////    } else {
////        echo "<li id='language_link'><span>" .
////            Dropdown::getLanguageName($_SESSION['glpilanguage']) . "</span></li>";
////    }
////
////    echo "<li id='c_recherche'>\n";
////    if ($full === true) {
////        /// Search engine
////        if ($CFG_GLPI['allow_search_global']) {
////            echo "<form role='search' method='get' action='" . $CFG_GLPI["root_doc"] . "/front/search.php'>\n";
////            echo "<span id='champRecherche'>";
////            echo "<input size='15' type='search' name='globalsearch' placeholder='" . __s('Search') . "' aria-labelledby='globalsearchglass'>";
////            echo "<button type='submit' name='globalsearchglass' id='globalsearchglass'>";
////            echo "<i class='fa fa-search'></i><span class='sr-only'>" . __s('Search') . "</span>";
////            echo "</button>";
////            echo "</span>";
////            Html::closeForm();
////        }
////    }
////    echo "</li>";
////
////    echo "</ul>";
////    echo "</div>\n";
////
////
////    }
////
////
////
////}
Html::footer();