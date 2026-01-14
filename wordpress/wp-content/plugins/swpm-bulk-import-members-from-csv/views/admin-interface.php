<?php

class SWPMBMI_admin_interface {

    static function replace_vars( $tpl, $vars ) {
	foreach ( $vars as $key => $value ) {
	    $tpl = str_replace( '%_' . $key . '_%', $value, $tpl );
	}
	return $tpl;
    }

    static function render_results_table( $results_success, $results_failed ) {
	ob_start();
	// Import Results template
	?>
	<h4><?php echo SWMPBMI_main::_( 'Statistics:' ); ?></h4>
	<table class="swpmbmi-table">
	    <tr>
		<th><?php echo SWMPBMI_main::_( 'Succeeded' ); ?></th>
		<th><?php echo SWMPBMI_main::_( 'Failed' ); ?></th>
		<th><?php echo SWMPBMI_main::_( 'Total' ); ?></th>
	    </tr>
	    <tr>
		<td style="color: green;">%_success_cnt_%</td>
		<td style="color: red;">%_failed_cnt_%</td>
		<td>%_total_cnt_%</td>
	    </tr>
	</table>
	%_failed_items_tbl_%
	<?php
	$tpl				 = ob_get_clean();
	ob_start();
	// Failed import items table
	?>
	<h4><?php echo SWMPBMI_main::_( 'Failed to import:' ); ?></h4>
	<table class="swpmbmi-table">
	    <tr>
		<th><?php echo SWMPBMI_main::_( 'Row №' ); ?></th>
		<th><?php echo SWMPBMI_main::_( 'Username' ); ?></th>
		<th><?php echo SWMPBMI_main::_( 'Email' ); ?></th>
		<th><?php echo SWMPBMI_main::_( 'Failure reason' ); ?></th>
	    </tr>
	    %_failed_items_%
	</table>
	<?php
	$failed_tbl_tpl			 = ob_get_clean();
	$vars				 = array();
	$vars[ 'success_cnt' ]		 = count( $results_success );
	$vars[ 'failed_cnt' ]		 = count( $results_failed );
	$vars[ 'total_cnt' ]		 = $vars[ 'success_cnt' ] + $vars[ 'failed_cnt' ];
	$vars[ 'failed_items_tbl' ]	 = '';
	if ( ! empty( $results_failed ) ) {
	    $vars[ 'failed_items' ] = '';
	    foreach ( $results_failed as $res ) {
		$vars[ 'failed_items' ] .= '<tr><td>' . $res[ 'line_num' ] . '</td><td>' . $res[ 'user_name' ] . '</td><td>' . $res[ 'email' ] . '</td><td>' . $res[ 'failure_reason' ] . '</td></tr>';
	    }
	    $vars[ 'failed_items_tbl' ] = $failed_tbl_tpl;
	}
	$tpl = SWPMBMI_admin_interface::replace_vars( $tpl, $vars );
	return $tpl;
    }

    function render_admin_page() {
	ob_start();
	?>
	<style>
	    div#swpmbmi-spinner span {
		float: none;
		vertical-align: text-top;
	    }
	    div#swpmbmi-import-results-box {
		display: none;
	    }
	    table.swpmbmi-table th, table.swpmbmi-table td  {
		padding-right: 20px;
		text-align: center;
		padding-bottom: 5px;
	    }
	</style>
	<div class="wrap">
	    <h1><?php echo SWMPBMI_main::_( 'Simple Membership Bulk Members Import' ); ?></h1>

	    <div id="poststuff">
		<div id="post-body">
		    <p style="background: #fff6d5; border: 1px solid #d1b655; color: #3f2502; margin: 10px 0;  padding: 5px 5px 5px 10px;">
			<?php echo SWMPBMI_main::_( sprintf( 'Read the %s to learn how to use the addon.', sprintf( '<a href="https://simple-membership-plugin.com/simple-membership-bulk-import-member-data-csv-file/" target="_blank">%s</a>', SWMPBMI_main::_( 'usage documentation' ) ) ) ); ?>
		    </p>
		    <form method="post" id="swpmbmi-upload-form" enctype="multipart/form-data">
			<div class="postbox">
			    <h3 class="hndle"><label for="title"><?php echo SWMPBMI_main::_( 'Bulk Member Import' ); ?></label></h3>
			    <div class="inside">
				<table class="form-table">
				    
				    <tr valign="top">
					<th scope="row"><?php echo SWMPBMI_main::_( 'Set a Default Password' ); ?></th>
					<td>
					    <input type="text" name="swpmbmi-default-password" id="swpmbmi-default-password" value="" size="30" />
					    <p class="description">
						<?php echo SWMPBMI_main::_('You can use it to set a default password for all the accounts that you will import. If you leave it empty, a random password will be generated for the imported accounts. '); ?>
						<?php echo SWMPBMI_main::_('The members will be able to change the password from the profile edit page of your site.'); ?>
					    </p>
					</td>
				    </tr>
				    
				    <tr valign="top">
					<th scope="row"><?php echo SWMPBMI_main::_( 'Send Registration Complete Email' ); ?></th>
					<td>
					    <input type="checkbox" name="swpmbmi-send-rego-complete-email" id="swpmbmi-send-rego-complete-email" value="1" />
					    <p class="description"><?php echo SWMPBMI_main::_( 'Check this option if you want the registration complete email to be sent to the users after account import.' ); ?></p>
					</td>
				    </tr>
				    
				    <tr valign="top">
					<th scope="row"><?php echo SWMPBMI_main::_( 'Upload CSV File' ); ?></th>
					<td>
					    <input type="file" name="swpmbmi-file" id="swpmbmi-file" value="" size="60" />
					    <p class="description"><?php echo SWMPBMI_main::_( 'Select the CSV file with members information.' ); ?></p>
					</td>
				    </tr>
				</table>
			    </div></div>
		    </form>
		    <div id="swpmbmi-import-results-box" class="postbox">
			<h3 class="hndle"><label for="title"><?php echo SWMPBMI_main::_( 'Import Results' ); ?></label></h3>
			<div class="inside">
			    <div id="swpmbmi-spinner"><span class="spinner is-active"></span></div>
			    <div id="swpmbmi-import-results">
			    </div>
			</div>
		    </div>

		</div>

	    </div>
	</div>
	<script>
	    jQuery(document).ready(function ($) {
		$('input#swpmbmi-file').change(function () {
		    $('div#swpmbmi-import-results-box').hide();
		    var filename = $(this).val();
		    var ext = filename.substring(filename.lastIndexOf('.') + 1, filename.length) || filename;
		    if (ext.toLowerCase() !== 'csv') {
			$(this).val('');
			alert('<?php echo SWMPBMI_main::_( esc_html( 'Please select CSV file!' ) ) ?>');
			return false;
		    }
		    var data = new FormData($('#swpmbmi-upload-form')[0]);
		    data.append('action', 'swpmbmi_process_file');
		    $('div#swpmbmi-import-results-box').show();
		    $('div#swpmbmi-spinner').show();

		    var request = $.ajax({
			url: '<?php echo get_admin_url(); ?>admin-ajax.php',
			data: data,
			type: 'POST',
			processData: false,
			cache: false,
			contentType: false,
		    });
		    request.done(function (out) {
			if (out.error) {
			    alert(out.err_msg);
			} else {
			    $('#swpmbmi-import-results').html(out.result);
			    $('div#swpmbmi-spinner').hide();
			}
		    });
		});
	    });
	</script>
	<?php
	$tpl = ob_get_clean();
	echo $tpl;
    }

}
