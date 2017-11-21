<?php
    
    /**
     * @file        patient.php
     * 
     * @brief       Register patient
     *
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira 
     */

    // Configuration
    require("../includes/config.php"); 

    // Handle GET data
    if (empty($_GET["id"])) {
        // TODO
    } else {
        $id = $_GET["id"];
    }

    if (isset($id)){
        // Query database in order to obtain currently worn devices
        // TODO - Correct query
        $result = query(
            "SELECT distinct serialnum, manufacturer, model
            FROM device JOIN wears ON manufacturer = manuf AND serialnum = snum 
            JOIN patient ON patient = number
            WHERE patient.number = ?
                AND  datediff(current_date(), cast(wears.end AS date)) <= 0", $id);
        if ($result){
            $cur_dev_table = createTable($result, ["serialnum", "manufacturer", "model"]);
        }
        // Query database in order to obtain currently worn devices
        // TODO - Correct query
        $result = query(
            "SELECT distinct serialnum, manufacturer, model
            FROM device JOIN wears ON manufacturer = manuf AND serialnum = snum 
            JOIN patient ON patient = number
            WHERE patient.number = ?
                AND  datediff(current_date(), cast(wears.end AS date)) > 0", $id);
        if ($result){
            $old_dev_table = createTable($result, ["serialnum", "manufacturer", "model"]);
        }
    }

    // Render header
    $title = 'Patient';
    require("../templates/header.php");
?>
 
        <div class="container">

            <?php if (isset($cur_dev_table)): ?>
            <h4>Current devices</h4>
            <?php echo $cur_dev_table ?>
            <?php endif ?>

            <?php if (isset($old_dev_table)): ?>
            <h4>Old devices</h4>
            <?php echo $old_dev_table ?>
            <?php endif ?>

        </div>

<?php
    // Render footer
    require("../templates/footer.php");
?>