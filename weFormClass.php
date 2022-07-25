<?php
if (! class_exists("WP_List_Table")) {
    require_once(ABSPATH . "wp-admin/includes/class-wp-list-table.php");
}

class We_Form_Table extends WP_List_Table
{
    private $_items;

    public function __construct()
    {
        parent::__construct([
            'singular' => 'contact',
            'plural'   => 'contacts',
            'ajax'     => false
        ]);
    }

    public function set_data($data)
    {
        $this->_items = $data;
    }

    public function get_columns()
    {
        return [
            'cb'    => '<input type="checkbox">',
            'name'  => __('Name', 'we_form'),
            'sex' =>__('Gender', 'we_form'),
            'email' => __('E-mail', 'we_form'),
            'age'   => __('Age', 'we_form'),
            'created_at'   => __('Created At', 'we_form'),
        ];
    }

    public function get_sortable_columns()
    {
        return [
            'age'  => [ 'age', true ],
            'name' => [ 'name', true ],
            'created_at' => [ 'created_at', true ],
        ];
    }


    public function column_cb($item)
    {
        // return "<input type='checkbox' value='{$item['id']}'/>";
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/
            $this->_args['singular'],  //Let's simply repurpose the table's singular label ("plugin")
            /*$2%s*/
            $item['id']                //The value of the checkbox should be the record's id
        );
    }

    public function column_email($item)
    {
        return "<strong>{$item['email']}</strong>";
    }
    public function column_created_at($item)
    {
        $created_at= date('M d, Y H:i a', strtotime($item['created_at']));
        return "<strong>{$created_at}</strong>";
    }

    public function column_age($item)
    {
        return "<em>{$item['age']}</em>";
    }

    public function extra_tablenav($which)
    {
        if ('top'==$which):
            ?>
        <div class="actions alignleft">
            <select name="filter_s" id="filter_s">
                <option value="all">All</option>
                <option value="M">Males</option>
                <option value="F">Females</option>
            </select>
            <?php
                                submit_button(__('Filter', 'we_form'), 'button', 'submit', false); ?>
        </div>
        <?php
                endif;
    }


    public function prepare_items()
    {
        $paged                 = $_REQUEST['paged'] ?? 1;
        $per_page              = 4;
        $total_items           = count($this->_items);
        $this->process_bulk_action();
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
        $data_chunks           = array_chunk($this->_items, $per_page);
        $this->items           = $data_chunks ? $data_chunks[ $paged - 1 ] : [];
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil(count($this->_items) / $per_page)
        ]);
    }

    /**
    * Default column values
    *
    * @param  object $item
    * @param  string $column_name
    *
    * @return string
    */
    protected function column_default($item, $column_name)
    {
        return isset($item->$column_name) ? $item->$column_name : '';
    }

    /**
    * Set the bulk actions
    *
    * @return array
    */
    public function get_bulk_actions()
    {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

     
    public function process_bulk_action()
    {
        global $wpdb;

        if ('delete'===$this->current_action()) {
            foreach ($_GET['contact'] as $id) {
                $result= $wpdb->delete(
                    $wpdb->prefix . 'contact',
                    [ 'id' => $id ],
                    [ '%d' ]
                );
            }
            if ($result) {
                $redirected_to = admin_url('admin.php?page=we-form&contact-deleted=true');
            } else {
                $redirected_to = admin_url('admin.php?page=we-form&contact-deleted=false');
            }
            wp_redirect($redirected_to);
            exit;
        }
    }

    /**
     * Render the "name" column
     *
     * @param  object $item
     *
     * @return string
     */
    public function column_name($item)
    {
        $actions = [];
 
        $actions['delete'] = sprintf('<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url(admin_url('admin-post.php?action=wc-delete-contact&id=' . $item['id']), 'wc-delete-contact'), $item['id'], __('Delete', 'we-form'), __('Delete', 'we-form'));
        return sprintf(
            '<p ><strong>%1$s</strong></p> %2$s',
            $item['name'],
            $this->row_actions($actions)
        );
    }
}
