<?php 
if(isset($_GET['result']) && $_GET['result'] != 'false') {
	display_success();
}
?>

<?php $cfm = new Countyfunction_modal();?>
<div class="wrap">
	<h2>
		County Administration
	</h2>
	<ul style="display:block;list-style-type:none;">
		<li><a href="<?php echo site_url().'/wp-admin/admin.php?page=county_func_admin_add_county';?>" style="padding:5px 10px; background-color:#b2b2b2; color:white;font-size:12px; text-decoration:none; color:black; clear:both;">Add new</a></li>	
	</ul>
	
	<ul class="subsubsub">
		<li><a href="<?php get_site_url('/wp-admin/admin.php?page=county_func_admin');?>" class="current">All (<?php echo $cfm->count_all_counties();?>)</a></li>
	</ul>
	
	
			
<?php // Disply the Table
$table = $cfm->get_table();

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
 
/**
 * Create a new table class that will extend the WP_List_Table
 */
class County_table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
	protected $tabledata;
	public function __construct($tabledata){
		parent::__construct();
		$this->tabledata = $tabledata;
		
	}
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
 
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
 
        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
 
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
 
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
 
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
 
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'county_name'       => 'Name',
            'state' => 'State',
            'desc'        => 'Description',
            'action'      => 'Action'
        );
 
        return $columns;
    }
 
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
 
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('title' => array('title', false), 'id' => array('id',false), 'state' => array('state',false));
    }
 
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
	 
        $data = array();
	   
 		foreach($this->tabledata as $row) {
			$data[] = array(
				'id' => $row['id'],
				'county_name' => $row['county_name'],
				'state' => $row['state'],
				'desc' => $row['desc'],
			);
		}
       
        return $data;
    }
 
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
           case 'action':
               return '<a href="'.get_site_url().'/wp-admin/admin.php?page=county_func_admin&action=del&id='.$item[ 'id' ] .'">Delete</a>';
 			break;
		   
            default:
               return $item[ $column_name ];
        }
    }
 
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'title';
        $order = 'asc';
 
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
 
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
 
 
        $result = strnatcmp( $a[$orderby], $b[$orderby] );
 
        if($order === 'asc')
        {
            return $result;
        }
 
        return -$result;
    }
}

$ftable = new County_table($table);
$ftable->prepare_items();
$ftable->display();
?>


</div>
