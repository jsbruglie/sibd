<?php

    // configuration
    require("../includes/config.php"); 

    require("../templates/header.php");

?>

<?php
    
    // Search string
    $name = "Miley";
    // Append % to the search string in order to perform partial match in SQL query
    $name_arg = $name . "%";

    // Search for a patient by name and create HTML table
    $result = query("SELECT * FROM patient WHERE name LIKE ?", $name_arg);
    $table = createTable($result, ["name", "birthday", "address"]);

?>

    <div class="container">
        <?php
            if (!$result)
            {
                echo "No matches found. Register a new patient?";
            }
            else
            {
                echo $table;
            }
        ?>
    </div>

<?php
    require("../templates/footer.php");
?>