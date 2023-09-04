<?php


    function max_page($tname, $conn, $itemsPerPage) {

        $sqlCount = "SELECT COUNT(*) as total FROM {$tname}";
        $resultCount = $conn->query($sqlCount);
        $rowCount = $resultCount->fetch_assoc();
        $totalCount = $rowCount['total'];

        $maxPages = ceil($totalCount / $itemsPerPage);
        
        return $maxPages;
    }

?>