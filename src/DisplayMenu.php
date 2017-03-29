<?php namespace Waynestate\Menu;

use InvalidArgumentException;

class DisplayMenu {
    /**
     * Render the HTML output
     *
     * @param array $params
     * @return string
     */
    public function render($params) {
        // Default output
        $output = '';

        // Require the $menu param set
        if (!isset($params['menu']) || !is_array($params['menu'])) {
            throw new InvalidArgumentException("assign: missing 'menu' parameter");
        }

        // Return the visual menu
        $output = $this->item($params['menu']);

        // If there are menu items, wrap it in an unsorted list
        return ( strlen($output) > 0 )? '<ul' . (isset($params['menu_class']) && $params['menu_class'] != '' ? ' class="' . $params['menu_class'] . '"' : '') . '>' . $output . '</ul>': $output;
    }

    /**
     * Render a menu item
     *
     * @param  array  $menu
     * @return string
     */
    private function item(array $menu) {
        $return = '';

        foreach ($menu as $item) {
            // Skip this item if it is not active
            if ( ! filter_var($item['is_active'], FILTER_VALIDATE_BOOLEAN) )
                continue;

            // Build the classes list
            $classes = ( filter_var($item['is_selected'], FILTER_VALIDATE_BOOLEAN) )? 'selected ' : '';
            $classes .= (string) $item['class_name'];

            // Build the target if not _self
            $target = ( $item['target'] != '_self' )? $item['target'] : '';

            // Build the menu item string
            $return .= '<li' .
                ( ( $classes != '' )? ' class="' .  trim($classes) . '"' : '') .
                '>';
            $return .= '<a href="' . $item['relative_url'] . '"' .
                ( ( $target != '' )? ' target="' .  trim($target) . '"' : '') .
                '>';
            $return .= $item['display_name'];
            $return .= '</a>';

            // If there are sub menu items
            if ( count( $item['submenu'] ) > 0 ) {
                // Recurse in to build the sub menu string
                $submenu = $this->item($item['submenu']);

                // If there are sub menu items, add them in to the string
                $return .= ( strlen($submenu) > 0 )? '<ul>' . $submenu . '</ul>' : $submenu;
            }

            // End the menu item
            $return .= '</li>' . "\n";
        }

        return $return;
    }
}
