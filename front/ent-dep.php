<?php
include('../inc/includes.php');

global $CFG_GLPI;

Session::checkLoginUser();
Html::simpleHeader("Entités");
echo "</br>";
echo "</br>";
if (Session::getCurrentInterface() == 'helpdesk') {
    $actionurl = $CFG_GLPI["root_doc"] . "/front/helpdesk.public.php?active_entity=";
} else {
    $actionurl = $CFG_GLPI["root_doc"] . "/front/central.php?active_entity=";
}


if ($_SESSION['glpiactiveprofile']['name'] == 'Super-Admin') {

    echo "<div class=\"container\">";
    echo "<div class=\"row\">";



    if ($_SESSION['glpiactive_entity_shortname'] === 'Entité racine' || $_SESSION['glpiactive_entity_shortname'] === 'Entité racine (Arborescence)') {
        echo "<div class='col-md-4'>";
        echo "<a style='text-decoration: none;border:0)' href='$actionurl" . "all" . "'>";
        echo "<div class='card card-bz logo-bz' style='box-shadow: 0 6px 10px rgba(30, 130, 76, 1), 0 0 6px rgba(0,0,0,.05);transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);'>
        <h3>" . "Entité Racine (Arborescence)" . "</h3>
        </div>";

        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
    } else {
        echo "<div class='col-md-4'>";
        echo "<a style='text-decoration: none;border:0)' href='$actionurl" . "all" . "'>";
        echo "<div class='card card-bz logo-bz'>
        <h3>" . "Entité Racine (Arborescence)" . "</h3>
        </div>";
        echo "</a>";

        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";
        echo "</br>";

    }

}
$iterator = $DB->request([
    'SELECT' => [
        'ent.id',
        'ent.name',
        'ent.sons_cache',
        'COUNT' => 'sub_entities.id AS nb_subs'
    ],
    'FROM' => 'glpi_entities AS ent',
    'LEFT JOIN' => [
        'glpi_entities AS sub_entities' => [
            'ON' => [
                'sub_entities' => 'entities_id',
                'ent' => 'id'
            ]
        ]
    ],
    'WHERE' => ['ent.entities_id' => 0],
    'GROUPBY' => ['ent.id', 'ent.name', 'ent.sons_cache'],
    'ORDERBY' => 'name'
]);
echo "<div class=\"container\">";
echo "<div class=\"row\">";
while ($row = $iterator->next()) {

    $path = [
        'id' => $row['id'],
        'text' => $row['name']
    ];

    if ($_SESSION['glpiactive_entity_shortname'] === 'IT') {
        if ($row['name'] === 'IT') {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id= 'card' class='card card-it' style='box-shadow: 0 6px 10px rgba(30, 130, 76, 1), 0 0 6px rgba(0,0,0,.05);transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";

        } elseif ($row['name'] === 'DSM') {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id= 'card' class='card card-dsm'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";
//                echo "</div>";
        }


    } elseif ($_SESSION['glpiactive_entity_shortname'] === 'DSM') {
        if ($row['name'] === 'IT') {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id= 'card' class='card card-it'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";
        } elseif ($row['name'] === 'DSM') {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id= 'card' class='card card-dsm' style='box-shadow: 0 6px 10px rgba(30, 130, 76, 1), 0 0 6px rgba(0,0,0,.05);transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";
        }

    } else {
        if ($row['name'] === "IT") {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'><div class='card card-it'><h3>" . $row['name'] . "</h3>
        <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
        </div>
        </a>";
            echo "</div>";
        } elseif ($row['name'] === "DSM") {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'><div class='card card-dsm'><h3>" . $row['name'] . "</h3>
        <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Is something Broken? We can help.Raise a request here.</p>
        </div>
        </a>";
            echo "</div>";
        }
    }


}
echo "</div>";
echo "</div>";
echo "</br>";
echo "<link rel='stylesheet' href='../css/Cards.css' type='text/css' media='screen'>";
echo "<link rel='stylesheet' href='../css/bootstrap.css' type='text/css' media='screen'>";
echo "<link href=\"https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900\" rel=\"stylesheet\">";


//print_r($_SESSION['glpiactive_entity_shortname']);


//print_r($_SESSION["glpiactive_entity"]);
//print_r($_SESSION['glpiactive_entity']);
//print_r(getSonsOf('glpi_entities', '2'));
//// Root node
////print_r($_SESSION['glpiactiveprofile']['entities']);
//if ($_SESSION['glpiactiveprofile']['entities'] != 0) {
//}

//$entity = new Entity();
//$entity->getFromDB($_SESSION['glpiactive_entity']);
//print_r ($_GET['node']);
//print_r(getSonsOf('glpi_entities',$_SESSION['glpiactive_entity']));
//print_r( $_SESSION['glpiactiveprofile']['entities']);
//print_r($_SESSION['glpiactive_entity_shortname']);
//echo(Session::changeActiveEntities());
//echo(Session::changeActiveEntities());
//echo Session::getLoginUserID();
echo "</br>";
echo "</br>";
echo "</br>";
echo "</br>";
Html::footer();

