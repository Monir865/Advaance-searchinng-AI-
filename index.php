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


    $search = "i want to buy a telivision of 42 fit out of 5000";

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
        $k=0;
        for($i=0; $i<sizeof($searchArray); $i++){
            if(is_numeric($searchArray[$i])){
                $p1 = $searchArray[$i];
                $multiPrice[$k++] = $searchArray[$i];
                $check++;
            }
        }   

        //$approval = "";

        if($check==0)
            return -1;
        elseif($check==1)
            return $p1;
        else
            return $multiPrice;
    }

    function isPrice($searchArray,$i){
        array_splice($searchArray, 0, $i);
        $searchStr = implode(" ", $searchArray);
        $detectPricePtrn = '/\b\d+(?:\.\d+)?\s+(?:feet|scal|inch|y[e]?rs|fit|m(?:eter|etre)s?)\b/i';
        return preg_match($detectPricePtrn, $searchStr)?false:true;
    }

    function getBudget($searchArray){
        $index = -1;
        for($i=0; $i<sizeof($searchArray); $i++){
            if(is_numeric($searchArray[$i])){
                if(isPrice($searchArray,$i)){
                    $index = $i;
                    break;
                };
            }
        }
        return $index;
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


        //see our filtred string
        print_r($searchArray).' </br>';


        //now convert array to string;
        $searchString = implode(" ", $searchArray);

        //in this step we have to detect how much price's are given by user
        $check =  isOnePricesGiven($searchArray);


        if($prices =  is_array($check)){
            return $check;

        }elseif($check == -1){
            return -1;

        }else{

            //findout bigger than or less than.
            $lessOrequal = "/\b\w+\b(?:\s+(?:in|of|are|which)\s+)?(?:a\s+)?(?:\w+\s+)?(?:price\s+(?:around|in|of|less\s+than|under|are|to)\s+)?(\d{4,6})(?:\s+\w+)?\b/i";
            $gratThenOrequal = "/\b(price|cost)\b.*?(more than|greater|upper|out of|between)\s+(\d+).*?(\d+)?/i";
            
            $l = "/\b\w+\b(?:\s+(?:in|of|are|which)\s+)?(?:a\s+)?(?:\w+\s+)?(?:price\s+(?:around|in|of|less\s+than|under|are|to)\s+)?(\d{4,6})(?:\s+\w+)?\b|(\w\d)+[\'\"^*]+(\s+)?(\w+)?(\s+)?(\w+)?(\s+)?\d+/i";
            $g = "/\b(price|cost)\b.*?(more than|greater|upper|out of|between)\s+(\d+).*?(\d+)?|(\w\d)+[\'\"^*]+(\s+)?(\w+)?(\s+)?(\w+)?(\s+)?\d+/i";

            if(preg_match($g, $searchString)){
                return 'greater '.$check;
            }
            if(preg_match($l, $searchString)){
                return 'less '.$check;
            }

        }




        foreach($searchArray as $val){
           // echo $val.' ';
        }
        //echo $indexOfBudget;

    }

    $pp = getPrice($search,$conn);

    print_r($pp);


    
    /*
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
    }*/









?>