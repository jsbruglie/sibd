<?php

    // configuration
    require("../includes/config.php"); 

    require("../templates/header.php");

    // Patient name
    $name_err = $name = "";

    // Handle POST data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $name_err = "Please insert a patient name!";
        } else {
            $name = $_POST["name"];
        }
    }

    $valid = !empty($name) && empty($name_err);

    if (!$name_err){
        // Append % to the search string in order to perform partial match in SQL query
        $name_arg = $name . "%";

        // Search for a patient by name and create HTML table
        $result = query("SELECT * FROM patient WHERE name LIKE ?", $name_arg);
        $table = createTable($result, ["name", "birthday", "address"]);
    }
?>
 
    <div class="container">

        <form action="" method="post">
            <fieldset>
                <div class="control-group">
                    <input autofocus name="name" placeholder="Patient name" type="text"/>
                    <span class="error"><?php echo $name_err;?></span>
                    <button type="submit" class="btn">Search</button>
                </div>
            </fieldset>
        </form>

        <?php
            if ($valid){
                if (!$result) {
                    echo 'No matches found. <a href="register.php">Register</a> a new patient?';
                } else {
                    echo $table;
                }
            }
        ?>

    </div>

<?php
    require("../templates/footer.php");
?>