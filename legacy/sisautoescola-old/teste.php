<html>
    <body>
        <?php

        $hora = "12:20";

        $duracao = "60";

        echo $hora ."<br>";
        echo $duracao ."<br>";
        echo strftime("%H:%M", strtotime($hora." + 50 minutes"));

        ?>
    </body>
</html>