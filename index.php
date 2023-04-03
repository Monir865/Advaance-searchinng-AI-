<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    $search = "i need a Bike";

    function removeShortElements($array, $length) {
        $k = 0;
        for($i=0; $i<sizeof($array); $i++){
            if(strlen($array[$i]) > $length){
                $newArr[$k++] = $array[$i];
            }
        }
        return $newArr;
    }

    function searchCatagory($res,$key){
        echo '^^^^^^^^^';
            while($data = mysqli_fetch_assoc($res)){
                    echo $key."+".$data["p_catagory"]."******";

                    if($key == $data["p_catagory"]){
                       // echo "IF : ".$filtered[$i]." ".$data["p_catagory"];
                    }
            }

            echo 'end first';
    }

    function getCatagory($string,$con){
        $search_arr = explode(" ", $string);
        $filtered = removeShortElements($search_arr,2);

        // Convert array to comma-separated string
        //$stringList = implode(',', $filtered);
        $stringList = "'" . implode("','", $filtered) . "'";

        print_r($stringList);
        $sql = "SELECT p_catagory FROM `products` WHERE p_catagory IN ($stringList)";
        $res = mysqli_query($con, $sql);

       if (mysqli_num_rows($res) > 0) {
            echo "<table>";
            echo "<tr><th>Name</th>";
            while($data = mysqli_fetch_assoc($res)) {
                echo "<tr><td>" . $data["p_catagory"];
            }
            echo "</table>";
        }
        

        /*$catagorySql = "SELECT DISTINCT p_catagory FROM `products`";
        $res = mysqli_query($con,$catagorySql);

        echo "Size -> ".sizeof($filtered);

        for($i=0; $i<sizeof($filtered); $i++){
            echo 'hello';
            searchCatagory($res,$filtered[$i]);
        }*/

        //print_r($filtered);
    }

    getCatagory($search,$conn);


    
    $sql = "SELECT *  FROM `products`  WHERE p_catagory = '$search'";
    $res = mysqli_query($conn,$sql);

    if (mysqli_num_rows($res) > 0) {
        echo "<table>";
        echo "<tr><th>Name</th>";
        while($data = mysqli_fetch_assoc($res)) {
            //echo "<tr><td>" . $data["p_name"];
        }
        echo "</table>";
    } else {
       // echo "0 results";
    }









?>