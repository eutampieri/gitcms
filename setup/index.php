<?php
require_once("../tools/tools.php");

if(is_dir("../posts")){
    die();
}

//Load the configuration from conf.json
$conf = json_decode(file_get_contents("../conf.json"), true);
$locale = json_decode(file_get_contents("../locales/".$conf["locale"].".json"), true);
?>
<!DOCTYPE html>
<html lang="<?php echo $conf["lang"];?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php ___("gitcms-setup");?></title>
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
    <link rel="stylesheet" href="../style.css">
    <style>
        h1{
            text-align: center;
            margin-bottom: .2em;
        }
    </style>
    <!--link rel="stylesheet" href="theme.css"-->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/.">gitCMS</a>
    </nav>
    <div class="container">
        <h1 class="display-4"><?php ___("gitcms-setup");?></h1>
        <div class="alert alert-danger<?php echo is_writable(dirname( dirname(__FILE__) ))?" d-none":"";?>" role="alert"><?php echo ___("root-dir-perms-err");?></div>
        <div class="alert alert-danger<?php echo exec("which git")!=""?" d-none":"";?>" role="alert"><?php echo ___("no-git-err");?></div>
        <div class="alert alert-warning<?php echo exec("which ssh")!=""?" d-none":"";?>" role="alert"><?php echo ___("no-ssh-err");?></div>

        <form method="POST" action="setup.php">
            <h3><?php ___("localisation");?></h3>
            <div class="form-group">
                <label for="language"><?php ___("language");?></label>
                <select class="form-control" id="language" name="locale">
                    <?php
                    foreach(glob("../locales/*.json") as $l){
                        $locale_path = explode("/", $l);
                        echo "<option value=\"".explode(".", $locale_path[count($locale_path) - 1])[0]."\">".json_decode(file_get_contents($l), true)["__friendly_name"]."</option>\n";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="datetime"><?php ___("datetime");?></label>
                <input name="datetime_format" type="text" class="form-control" id="datetime" value="%d/%m/%Y %H:%M">
                <button class="mt-2 btn btn-secondary" type="button" data-toggle="collapse" data-target="#dropdownDateTimeFmts" aria-controls="dropdownDateTimeFmts" aria-expanded="false">
                    <?php ___("formats-cheatsheet");?>
                </button>
                <div class="collapse" id="dropdownDateTimeFmts">
                    <small class="form-text text-muted">
                        <table class="doctable table"><thead> <tr> <th><code class="parameter">format</code></th> <th>Description</th> <th>Example returned values</th> </tr> </thead> <tbody class="tbody"> <tr> <td style="text-align: center;"><em class="emphasis">Day</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%a</em></td> <td>An abbreviated textual representation of the day</td> <td>
                        <em>Sun</em> through <em>Sat</em>
                        </td> </tr> <tr> <td><em>%A</em></td> <td>A full textual representation of the day</td> <td>
                        <em>Sunday</em> through <em>Saturday</em>
                        </td> </tr> <tr> <td><em>%d</em></td> <td>Two-digit day of the month (with leading zeros)</td> <td>
                        <em>01</em> to <em>31</em>
                        </td> </tr> <tr> <td><em>%e</em></td> <td> Day of the month, with a space preceding single digits. Not implemented as described on Windows. See below for more information. </td> <td>
                        <em> 1</em> to <em>31</em>
                        </td> </tr> <tr> <td><em>%j</em></td> <td>Day of the year, 3 digits with leading zeros</td> <td>
                        <em>001</em> to <em>366</em>
                        </td> </tr> <tr> <td><em>%u</em></td> <td>ISO-8601 numeric representation of the day of the week</td> <td>
                        <em>1</em> (for Monday) through <em>7</em> (for Sunday)</td> </tr> <tr> <td><em>%w</em></td> <td>Numeric representation of the day of the week</td> <td>
                        <em>0</em> (for Sunday) through <em>6</em> (for Saturday)</td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Week</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%U</em></td> <td>Week number of the given year, starting with the first Sunday as the first week</td> <td>
                        <em>13</em> (for the 13th full week of the year)</td> </tr> <tr> <td><em>%V</em></td> <td>ISO-8601:1988 week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week</td> <td>
                        <em>01</em> through <em>53</em> (where 53 accounts for an overlapping week)</td> </tr> <tr> <td><em>%W</em></td> <td>A numeric representation of the week of the year, starting with the first Monday as the first week</td> <td>
                        <em>46</em> (for the 46th week of the year beginning with a Monday)</td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Month</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%b</em></td> <td>Abbreviated month name, based on the locale</td> <td>
                        <em>Jan</em> through <em>Dec</em>
                        </td> </tr> <tr> <td><em>%B</em></td> <td>Full month name, based on the locale</td> <td>
                        <em>January</em> through <em>December</em>
                        </td> </tr> <tr> <td><em>%h</em></td> <td>Abbreviated month name, based on the locale (an alias of %b)</td> <td>
                        <em>Jan</em> through <em>Dec</em>
                        </td> </tr> <tr> <td><em>%m</em></td> <td>Two digit representation of the month</td> <td>
                        <em>01</em> (for January) through <em>12</em> (for December)</td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Year</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%C</em></td> <td>Two digit representation of the century (year divided by 100, truncated to an integer)</td> <td>
                        <em>19</em> for the 20th Century</td> </tr> <tr> <td><em>%g</em></td> <td>Two digit representation of the year going by ISO-8601:1988 standards (see %V)</td> <td>Example: <em>09</em> for the week of January 6, 2009</td> </tr> <tr> <td><em>%G</em></td> <td>The full four-digit version of %g</td> <td>Example: <em>2008</em> for the week of January 3, 2009</td> </tr> <tr> <td><em>%y</em></td> <td>Two digit representation of the year</td> <td>Example: <em>09</em> for 2009, <em>79</em> for 1979</td> </tr> <tr> <td><em>%Y</em></td> <td>Four digit representation for the year</td> <td>Example: <em>2038</em>
                        </td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Time</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%H</em></td> <td>Two digit representation of the hour in 24-hour format</td> <td>
                        <em>00</em> through <em>23</em>
                        </td> </tr> <tr> <td><em>%k</em></td> <td>Hour in 24-hour format, with a space preceding single digits</td> <td>
                        <em> 0</em> through <em>23</em>
                        </td> </tr> <tr> <td><em>%I</em></td> <td>Two digit representation of the hour in 12-hour format</td> <td>
                        <em>01</em> through <em>12</em>
                        </td> </tr> <tr> <td><em>%l (lower-case 'L')</em></td> <td>Hour in 12-hour format, with a space preceding single digits</td> <td>
                        <em> 1</em> through <em>12</em>
                        </td> </tr> <tr> <td><em>%M</em></td> <td>Two digit representation of the minute</td> <td>
                        <em>00</em> through <em>59</em>
                        </td> </tr> <tr> <td><em>%p</em></td> <td>UPPER-CASE 'AM' or 'PM' based on the given time</td> <td>Example: <em>AM</em> for 00:31, <em>PM</em> for 22:23</td> </tr> <tr> <td><em>%P</em></td> <td>lower-case 'am' or 'pm' based on the given time</td> <td>Example: <em>am</em> for 00:31, <em>pm</em> for 22:23</td> </tr> <tr> <td><em>%r</em></td> <td>Same as "%I:%M:%S %p"</td> <td>Example: <em>09:34:17 PM</em> for 21:34:17</td> </tr> <tr> <td><em>%R</em></td> <td>Same as "%H:%M"</td> <td>Example: <em>00:35</em> for 12:35 AM, <em>16:44</em> for 4:44 PM</td> </tr> <tr> <td><em>%S</em></td> <td>Two digit representation of the second</td> <td>
                        <em>00</em> through <em>59</em>
                        </td> </tr> <tr> <td><em>%T</em></td> <td>Same as "%H:%M:%S"</td> <td>Example: <em>21:34:17</em> for 09:34:17 PM</td> </tr> <tr> <td><em>%X</em></td> <td>Preferred time representation based on locale, without the date</td> <td>Example: <em>03:59:16</em> or <em>15:59:16</em>
                        </td> </tr> <tr> <td><em>%z</em></td> <td>The time zone offset. Not implemented as described on Windows. See below for more information.</td> <td>Example: <em>-0500</em> for US Eastern Time</td> </tr> <tr> <td><em>%Z</em></td> <td>The time zone abbreviation. Not implemented as described on Windows. See below for more information.</td> <td>Example: <em>EST</em> for Eastern Time</td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Time and Date Stamps</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%c</em></td> <td>Preferred date and time stamp based on locale</td> <td>Example: <em>Tue Feb 5 00:45:10 2009</em> for February 5, 2009 at 12:45:10 AM</td> </tr> <tr> <td><em>%D</em></td> <td>Same as "%m/%d/%y"</td> <td>Example: <em>02/05/09</em> for February 5, 2009</td> </tr> <tr> <td><em>%F</em></td> <td>Same as "%Y-%m-%d" (commonly used in database datestamps)</td> <td>Example: <em>2009-02-05</em> for February 5, 2009</td> </tr> <tr> <td><em>%s</em></td> <td>Unix Epoch Time timestamp (same as the <span class="function"><a href="function.time" class="function">time()</a></span> function)</td> <td>Example: <em>305815200</em> for September 10, 1979 08:40:00 AM</td> </tr> <tr> <td><em>%x</em></td> <td>Preferred date representation based on locale, without the time</td> <td>Example: <em>02/05/09</em> for February 5, 2009</td> </tr> <tr> <td style="text-align: center;"><em class="emphasis">Miscellaneous</em></td> <td>---</td> <td>---</td> </tr> <tr> <td><em>%n</em></td> <td>A newline character ("\n")</td> <td>---</td> </tr> <tr> <td><em>%t</em></td> <td>A Tab character ("\t")</td> <td>---</td> </tr> <tr> <td><em>%%</em></td> <td>A literal percentage character ("%")</td> <td>---</td> </tr> </tbody> </table>
                    </small>
                </div>
            </div>
            
            <h3><?php ___("customization");?></h3>
            <div class="form-group">
                <label for="blog-title"><?php ___("blog-title");?></label>
                <input type="text" class="form-control" id="blog-title" name="blog_title" placeholder="<?php ___("myblog");?>">
            </div>
            <div class="form-group">
                <label for="post-preview-number"><?php ___("post-preview-number");?></label>
                <input type="number" class="form-control" id="post-preview-number" name="post_preview_number" value="10">
            </div>
            
            <h3><?php ___("post-storage");?></h3>
            <p><?php ___("post-storage-intro");?></p>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="post_storage" id="local" value="local" checked>
                <label class="form-check-label" for="local"><?php ___("post-storage-local");?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="post_storage" id="remote" value="remote">
                <label class="form-check-label" for="remote"><?php ___("post-storage-remote");?></label>
            <div class="form-group">
                <label for="repo-url"><?php ___("repo-url");?></label>
                <input type="text" class="form-control" id="repo-url" name="repo_url" placeholder="<?php ___("repo-url-placeholder");?>">
            </div></div>
            <div class="mt-2"><p><?php ___("ssh-key-copy-paste");?></p> <textarea class="form-control"><?php echo get_ssh_pubkey();?></textarea></div>
            <h3><?php ___("admin-setup");?></h3>
            <div class="form-group">
                <label for="username"><?php ___("username");?></label>
                <input type="text" class="form-control" id="username" name="username" placeholder="<?php ___("username");?>"">
            </div>
            <div class="form-group">
                <label for="password"><?php ___("password");?></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="<?php ___("password");?>"">
            </div>
            <div class="form-group">
                <label for="name"><?php ___("name");?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?php ___("name");?>"">
            </div>
            <button type="submit" class="btn btn-primary"><?php ___("save");?></button>
        </form>
    </div>
</body>

</html>