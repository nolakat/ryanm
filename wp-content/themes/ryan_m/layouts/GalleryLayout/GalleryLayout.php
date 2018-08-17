
<div id="gallery-grid">

    <?php while (have_rows('newflex')) { 
        the_row('newflex');

        $layout = get_row_layout();
        $layout = explode("--", $layout);
        
        if (count($layout) > 1) {
            print get_template_component($layout[0], $layout[1]);
        } else {
            print get_template_component($layout[0]);
        }
    } ?>


    </div>

