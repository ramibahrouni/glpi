<?php
include('../inc/includes.php');

global $CFG_GLPI;


/*
 * Checking if the user is already logged in.
 */
Session::checkLoginUser();
//Getting the simple Header which contains only the logo and the account name, settings.
Html::simpleHeader("Entités");
echo "</br>";
echo "</br>";

//Check if the current interface is helpdesk or central  to define the redirection
if (Session::getCurrentInterface() == 'helpdesk') {
    $actionurl = $CFG_GLPI["root_doc"] . "/front/helpdesk.public.php?active_entity=";
} else {
    $actionurl = $CFG_GLPI["root_doc"] . "/front/central.php?active_entity=";
}

//The Super-Admin profile has the privilege of managing the entities(CRUD), for this matter the parent entity should be located in the page of entities.
if ($_SESSION['glpiactiveprofile']['name'] == 'Super-Admin') {
    //Customizing the entities into Card Style
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

//getting the child entities
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

    if ($_SESSION['glpiactive_entity_shortname'] === "Entité racine (Arborescence)") {

        echo "<div class='col-md-4'>";
        echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
        echo "<div id=" . $row['name'] . " class='card card-bz'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
        echo "</a>";
        echo "</div>";


    } else {
        if ($_SESSION['glpiactive_entity_shortname'] === $row['name']) {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id=" . $row['name'] . " class='card card-bz' style='box-shadow: 0 6px 10px rgba(30, 130, 76, 1), 0 0 6px rgba(0,0,0,.05);transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need an IT service? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";


        } else {
            echo "<div class='col-md-4'>";
            echo "<a style='text-decoration: none;border:0)' href='$actionurl" . $row['id'] . "'>";
            echo "<div id=" . $row['name'] . " class='card card-bz'>
                <h3>" . $row['name'] . "</h3>
                <p style='font-family: 'Nunito', sans-serif; text-decoration: #0c0c0b;'>Need help? Raise a request here.</p>
                </div>";
            echo "</a>";
            echo "</div>";
        }
    }
/*
 * Overriding card icon for IT and DSM Entities
 * */
    echo "<script>
var text = document.getElementById(\"" . $row['name'] . "\").children[0].innerHTML;
if (text === \"IT\"){

document.getElementById(\"" . $row['name'] . "\").classList.remove('card-bz');
document.getElementById(\"" . $row['name'] . "\").className += \" card-it\";
document.getElementById(\"" . $row['name'] . "\").children[1].innerHTML = \"Need an IT service?We can help.Raise a request here.\";


}else if(text === \"DSM\"){
document.getElementById(\"" . $row['name'] . "\").classList.remove('card-bz');
document.getElementById(\"" . $row['name'] . "\").className += \" card-dsm\";
document.getElementById(\"" . $row['name'] . "\").children[1].innerHTML = \"Is something broken?We can help.Raise a request here.\";
}

</script>";
}

echo "</div>";
echo "</div>";
echo "</br>";
echo "<link rel='stylesheet' href='../css/Cards.css' type='text/css' media='screen'>";
echo "<link rel='stylesheet' href='../css/bootstrap.css' type='text/css' media='screen'>";
echo "<link href=\"https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900\" rel=\"stylesheet\">";


echo "</br>";
echo "</br>";
echo "</br>";
echo "</br>";
Html::footer();

