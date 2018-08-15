<?php
class ISS_Assignment_Post_Type_Metaboxes
{
    public function init()
    {
        add_action('add_meta_boxes', array($this, 'iss_assignment_add_post_meta_boxes'));
        add_action('save_post', array($this, 'iss_assignment_save_meta_boxes'), 10, 2);
    }
    
    /* Create one or more meta boxes to be displayed on the post editor screen. */
    public function iss_assignment_add_post_meta_boxes()
    {

        add_meta_box(
            'iss_assignment_post_meta_box',      // Unique ID
            'Grading Details',    // Title
            array( $this, 'iss_assignment_render_meta_box'),   // Callback function
            'iss_assignment',         // Admin page (or post type)
            'normal',         // Context
            'high'         // Priority
        );
    }


    /* Display the post meta box. */
    public function iss_assignment_render_meta_box($post)
    {
        $classid = 1;
        $category = 'g1is';
        $duedate = current_time('mysql');
        $possiblepoints = 10;
        $grading = ISS_AssignmentService::LoadByID($post->ID);
        if (null != $grading) {
            $classid = $grading->ClassID;
            $category = $grading->Category;
            $duedate = $grading->DueDate;
            $possiblepoints = $grading->PossiblePoints;
        } else {
            if (isset($_GET['cid'])) {
                $classid = iss_sanitize_input($_GET['cid']);
            }
            if (isset($_GET['cid'])) {
                $category = iss_sanitize_input($_GET['cat']);
            }
        }
        // $meta = get_post_custom($post->ID);
        // $classid = !isset($meta['classid'][0]) ? '' : $meta['classid'][0];
        // $category = !isset($meta['category'][0]) ? '' : $meta['category'][0];
        // $duedate = !isset($meta['duedate'][0]) ? '' : $meta['duedate'][0];
        // $possiblepoints = !isset($meta['possiblepoints'][0]) ? '' : $meta['possiblepoints'][0];

        wp_nonce_field(basename(__FILE__), 'iss_assignment_post_meta_box_nonce'); ?>
        <table class="form-table">
        <tr>
            <td  colspan="2">
                <label for="possiblepoints"><?php _e('Possible Points', 'iss_assignment_post_type'); ?>
                </label>
            </td>
            <td colspan="4">
                <input type="text" name="possiblepoints" class="" required value="<?php echo $possiblepoints; ?>">
                <!-- <p class="description"><?php _e('E.g. CEO, Sales Lead, Designer', 'iss_assignment_post_type'); ?></p> -->
            </td>
        </tr>
        <tr>
            <td  colspan="2">
                <label for="duedate"><?php _e('Due Date', 'iss_assignment_post_type'); ?>
                </label>
            </td>
            <td colspan="4">
                <input type="text" name="duedate" class="my-custom-datepicker-field" required value="<?php echo $duedate; ?>">
            </td>
        </tr>
        <input type="hidden" name="category" class="" value="<?php echo $category; ?>">
        <input type="hidden" name="classid" class="" value="<?php echo $classid; ?>">
 
        <!-- <tr>
            <td  colspan="2"> <label for="classid"><?php _e('Class ID', 'iss_assignment_post_type'); ?> </label></td>
            <td colspan="4"><?php echo $classid; ?> </td>
        </tr>
        <tr>
            <td colspan="2"> <label for="category"><?php _e('Category', 'iss_assignment_post_type'); ?></label> </td>
            <td colspan="4"> <?php echo $category; ?></td>
        </tr> -->
        </table>
        <script>
        jQuery(document).ready(function($){
            $('.my-custom-datepicker-field').datepicker({
                dateFormat: 'yyyy-mm-dd', //maybe you want something like this
                showButtonPanel: true
            });
        });
        </script>

    <?php  }

    /* Save the meta box's post metadata. */
    public function iss_assignment_save_meta_boxes($post_id, $post)
    {
        global $post;
            
            // Verify nonce
        if (!isset($_POST['iss_assignment_post_meta_box_nonce']) || !wp_verify_nonce($_POST['iss_assignment_post_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
            // Check Autosave
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])) {
            return $post_id;
        }
            // Don't save if only a revision
        if (isset($post->post_type) && $post->post_type == 'revision') {
            return $post_id;
        }
            // Check permissions
        if (!current_user_can('edit_post', $post->ID)) {
            return $post_id;
        }

        $grading = ISS_AssignmentService::LoadByID($post->ID);
        if (null != $grading) {
            $duedate = (isset($_POST['duedate']) ? esc_textarea($_POST['duedate']) : '');
            $possiblepoints = (isset($_POST['possiblepoints']) ? esc_textarea($_POST['possiblepoints']) : '');
            ISS_AssignmentService::Update($post->ID, $possiblepoints, $duedate);
        } else {
            $duedate = (isset($_POST['duedate']) ? esc_textarea($_POST['duedate']) : '');
            $possiblepoints = (isset($_POST['possiblepoints']) ? esc_textarea($_POST['possiblepoints']) : '');
            $classid = (isset($_POST['classid']) ? esc_textarea($_POST['classid']) : '');
            $category = (isset($_POST['category']) ? esc_textarea($_POST['category']) : '');
            ISS_AssignmentService::Add($post->ID, $classid, $category, $possiblepoints, $duedate);
            wp_set_post_terms( $post->ID, $category);
        }
        // $meta['possiblepoints'] = (isset($_POST['possiblepoints']) ? esc_textarea($_POST['possiblepoints']) : '');
        // $meta['duedate'] = (isset($_POST['duedate']) ? esc_url($_POST['duedate']) : '');
        // $meta['classid'] = (isset($_POST['classid']) ? esc_url($_POST['classid']) : '');
        // $meta['category'] = (isset($_POST['category']) ? esc_url($_POST['category']) : '');
        // foreach ($meta as $key => $value) {
        //     update_post_meta($post->ID, $key, $value);
        // }
    }
}
?>