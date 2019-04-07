<?php

include("tools/parsedown/Parsedown.php");

// If this is the first run, init the posts repository
if(!is_dir("posts/.git")){
    exec("git init posts");
}

// The categories are the folders inside the posts folder
$categories = glob("posts/*", GLOB_ONLYDIR);

// The posts are markdown files inside the posts directory
$posts = array_merge(glob("posts/*.md"), glob("posts/*/*.md"));
// Sort them by creation date
usort($posts, create_function('$a,$b', 'return intval(exec("cd posts && git log -n 1 --pretty=format:%at ".$a)) - intval(exec("cd posts && git log -n 1 --pretty=format:%at ".$b));'));

//Load the configuration from conf.json
$conf = json_decode(file_get_contents("conf.json"), true);
$locale = json_decode(file_get_contents("locales/".$conf["locale"].".json"), true);
?>
<!DOCTYPE html>
<html lang="<?php echo $conf["locale"];?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $conf["blog_title"];?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="style.css">
    <!--link rel="stylesheet" href="theme.css"-->
    <style>
        img{
            max-height: 50vh;
            max-width: 100%;
            display: block;
            margin: .5em auto;
            border-radius: .25em;
            /*box-shadow: 1px 1px 1px black;*/
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/."><?php echo $conf["blog_title"];?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                foreach($categories as $category){
                    $category = str_replace("posts/", "", $category);
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"/posts.php?category=".urlencode($category)."\">".str_replace("-"," ", $category)."</a></li>";
                }
                foreach($conf["header_links"] as $entry=>$link){
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".$link."\">".$entry."</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <?php
        $Parsedown = new Parsedown();
        echo $Parsedown->text(file_get_contents($_GET["post"]));
        ?>
    </div>
</body>

</html>