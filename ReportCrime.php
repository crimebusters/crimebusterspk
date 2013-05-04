<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 3/15/13
 * Time: 11:23 AM
 * To change this template use File | Settings | File Templates.






require_once ("Controller/CrimeController.php");
require_once("Controller/CategoryController.php");
require_once ("Model/CrimeModel.php");*/
require_once ("Repository/CrimeRepository.php");
require_once ("Repository/CategoryRepository.php");

?>


<html>
    <head>
        <title>
            Report Crime
        </title>
    </head>
    <body>
    <?php
    $crimeRepository = new CrimeRepository();
    $crimeRepository->GetAll();
?>

        <form action="Controller/CrimeController.php" method="POST" id="frmReportCrime">

            <div style="width: 100%; text-align: left;">
                <input type="hidden" id="longitude" name="longitude" value="245">
                <input type="hidden" id="latitude" name="latitude" value="356">

                <br/>

                <label>Category</label>
                <select id="txtCategory" name="txtCategory">

                    <option value="1">Item One</option>
                    <option value="2">Item Two</option>
                    <option value="3">Item Three</option>
                        <?php

                       $categoryRepository = new CategoryRepository();
                        $categories = $categoryRepository->GetAll();

                    foreach ($categories as $category)
                    {
                        echo "<option value='".$category["Category_ID"]."'>";
                        echo $category["Category_Name"];

                        echo "</option>";
                    }

                        ?>

                </select>

                <br/>
                <label>Crime Date and Time</label>
                <input id="txtCrimeDate" name="txtCrimeDate" type="text">
                &nbsp;
                &nbsp;
                &nbsp;
                <input id="txtCrimeTime" name="txtCrimeTime" type="text">
                <br/>
                <label>Description</label>
                <textarea id="txtDescription" name="txtDescription" maxlength="10000" ></textarea>

                <br/>
                <label>Other Details</label>
                <textarea id="txtOtherDetail" name="txtOtherDetail" maxlength="10000" ></textarea>
                <br/>
                <input id="btnSubmit" name="btnSubmit" type="submit" value="Report Crime">
                <input id="btnCancel" name="btnCancel" type="button" value="Cancel">

            </div>

            <div>
                <?php
               $results = $crimeRepository->GetAll();
                echo "done";
                foreach($results as $result)
                {
                   echo  "<pre>".print_r($result) . "</pre>";
                    //echo $result["Crime_ID"];
                }
                ?>

            </div>


        </form>



    </body>


</html>

