<?php

$distFiles = include 'config.php';

if (!empty($_POST['implementFile'])) {
    foreach ($_POST['implementFile'] as $filePath => $content) {
        $implementedFilePath = str_replace('.dist', '', $filePath);

        file_put_contents($implementedFilePath, $content) ? 't': 'f';
    }

    header('location: ' . str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']));
    exit;
}

if (!empty($_GET['deleteFilePath'])) {
    $implementedFilePath = str_replace('.dist', '', urldecode($_GET['deleteFilePath']));

    if (is_file($implementedFilePath)) {
        unlink($implementedFilePath);
    }

    header('location: ' . str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']));
    exit;
}

if (isset($_POST['uninstall'])) {
    $files = glob('*');
    foreach ($files as $filePath) {
        unlink($filePath);
    }

    rmdir(dirname(__FILE__));

    echo 'You succesfully removed the *-dist implementation script with all it\'s files.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Implementing *.dist files</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/foundation.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.min.css" rel="stylesheet">
    <style>
        textarea {
            margin: .5em 0;
            resize: none;
        }
        code {
            display: block;
            height: 100%;
        }
        pre {
            padding: 0 !important;
        }
        .pair-wrap {
            margin-bottom: 30px !important;
        }
        .pair-wrap pre, .pair-wrap textarea {
            height: 100%;
        }
        legend {
            background: none !important;
        }
        .no-margin {
            margin: 0;
        }
        .button.fluid {
            width: 100%;
        }

        .label {
            vertical-align: middle;
        }

        .implementation-mode_edit pre {
            display: none;
        }

        .implementation-mode_show textarea {
            display: none;
        }

        .change-btn {
            display: none;
        }
    </style>
</head>
<body>

    <div class="row">
        <div class="column small-12">
            <br><br>
            <h3 class="text-center">Implementing *.dist files</h3>
            <br>
            <div class="panel">
                <p>
                    Here you can implement the *.dist files - listed in the "config.php" file - . Every dist file will
                    be implemented with the same name, only without the ".dist" suffix. So for example in case of a
                    Symfony2 "parameters.yml.dist" file, the new file will be called "parameters.yml".
                </p>
                <p>
                    <b>Important!</b> <br>
                    This script should only be used after the very first deplyoment, to create server-specific
                    configurations based on the *dist template files.<br>
                    After you are done implementing the dist files, remove the folder containing this script altogether.
                </p>
            </div>
            <?php foreach ($distFiles as $filePath): ?>
                <?php
                    if (!file_exists($filePath)) { continue; }
                    $implementationMissing = !is_file(str_replace('.dist', '', $filePath));
                ?>
                <form action="" method="post">
                    <fieldset class="panel">
                        <legend>
                            <h4>
                            <?php echo $filePath ?>
                            <?php if ($implementationMissing): ?>
                                - <span class="label alert">missing</span>
                            <?php else: ?>
                                - <span class="label success">implemented</span>
                            <?php endif; ?>
                            </h4>
                        </legend>
                        <div class="row">
                            <div class="column small-6">
                                <h5 class="text-center"><?php echo basename($filePath) ?></h5>
                            </div>
                            <div class="column small-6">
                                <h5 class="text-center">
                                    <?php echo basename($filePath, '.dist') ?>
                                    <?php if (!$implementationMissing): ?>
                                        - <a class="label switch-on-edit-mode">Edit</a>
                                    <?php endif ?>
                                </h5>
                            </div>
                        </div>
                        <div class="row pair-wrap" data-equalizer>
                            <div class="column small-6" data-equalizer-watch>
                                <pre class="language-markup"><code><?php echo htmlspecialchars(file_get_contents($filePath)) ?></code></pre>
                            </div>
                            <div class="column small-6 implementation-mode implementation-mode_<?php echo $implementationMissing ? 'edit': 'show' ?>" data-equalizer-watch>
                                <textarea name="implementFile[<?php echo $filePath ?>]" rows="15"><?php
                                    if ($implementationMissing) {
                                        echo file_get_contents($filePath);
                                    } else {
                                        echo file_get_contents(str_replace('.dist', '', $filePath));
                                    }
                                ?></textarea>
                                <?php if (!$implementationMissing): ?>
                                    <pre class="language-markup"><code><?php echo htmlspecialchars(file_get_contents(str_replace('.dist', '', $filePath))) ?></code></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="column small-3 hide-for-small"><br></div>
                            <div class="column small-6 text-center">
                                <?php if ($implementationMissing): ?>
                                    <button type="submit" class="button radius no-margin">Implement file</button>
                                <?php else: ?>
                                    <button type="submit" class="change-btn button radius no-margin">Change file</button>
                                <?php endif; ?>
                            </div>
                            <div class="column small-6 medium-3">
                                <?php if (!$implementationMissing): ?>
                                    <a href="?deleteFilePath=<?php echo urlencode($filePath) ?>" class="delete-implementation button alert tiny right">Delete implemented file</a>
                                <?php endif ?>
                            </div>
                        </div>
                    </fieldset>
                </form>
            <?php endforeach ?>

        </div>
    </div>

    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/0.0.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/js/foundation.min.js"></script>
    <script> $(document).foundation(); </script>
    <script>
        $('.switch-on-edit-mode').on('click', function(){
            var $panel = $(this).closest('.panel');
            var $label = $panel.find('.switch-on-edit-mode');
            var $implementationMode = $panel.find('.implementation-mode');
            var $changeBtn = $panel.find('.change-btn');

            var editMode = $panel.data('editMode');

            if (!editMode) {
                $label.text('x');
                $changeBtn.show();
                $implementationMode.removeClass('implementation-mode_show');
                $implementationMode.addClass('implementation-mode_edit');
            } else {
                $label.text('Edit');
                $changeBtn.hide();
                $implementationMode.removeClass('implementation-mode_edit');
                $implementationMode.addClass('implementation-mode_show');
            }

            $panel.data('editMode', !editMode);
        });

        $('.delete-implementation').on('click', function(e){
            if (!confirm('Are you sure you want to delete this implementation?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
