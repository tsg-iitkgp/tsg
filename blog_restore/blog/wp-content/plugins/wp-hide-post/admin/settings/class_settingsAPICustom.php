<?php

if (!class_exists('wphp_settingsAPICustom'))
{
    class wphp_settingsAPICustom extends wphp_settingsAPI
    {
        public function admin_enqueue_scripts()
        {
            parent::admin_enqueue_scripts();

            wp_enqueue_style('scb_settings', plugin_dir_url(__FILE__) . 'assets/chosen.min.css');
            wp_enqueue_script('scb_settings', plugin_dir_url(__FILE__) . 'assets/chosen.jquery.min.js', array('jquery'));

        }

        public function admin_init()
        {
            //register settings sections
            foreach ($this->settings_sections as $section)
            {
                if (!isset($section['id']))
                {
                    continue;
                }
                if (false == get_option($section['id']))
                {
                    add_option($section['id']);
                }

                if (isset($section['desc']) && !empty($section['desc']))
                {
                    $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
                    $callback        = create_function('', 'echo "' . str_replace('"', '\"', $section['desc']) . '";');
                }
                else if (isset($section['callback']))
                {
                    $callback = $section['callback'];
                }
                else
                {
                    $callback = null;
                }

                add_settings_section($section['id'], isset($section['title'])?$section['title']:'', $callback, $section['id']);
            }

            //register settings fields
            foreach ($this->settings_fields as $section => $field)
            {
                if (!is_array($field) || !count($field))
                {
                    continue;
                }

                foreach ($field as $option)
                {
                    if (!isset($option['name']) || !isset($option['label']))
                    {
                        continue;
                    }
                    $type = isset($option['type']) ? $option['type'] : 'text';

                    $args = array(
                        'id'                => $option['name'],
                        'label_for'         => $args['label_for'] = "{$section}[{$option['name']}]",
                        'desc'              => isset($option['desc']) ? $option['desc'] : '',
                        'name'              => $option['label'],
                        'section'           => $section,
                        'size'              => isset($option['size']) ? $option['size'] : null,
                        'options'           => isset($option['options']) ? $option['options'] : '',
                        'std'               => isset($option['default']) ? $option['default'] : '',
                        'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                        'type'              => $type,

                    );
                    $args     = array_merge($option, $args);
                    $callable = isset($option['callback']) && (is_callable($option['callback']) || is_array($option['callback'])) ? $option['callback'] : array($this, 'callback_' . $type);
                    add_settings_field($section . '[' . $option['name'] . ']', $option['label'], $callable, $section, $section, $args);
                }
            }

            // creates our settings in the options table
            $th = $this;
            foreach ($this->settings_sections as $section)
            {
                register_setting($section['id'], $section['id'],
                    function ($options) use ($th, $section)
                    {
                        return $th->sanitize_options($section, $options);
                    });
            }
        }
        public function callback_yesno($args)
        {

            $args['options'] = array('1' => 'Yes', '0' => 'No');
            $value           = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
            $size            = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
            $html            = sprintf('<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]"  %4$s>', $size, $args['section'], $args['id'],isset($args['disabled'])?$args['disabled']:'');

            foreach ($args['options'] as $key => $label)
            {
                $html .= sprintf('<option value="%s"%s>%s</option>', $key, selected($value, $key, false), $label);
            }

            $html .= sprintf('</select>');
            $html .= $this->get_field_description($args);

            echo $html;
        }
        /**
         * Displays a selectbox for a settings field
         *
         * @param array   $args settings field args
         */
        public function callback_select($args)
        {

            $value = ($this->get_option($args['id'], $args['section'], $args['std']));
            if (!is_array($value))
            {
                $value = esc_attr($value);
            }
            $value = is_array($value) ? $value : array($value);
            $size  = isset($args['multiple']) ? '' : (isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular');
            if (isset($args['class']))
            {
                $size .= ' ' . $args['class'];
            }
            $html = sprintf('<select  class="%1$s" name="%2$s[%3$s]%6$s" id="%2$s[%3$s]"  %4$s %5$s>', $size, $args['section'], $args['id'], isset($args['multiple']) ? 'multiple' : '', empty($args['placeholder']) ? '' : 'data-placeholder="' . $args['placeholder'] . '"', isset($args['multiple']) ? '[]' : '');

            foreach ($args['options'] as $key => $label)
            {
                $extra = '';
                if (is_array($label))
                {
                    $extra = isset($label['extra'])?$label['extra']:'';
                    $label = @$label['text'];
                }
                $html .= sprintf('<option %s value="%s"%s>%s</option>', $extra, $key, in_array($key, $value) ? 'selected' : '', $label);
            }

            $html .= sprintf('</select>');
            $html .= $this->get_field_description($args);

            echo $html;
        }
        /**
         * Displays a selectbox for a settings field
         *
         * @param array   $args settings field args
         */
        public function callback_multi($args)
        {
            $args['multiple'] = 1;
            $args['class']    = 'scb-sett-select-chosen chosen-select';

            echo ($this->callback_select($args));
        }
        public function get_sanitize_callback($slug = '')
        {
            if (empty($slug))
            {
                return false;
            }

            // Iterate over registered fields and see if we can find proper callback

            foreach ($this->settings_fields as $section => $options)
            {
                foreach ($options as $option)
                {
                    if ($option['name'] != $slug)
                    {
                        continue;
                    }

                    // Return the callback name
                    return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) ? $option['sanitize_callback'] : (is_callable(array($this, 'sanitize_callback_' . $option['type'])) ? array($this, 'sanitize_callback_' . $option['type']) : false);
                }
            }

            return false;
        }

        public function sanitize_callback_yesno($option_value)
        {
            return (int) (boolean) $option_value;
        }

        public function sanitize_options($section, $options)
        {
            // print_n($options);
            $options = apply_filters('scb_pre_sanitize_section', $options, $section);

            foreach ($options as $option_slug => $option_value)
            {
                $sanitize_callback = $this->get_sanitize_callback($option_slug);
                // If callback is set, call it
                if ($sanitize_callback)
                {
                    // print_n($option_slug);

                    $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                    continue;
                }
            }

            return apply_filters('scb_post_sanitize_section', $options, $section);
        }
        public function show_navigation()
        {
            $html='';
            if(wphp_settings::instance()->options('page_title'))
            {
                $html="<h1>".wphp_settings::instance()->options('page_title')."</h1>";
            }
            $html .= '<h2 class="nav-tab-wrapper scb-set-nav">';

            foreach ($this->settings_sections as $tab)
            {

                $html .= sprintf('<a href="#%1$s" class="nav-tab" id="%1$s-tab" data-tab-id="%1$s">%2$s</a>', $tab['id'], (!empty($tab['label']) ? $tab['label'] : $tab['title']));
            }

            $html .= '</h2>';

            echo $html;
        }
        /**
         * Show the section settings forms
         *
         * This function displays every sections in a different form
         */
        public function show_forms($page = "options.php", $options = array())
        {
            ?>
        <div class="metabox-holder">
        <?php
        foreach ($this->settings_sections as $form)
            {
                $button['type'] = isset($form['options']['button']['type']) ? $form['options']['button']['type'] : 'primary';
                $button['text'] = isset($form['options']['button']['text']) ? $form['options']['button']['text'] : null;
                $button['name'] = isset($form['options']['button']['name']) ? $form['options']['button']['name'] : null;
                if (isset($form['options']['form']['page']))
                {
                    $page = $form['options']['form']['page'];
                }
                elseif (isset($form['options']['form']['handler']) && is_callable($form['options']['form']['handler']))
                {
                    $page = 'options-general.php?page=' . $options['setting_page_name'];
                }

                ?>
                <div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
                    <form method="post" action="<?php echo ($page); ?>">
                        <?php
do_action('wsa_form_top_' . $form['id'], $form);
                settings_fields($form['id']);

                do_settings_sections($form['id']);

                do_action('wsa_form_bottom_' . $form['id'], $form);
                if (!(isset($form['options']['no_submit']) && $form['options']['no_submit']))
                {
                    ?>
                        <div style="padding-left: 10px" class="scb_tab_submit_div">
                            <?php submit_button($button['text'], $button['type'], $button['name']);?>
                        </div>
                        <?php }?>
                        <?php do_action('wsa_form_last_' . $form['id'], $form);?>
                    </form>
                </div>
            <?php }?>
            <?php do_action('wsa_global_footer', $form);?>
        </div>
        <?php
$this->script();
        }
        public function get_field_description($args)
        {
            if (!empty($args['desc']))
            {

                $desc = '<p class="description">' . $args['desc'] . '</p>';
            }
            else
            {
                $desc = '';
            }

            return $desc;
        }

    }

}
