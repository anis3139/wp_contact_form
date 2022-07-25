<?php
if (! class_exists("WP_List_Table")) {
    require_once(ABSPATH . "wp-admin/includes/class-wp-list-table.php");
}

class We_Form_Table extends WP_List_Table
{
    private $_items;


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
        return "<input type='checkbox' value='{$item['id']}'/>";
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
        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
        $data_chunks           = array_chunk($this->_items, $per_page);
        $this->items           = $data_chunks ? $data_chunks[ $paged - 1 ] : [];
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil(count($this->_items) / $per_page)
        ]);
    }

    public function column_default($item, $column_name)
    {
        return $item[ $column_name ];
    }
}
