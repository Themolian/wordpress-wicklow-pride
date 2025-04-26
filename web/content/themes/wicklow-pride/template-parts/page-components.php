<?php
if(have_rows('page_content')) {
    while(have_rows('page_content')) {
        the_row();
        get_template_part('template-parts/component/' . get_row_layout());
    }
}