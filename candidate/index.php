<?php 
    require_once("inc/header.php");



    if(isset($_GET['homepage']))
    {
        require_once("inc/homepage.php");
    }else if(isset($_GET['addCandidatePage']))
    {
        require_once("inc/add_candidates.php");
    }else if(isset($_GET['viewResult']))
    {
        require_once("inc/viewResults.php");
    }

?>


<?php 
    require_once("inc/footer.php");
?>