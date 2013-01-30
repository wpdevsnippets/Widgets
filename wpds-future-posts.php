/*
 * WPDS Future Posts Widget
 * 
 * Author: WPDevSnipets.com
 * 
 */

add_action('widgets_init', create_function('', 'return register_widget("WPDS_Future_Posts");'));

class WPDS_Future_Posts extends WP_Widget {

    function WPDS_Future_Posts() {
        $widget_ops = array(
            'classname' => 'WPDS_Future_Posts',
            'description' => 'Displays upcoming or future posts'
        );
        $this->defaults = array(
            'title' => '',
            'post_type' => 'post',
            'show_image' => 0,
            'image_alignment' => '',
            'image_size' => '',
            'posts_num' => '',
            'orderby' => '',
            'order' => '',
            'display_as_link' => false
        );

        $this->WP_Widget('WPDS_Future_Posts', __('WPDS Future Posts', 'wpds'), $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, $this->defaults);

        ?>
        <div class="wpds-widget">
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><br/>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></p>

            <p><label for="<?php echo $this->get_field_id('post_type'); ?>">Post Type:</label><br/>
                <?php $post_types = get_post_types(array('public' => true)); ?>
                <select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
                    <?php
                    foreach ($post_types as $post_type)
                        echo '<option value="' . $post_type . '" ' . selected($post_type, $instance['post_type'], FALSE) . '>' . ucfirst($post_type) . '</option>';
                    ?>
                </select> Limit to 
                <input type="text" id="<?php echo $this->get_field_id('posts_num'); ?>" name="<?php echo $this->get_field_name('posts_num'); ?>" value="<?php echo esc_attr($instance['posts_num']); ?>" size="2" /> posts</p>
            
            <p><label for="<?php echo $this->get_field_id('orderby'); ?>">Order By:</label><br/>
            <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
                <option value="date" <?php selected('date', $instance['orderby']); ?>>Date</option>
                <option value="title" <?php selected('title', $instance['orderby']); ?>>Title</option>
                <option value="ID" <?php selected('ID', $instance['orderby']); ?>>ID</option>
                <option value="rand" <?php selected('rand', $instance['orderby']); ?>> Random</option>
            </select>
            <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                <option value="DESC" <?php selected('DESC', $instance['order']); ?>>Descending</option>
                <option value="ASC" <?php selected('ASC', $instance['order']); ?>>Ascending</option>
            </select></p>
            
            <p><input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked(1, $instance['show_image']); ?>/> <label for="<?php echo $this->get_field_id('show_image'); ?>">Show Image</label><br/>
            <?php $sizes = get_intermediate_image_sizes(); ?>
            <select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
                <?php
                foreach ((array) $sizes as $name)
                    echo '<option value="' . esc_attr($name) . '" ' . selected($name, $instance['image_size'], FALSE) . '>' . ucfirst(esc_html($name)) . '</option>';
                ?>
            </select> 
            <select id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('image_alignment'); ?>">
                <option value="">- None -</option>
                <option value="alignleft" <?php selected('alignleft', $instance['image_alignment']); ?>>Align Left</option>
                <option value="alignright" <?php selected('alignright', $instance['image_alignment']); ?>>Align Right</option>
            </select></p>

            
            <p><label><input id="<?= $this->get_field_id('display_as_link') ?>" type="checkbox" name="<?= $this->get_field_name('display_as_link') ?>" value="yes" <? checked($instance['display_as_link'], 'yes', true) ?> /> Display as link</label></p>
        </div>
        <?php
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    // Display the widget
    function widget($args, $instance) {
        extract($args);
        $instance = wp_parse_args((array) $instance, $this->defaults);

        echo $before_widget;

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        $qry_args = array(
            'post_type' => $instance['post_type'], 
            'showposts' => $instance['posts_num'],
            'post_status' => array('future'), 
            'orderby' => $instance['orderby'], 
            'order' => $instance['order']
        );

        $future_posts = new WP_Query($qry_args);
        
        if ($future_posts->have_posts()) {

            if (!empty($title))
                echo $before_title . $title . $after_title;
            
            echo '<ul class="wpds_future_posts">';
            
            while ($future_posts->have_posts()) {
                $future_posts->the_post();

                echo '<li>';
                if (!empty($instance['show_image'])) {
                    if (has_post_thumbnail() && $instance['display_as_link'] == 'yes')
                        printf('<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), esc_attr($instance['image_alignment']), get_the_post_thumbnail(get_the_ID(), $instance['image_size'], array('class' => 'post_thumbnail')));
                    else if(has_post_thumbnail() )
                        printf('<span class="%s">%s</span>', esc_attr($instance['image_alignment']), get_the_post_thumbnail(get_the_ID(), $instance['image_size'], array('class' => 'post_thumbnail')));
                }

                echo ($instance['display_as_link'] == 'yes') ? '<a href="' . get_permalink() . '">' . get_the_title(): get_the_title();

                if ($instance['display_as_link'] == 'yes')
                    echo '</a>';

                echo '</li>';
            }
            echo '</ul>';
        }
        wp_reset_query();

        echo $after_widget;
    }
}
