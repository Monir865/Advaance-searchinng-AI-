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


    $search = "i need a Bike whice price is more then 500 to 1000";

    function removeShortElements($array, $length) {
        $k = 0;
        for($i=0; $i<sizeof($array); $i++){
            if(strlen($array[$i]) > $length){
                $newArr[$k++] = $array[$i];
            }
        }
        return $newArr;
    }


    function getCatagory($string,$con){
        $catagory = null;
        $search_arr = explode(" ", $string);
        $filtered = removeShortElements($search_arr,2);

        $stringList = "'" . implode("','", $filtered) . "'";

        $sql = "SELECT p_catagory FROM `products` WHERE p_catagory IN ($stringList)";
        $res = mysqli_query($con, $sql);

       if (mysqli_num_rows($res) > 0) {
            while($data = mysqli_fetch_assoc($res)) {
                $catagory = $data["p_catagory"];
                break;
            }
        }
        return $catagory;
    }
    //getCatagory($search,$conn);


    function isOnePricesGiven($searchArray){
        $check=0;
        for($i=0; $i<sizeof($searchArray); $i++){
            if(is_numeric($searchArray[$i])){
                $p1 = $searchArray[$i];
                $check++;
            }
        }   

        if($check==0)
            return -1;
        elseif($check==1)
            return $p1;
        else
            return false;
    }

    function getBudget($searchArray){
        for($i=0; $i<sizeof($searchArray); $i++){
            if(is_numeric($searchArray[$i])){
                return $i;
            }
        }
        return -1;
    }

    function getPrice($string,$con){
        $searchArray = explode(" ", $string);

        //find price syntex or budget sintex.
        $indexOfPrice = array_search('price', $searchArray);
        if(!$indexOfPrice)$indexOfBudget = getBudget($searchArray);

        //remove unusefull string's
        if($indexOfPrice)
            array_splice($searchArray, 0, $indexOfPrice-2);
        else
            array_splice($searchArray, 0, $indexOfBudget-2);

        //now convert array to string;
        $searchString = implode(" ", $searchArray);
        echo $searchString.'$$$$$$$$';

        //findout bigger than or less than.
        $lessOrequal = "/(of|in)\s+price\s+(\d)+|price\s+(of|in)\s+(\d)+|price\s+\w+\s+(\d)+|price\s+\d+|price\s+\d+|(\w)+\s+(in|of)\s+\d+/i";
        $gratThenOrequal = "/(out)\s+(of)?\s+(price)\s+\d+|(\w{2,3})?(\s)?(price\s)?(more)\s+(than)\s+\d+|price\s+(out)\s+\w+\s+\d+|(out)\s+\w+\s+\d+/i";

        if(preg_match($lessOrequal, $searchString)){
            echo 'less than'.'<------>';
        }

        if(preg_match($gratThenOrequal, $searchString)){
            echo 'more than';
        }





        foreach($searchArray as $val){
           // echo $val.' ';
        }
        //echo $indexOfBudget;





        //remove unusefull string's
        //array_splice($searchArray, 0, $indexOfPrice);


        //$check =  isOnePricesGiven($searchArray);

        



        

    }

    getPrice($search,$conn);



    
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