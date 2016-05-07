<?php
    require_once 'functions.php';

    set_null('template/formbuild.php');
    set_null('template/form_var_build.php');
    set_null('template/update_var_build.php');
    set_null('template/temp.php');
    set_null('template/widget_file.php');

    if ( isset( $_POST['submit'] ) ) {

         $widget_codes = file_get_contents('template.php');

         $count = count($_POST['table_key'] > 0) ? count($_POST['table_key']) : 1; 


        for($i=0; $i<$count; $i++){

            $file             = "template/formbuild.php";
            $file_var         = 'template/form_var_build.php';
            $file_update_var  = 'template/update_var_build.php';

            $id     = "%".$_POST['table_key'][$i]."%";
            $name   = "%".$_POST['table_value'][$i]."%";
            $value  = "%_".$_POST['table_value'][$i]."%";

            $var  = '$'.$_POST['table_value'][$i].' = !empty($instance["'.$_POST['table_value'][$i].'"]) ? $instance["'.$_POST["table_value"][$i].'"] : "";';
            $var .= "\n";

            file_put_contents($file_var, $var,FILE_APPEND);

            $update_var  = '$instance["'.$_POST['table_value'][$i].'"] = ( !empty($new_instance["'.$_POST['table_value'][$i].'"])) ? strip_tags($new_instance["'.$_POST["table_value"][$i].'"]) : "";';
            $update_var .= "\n";

            file_put_contents($file_update_var, $update_var,FILE_APPEND);

            $data = build_form($id,$_POST['table_key'][$i],$name,$value);
            file_put_contents($file, $data,FILE_APPEND | LOCK_EX);

            $id_string    = '<?php echo $this->get_field_id("'.$_POST['table_value'][$i].'") ?>';
            $name_string  = '<?php echo $this->get_field_name("'.$_POST['table_value'][$i].'") ?>';
            $value_string = '<?php echo $'.$_POST['table_value'][$i].' ?>';
            
            replace_data($file,$id,$id_string);
            replace_data($file,$name,$name_string);
            replace_data($file,$value,$value_string);

        }

    $temp_file   = 'template/temp.php';
    $widget_file = 'template/widget_file.php';

    $var_data = file_get_contents('template/form_var_build.php');
    file_put_contents($temp_file, $var_data,FILE_APPEND);

    file_put_contents($widget_file, $var_data,FILE_APPEND);

    $var_data = "\n?>\n";
    file_put_contents($temp_file, $var_data,FILE_APPEND);

    $form_data = file_get_contents('template/formbuild.php');
    file_put_contents($temp_file,$form_data,FILE_APPEND);

    $var_data = "\n<?php \n";
    file_put_contents($temp_file, $var_data,FILE_APPEND);

    $all_form_data = file_get_contents($temp_file);
    $widget_codes  = str_replace('%form_contents%', $all_form_data, $widget_codes);

    $all_update_data = file_get_contents("template/update_var_build.php");
    $widget_codes    = str_replace("%update_contents%", $all_update_data, $widget_codes);

    $all_widget_data = file_get_contents("template/widget_file.php");
    $widget_codes    = str_replace("%widget_contents%", $all_widget_data, $widget_codes);

    $search_array = array(
        '%widget_class%',
        '%widget_id%',
        '%widget_title%',
        '%description%',
        '%textdomain%',
    );

    $replace_array = array(
        $_POST['widget_class'],
        $_POST['widget_id'],
        $_POST['widget_title'],
        trim($_POST['description']),
        $_POST['textdomain'],
    );

    $widget_codes = str_replace($search_array, $replace_array, $widget_codes);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Widget Generator</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Widget Generator</a>
            </div>
        </div>
        <!-- /.container-fluid -->
    </nav>

    <div style="margin-bottom: 100px;"></div>


<div class="container">
    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <div class="page-header">
                <h1>Generate A Widget</h1>
            </div>

            <?php if ( isset( $_POST['submit'] ) ):?>
            <p><strong><?php printf( '%s.php', $_POST['widget_id'] ); ?></strong></p>
            <pre class="prettyprint" style="overflow-y: scroll;width:100%;"><?php echo htmlentities( $widget_codes ); ?></pre>
            <?php endif ?>

            <form class="form-horizontal" method="post">
                <fieldset>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="class">Widget Class Name</label>
                        <div class="col-md-5">
                            <input id="class" name="widget_class" type="text" placeholder="Class name" class="form-control input-md" required value="<?php echo isset( $_POST['widget_class' ] ) ? $_POST['widget_class'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="widget_id">Widget Id</label>
                        <div class="col-md-5">
                            <input id="widget_id" name="widget_id" type="text" placeholder="Your widget Id" class="form-control input-md" required value="<?php echo isset( $_POST['widget_id' ] ) ? $_POST['widget_id'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="title">Widget Title</label>
                        <div class="col-md-5">
                            <input id="title" name="widget_title" type="text" placeholder="Widget title" class="form-control input-md" required value="<?php echo isset( $_POST['widget_title' ] ) ? $_POST['widget_title'] : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="description">Widget Description</label>
                        <div class="col-md-5">
                            <textarea name="description" id="values" cols="20" rows="7" class="form-control input-md" placeholder="Your widget Description">
                                <?php echo isset( $_POST['description' ] ) ? $_POST['description'] : ''; ?>
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="textdomain">Textdomain</label>
                        <div class="col-md-5">
                            <input id="textdomain" name="textdomain" type="text" placeholder="Your Text domain" class="form-control input-md"  value="<?php echo isset( $_POST['textdomain' ] ) ? $_POST['textdomain'] : ''; ?>">
                        </div>
                    </div>

                    <h3>From Field</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Label</th>
                                <th>Name</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input id="textdomain" name="table_key[]" type="text" placeholder="Form Field Label" class="form-control input-md" required>
                                </td>
                                <td>
                                    <input id="textdomain" name="table_value[]" type="text" placeholder="Form Field Name" class="form-control input-md" required>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-success add-row">+</a>
                                    <a href="#" class="btn btn-sm btn-danger remove-row">-</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <label class="col-md-4 control-label" for="submit"></label>
                        <div class="col-md-4">
                            <button id="submit" name="submit" class="btn btn-primary">Generate Widget</button>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <div class="row">
            <p>Developed By Nesar Ahammed Jony And Insiperd By <a href="http://tareq.wedevs.com">Tareq Hasan</a></p>
        </div>
    </div>
</footer>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js">\x3C/script>')</script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
<script>$.fn.modal || document.write('<script src="assets/js/bootstrap.min.js">\x3C/script>')</script>

<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="assets/js/script.js" type="text/javascript" charset="utf-8"></script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-4803329-14', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
