<?php
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
<html lang="<?php echo $conf["lang"];?>">

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
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <?php
                foreach($categories as $category){
                    $category = str_replace("posts/", "", $category);
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"/posts.php?category=".urlencode($category)."\">".str_replace("-"," ", $category)."</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1><?php echo $conf["title"];?></h1>
        <?php
        for($i=0;$i<min($conf["post_preview_number"], count($posts)); $i++){
            $post_md = file_get_contents($posts[$i]);
            $post_info = explode("£$",exec("cd posts && git log --reverse -n 1 --pretty=format:%at£$%an ".str_replace("posts/",'',$posts[$i])));
            $path_components = explode("/", $posts[$i]);
            $category = (count($path_components) == 3 ? str_replace("-", " ", $path_components[1]) : $locale["uncategorized"]);
            echo "<a href=\"post.php?post=".urlencode($posts[$i])."\"><div class=\"card bg-dark text-white post-preview img-fluid\">
            <div class=\"img-wrapper\"><img src=\"https://picsum.photos/1024/?random&$i\" class=\"card-img-top\" alt=\"Post cover picture\"></div>
            <div class=\"card-img-overlay\">
                <h3 class=\"card-title\">".str_replace("# ",'',explode("\n",$post_md)[0])."</h3>
                <p class=\"card-text\"><span class=\"badge badge-pill badge-light\">".$post_info[1]."</span> <span class=\"badge badge-pill badge-light\">".strftime($conf["datetime_format"], intval($post_info[0]))."</span> <span class=\"badge badge-pill badge-light\">$category</span></p>
                <p class=\"card-text\">Testo</p>
            </div>
        </div></a>";
        }
        ?>
    </div>
</body>

</html>