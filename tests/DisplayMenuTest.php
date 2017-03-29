<?php

use Waynestate\Menu\DisplayMenu;

/**
 * Class DisplayMenuTest
 */
class DisplayMenuTest extends PHPUnit_Framework_TestCase {
    /**
     * @var
     */
    protected $menu;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->displayMenu = new DisplayMenu;

        // Stub
        $this->menu = [
            [
                'menu_item_id' => 1,
                'menu_id' => 1,
                'page_id' => 1,
                'display_name' => 'First',
                'class_name' => '',
                'is_selected' => false,
                'is_active' => false,
                'target' => '_self',
                'relative_url' => '/first',
                'submenu' => [
                    [
                        'menu_item_id' => 3,
                        'menu_id' => 1,
                        'page_id' => 3,
                        'display_name' => 'Three',
                        'class_name' => '',
                        'is_selected' => false,
                        'is_active' => false,
                        'target' => '_self',
                        'relative_url' => '/third',
                        'submenu' => [],
                    ],
                ],
            ],
            [
                'menu_item_id' => 2,
                'menu_id' => 1,
                'page_id' => 3,
                'display_name' => 'Second',
                'class_name' => '',
                'is_selected' => false,
                'is_active' => false,
                'target' => '_self',
                'relative_url' => '/second',
                'submenu' => [],
            ],
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function check_if_no_menu_is_passed_in()
    {
        // No menu passed
        $params = [];

        // Try to display the menu
        $output = $this->displayMenu->render($params);
    }

    /**
     * @test
     */
    public function check_if_no_output_with_empty_menu()
    {
        // Base menu, nothing enabled
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert there is no string to output
        $this->assertEquals('', $output);
    }

    /**
     * @test
     */
    public function check_single_menu_item()
    {
        // Make one item active
        $this->menu[0]['is_active'] = true;

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/first">First</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function check_multiple_menu_items()
    {
        // Make one item active
        $this->menu[0]['is_active'] = true;
        $this->menu[1]['is_active'] = true;

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/first">First</a></li><li><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function check_nested_menu_items()
    {
        // Make one item active
        $this->menu[0]['is_active'] = true;
        $this->menu[0]['submenu'][0]['is_active'] = true;

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/first">First</a><ul><li><a href="/third">Three</a></li></ul></li></ul>', $output);
    }

    /**
     * @test
     */
    public function handle_active_string_instead_of_boolean()
    {
        // Make one item active
        $this->menu[1]['is_active'] = 'true';

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function handle_selected_string_instead_of_boolean()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;
        $this->menu[1]['is_selected'] = 'false';

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function check_item_class_with_selected_state()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;
        $this->menu[1]['is_selected'] = true;
        $this->menu[1]['class_name'] = 'foo';

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li class="selected foo"><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function check_item_class_without_selected_state()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;
        $this->menu[1]['class_name'] = 'foo';

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li class="foo"><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function should_not_include_blank_target_attribute()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/second">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function should_include_target_attribute()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;
        $this->menu[1]['target'] = '_blank';

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul><li><a href="/second" target="_blank">Second</a></li></ul>', $output);
    }

    /**
     * @test
     */
    public function should_include_menu_class()
    {
        // Make one item active
        $this->menu[1]['is_active'] = true;

        // Set the params for the Smarty function
        $params = [
            'menu' => $this->menu,
            'menu_class' => 'test-menu'
        ];

        // Display the menu
        $output = $this->displayMenu->render($params);
        $output = str_replace("\n", '', trim($output)); // Remove line breaks

        // Assert the menu display
        $this->assertEquals('<ul class="test-menu"><li><a href="/second">Second</a></li></ul>', $output);
    }
}
