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
 * Bugnote add include file
 *
 * @package MantisBT
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses access_api.php
 * @uses bug_api.php
 * @uses collapse_api.php
 * @uses config_api.php
 * @uses constant_inc.php
 * @uses event_api.php
 * @uses form_api.php
 * @uses helper_api.php
 * @uses html_api.php
 * @uses lang_api.php
 */

if( !defined( 'BUGDEVLOG_ADD_INC_ALLOW' ) ) {
	return;
}

require_api( 'access_api.php' );
require_api( 'bug_api.php' );
require_api( 'collapse_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'event_api.php' );
require_api( 'form_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );

?>
<?php if( ( !bug_is_readonly( $f_bug_id ) ) &&
		( access_has_bug_level( config_get( 'add_bugnote_threshold' ), $f_bug_id ) ) ) { ?>
<?php # Bugnote Add Form BEGIN ?>

<div class="col-md-6 col-xs-12">
<a id="addbugdevlog"></a>
<div class="space-10"></div>

<?php
	$t_collapse_block = is_collapsed( 'bugdevlog_add' );
	$t_block_css = $t_collapse_block ? 'collapsed' : '';
	$t_block_icon = $t_collapse_block ? 'fa-chevron-down' : 'fa-chevron-up';
?>
<form id="bugdevlogadd" method="post" action="bugdevlog_add.php" enctype="multipart/form-data">
	<?php echo form_security_field( 'bugdevlog_add' ) ?>
	<input type="hidden" name="bug_id" value="<?php echo $f_bug_id ?>" />
	<div id="bugdevlog_add" class="widget-box widget-color-green2 <?php echo $t_block_css ?>">
		<div class="widget-header widget-header-small">
			<h4 class="widget-title lighter">
				<i class="ace-icon fa fa-comment"></i>
				<?php echo lang_get( 'add_bugdevlog_title' ) ?>
			</h4>
			<div class="widget-toolbar">
				<a data-action="collapse" href="#">
					<i class="1 ace-icon fa <?php echo $t_block_icon ?> bigger-125"></i>
				</a>
			</div>
		</div>

		<div class="widget-body">
		<div class="widget-main no-padding">

		<div class="table-responsive">
		<table class="table table-bordered table-condensed">
		<tbody>
			<tr>
				<th class="category" width="15%">
					<?php echo lang_get( 'bugdevlog' ) ?>
				</th>
				<td width="85%">
					<textarea name="bugdevlog_text" class="form-control" rows="7"></textarea>
				</td>
			</tr>

<?php
	if( access_has_bug_level( config_get( 'set_view_status_threshold' ), $f_bug_id ) ) {
?>
			<!--tr>
				<th class="category">
					<?php /* echo lang_get( 'view_status' ) */ ?>
				</th>
				<td>
<?php /*
		$t_default_bugdevlog_view_status = config_get( 'default_bugnote_view_status' );
		if( access_has_bug_level( config_get( 'set_view_status_threshold' ), $f_bug_id ) ) {
*/ ?>
				<label for="bugdevlog_add_view_status">
					<input type="checkbox" class="ace" id="bugdevlog_add_view_status" name="private" <?php /* check_checked( $t_default_bugdevlog_view_status, VS_PRIVATE ); */ ?> />
					<span class="lbl"> <?php /* echo lang_get( 'private' ); */ ?> </span>
				</label>
<?php /*
		} else {
			echo get_enum_element( 'project_view_state', $t_default_bugdevlog_view_status );
		}
*/ ?>
				</td>
			</tr-->
<?php
	}

	if( config_get( 'time_tracking_enabled' ) ) {
		if( access_has_bug_level( config_get( 'time_tracking_edit_threshold' ), $f_bug_id ) ) {
?>
			<tr>
				<th class="category">
					<?php echo lang_get( 'time_tracking' ) ?>
				</th>
				<td>
					<?php if( config_get( 'time_tracking_stopwatch' ) ) { ?>
					<input type="text" name="time_tracking" class="stopwatch_time input-sm" size="8" placeholder="hh:mm:ss" />
					<input type="button" name="time_tracking_toggle" class="stopwatch_toggle btn btn-sm btn-white btn-round" value="<?php echo lang_get( 'time_tracking_stopwatch_start' ) ?>" />
					<input type="button" name="time_tracking_reset" class="stopwatch_reset btn btn-sm btn-white btn-round" value="<?php echo lang_get( 'time_tracking_stopwatch_reset' ) ?>" />
					<?php } else { ?>
					<input type="text" name="time_tracking" class="input-sm" size="5" placeholder="hh:mm" />
					<?php } ?>
				</td>
			</tr>
<?php
		}
	}
	event_signal( 'EVENT_BUGNOTE_ADD_FORM', array( $f_bug_id ) );
?>
		</tbody>
</table>
</div>
</div>
	<div class="widget-toolbox padding-8 clearfix">
		<input type="submit" class="btn btn-success btn-white btn-round" value="<?php echo lang_get( 'add_bugdevlog_button' ) ?>" />
	</div>
</div>
</div>
</form>
</div>
<?php
}
