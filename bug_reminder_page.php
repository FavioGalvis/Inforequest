<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page allows an authorized user to send a reminder by email to another user
 *
 * @package MantisBT
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses access_api.php
 * @uses bug_api.php
 * @uses config_api.php
 * @uses constant_inc.php
 * @uses error_api.php
 * @uses form_api.php
 * @uses gpc_api.php
 * @uses helper_api.php
 * @uses html_api.php
 * @uses lang_api.php
 * @uses print_api.php
 */

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'bug_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'error_api.php' );
require_api( 'form_api.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );

$f_bug_id = gpc_get_int( 'bug_id' );

$t_bug = bug_get( $f_bug_id, true );
if( $t_bug->project_id != helper_get_current_project() ) {
	# in case the current project is not the same project of the bug we are viewing...
	# ... override the current project. This to avoid problems with categories and handlers lists etc.
	$g_project_override = $t_bug->project_id;
}

if( bug_is_readonly( $f_bug_id ) ) {
	error_parameters( $f_bug_id );
	trigger_error( ERROR_BUG_READ_ONLY_ACTION_DENIED, ERROR );
}

access_ensure_bug_level( config_get( 'bug_reminder_threshold' ), $f_bug_id );

layout_page_header( bug_format_summary( $f_bug_id, SUMMARY_CAPTION ) );
layout_page_begin();
?>

<?php # Send reminder Form BEGIN ?>

<div class="col-md-12 col-xs-12">
<form method="post" action="bug_reminder.php">
<?php echo form_security_field( 'bug_reminder' ) ?>
<input type="hidden" name="bug_id" value="<?php echo $f_bug_id ?>" />
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
	<h4 class="widget-title lighter">
		<i class="ace-icon fa fa-envelope"></i>
		<?php echo lang_get( 'bug_reminder' ) ?>
	</h4>
</div>
<div class="widget-body">
	<div class="widget-main no-padding">
		<div class="table-responsive">
<table class="table table-bordered table-condensed table-striped">
<tr>
	<th class="category">
		<?php echo lang_get( 'to' ) ?>
	</th>
	<td>
		<select name="to[]" multiple="multiple" size="9" class="width-100">
			<?php
			$t_project_id = bug_get_field( $f_bug_id, 'project_id' );
			$t_access_level = config_get( 'reminder_receive_threshold' );
			if( $t_bug->view_state === VS_PRIVATE ) {
				$t_private_bug_threshold = config_get( 'private_bug_threshold' );
				if( $t_private_bug_threshold > $t_access_level ) {
					$t_access_level = $t_private_bug_threshold;
				}
			}
			$t_selected_user_id = 0;
			print_user_option_list( $t_selected_user_id, $t_project_id, $t_access_level );
			?>
		</select>
	</td>
	<td class="center">
		<textarea class="form-control" name="body" cols="65" rows="10"></textarea>
	</td>
</tr>
</table>
	</div>
		</div>
		<div class="widget-toolbox padding-8 clearfix">
			<input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo lang_get( 'bug_send_button' ) ?>" />
		</div>
	</div>
	</div>
	</form>
	<div class="alert alert-info">
		<p><i class="fa fa-info-circle fa-lg"> </i>
		<?php
			echo lang_get( 'reminder_explain' ) . ' ';
			if( ON == config_get( 'reminder_recipients_monitor_bug' ) ) {
				echo lang_get( 'reminder_monitor' ) . ' ';
			}
			if( ON == config_get( 'store_reminders' ) ) {
				echo lang_get( 'reminder_store' );
			}
		?>
		</p>
	</div>
</div>

<?php
$_GET['id'] = $f_bug_id;
$t_fields_config_option = 'bug_view_page_fields';
$t_show_page_header = false;
$t_force_readonly = true;
$t_mantis_dir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
$t_file = __FILE__;

define( 'BUG_VIEW_INC_ALLOW', true );
include( $t_mantis_dir . 'bug_view_inc.php' );
