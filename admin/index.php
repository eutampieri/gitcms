<?php
$conf = json_decode(file_get_contents(dirname(dirname(__FILE__))."/conf.json"), true);
require_once(dirname(dirname(__FILE__))."/plugins/".$conf["auth_plugin"]."/main.php");
require_once(dirname(dirname(__FILE__))."/tools/tools.php");

$user = null;
$auth = new Auth();

if(isset($_COOKIE["gitcms_session"])){
    $user = $auth->check_session($_COOKIE["gitcms_session"]);
    if($user === false){
        header("Location: ../login.php");
        die();
    }
} else{
    header("Location: ../login.php");
    die();
}

$update_available = exec(dirname(dirname(__FILE__))."/tools/cli/check_updates.sh") == "1"; //Checking if repo is up to date with master
?>
<!DOCTYPE html>
<html lang="<?php echo $conf["locale"];?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php $conf["blog_title"]." - ".___("dashboard");?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <!--link rel="stylesheet" href="theme.css"-->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/."><?php echo $conf["blog_title"];?></a>
    </nav>
    <div class="container">
        <div class="alert alert-info<?php echo $update_available?"":" d-none";?>" role="alert">
            <div><?php ___("update-available");?></div>
            <a role="button" href="api.php?action=update" class="btn btn-info"><?php ___("update-now");?></a>
        </div>

        <h1 class="display-4"><?php ___("dashboard");?></h1>
        <h2><?php ___("posts");?></h2>
        <!--div class="form-group">
            <label for="language"><?php ___("auth-plugin");?></label>
            <select class="form-control" id="language" name="auth_plugin">
                <?php
                foreach(glob("../plugins/*/auth.plugtype") as $auth_plugin){
                    $plugin_desc = file_get_contents($auth_plugin);
                    $plugin_name = dirname($auth_plugin);
                    echo "<option value=\"$plugin_name\">$plugin_name</option>\n";
                }
                ?>
            </select>
        </div-->
        <div class="table-responsive">
            <table class="table">
            <thead><tr>
                <th><?php ___("post-title");?></th>
                <th><?php ___("post-author");?></th>
                <th><?php ___("post-last-update");?></th>
                <th><?php ___("post-category");?></th>
                <th></th>
            </tr></thead><tbody>
            <?php
            $posts = array_merge(glob(dirname(dirname(__FILE__))."/posts/*.md"), glob(dirname(dirname(__FILE__))."/posts/*/*.md"));
            for($i=0;$i<count($posts); $i++){
                $post_md = file_get_contents($posts[$i]);
                $post_info = explode("£$", explode("\n", exec("cd ".dirname(dirname(__FILE__))."/posts && git log --pretty=format:%at£$%an ".str_replace(dirname(dirname(__FILE__))."/posts/",'',$posts[$i])))[0]);
                $path_components = explode("/", str_replace(dirname(dirname(__FILE__))."/", "", $posts[$i]));
                $category = (count($path_components) == 3 ? str_replace("-", " ", $path_components[1]) : __("uncategorized"));
                echo "<tr><td>".str_replace("# ",'',explode("\n",$post_md)[0])."</td>
                    <td>".$post_info[1]."</td> <td>".gmstrftime($conf["datetime_format"], intval($post_info[0]))."</td> <td>$category</td><td><a role=\"button\" class=\"btn btn-primary\" href=\"#\"><i class=\"fas fa-edit\"></i></a> <button class=\"btn btn-danger\"><i class=\"fas fa-trash-alt\"></i></button></td>
                    </tr>";
            }
    
            ?>
        </tbody></table></div>
    </div>
</body>
</html>
