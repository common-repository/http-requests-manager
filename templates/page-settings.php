<script>
	var VPHRM = {
		response: [],
		domains: [],
		query_args: {
			'orderby': 'id',
			'order': 'DESC',
			'get_domains': 1,
			'page': 1
		},
		message_save_error: '<?php echo __('Error saving option. Please try again.', 'http-requests-manager'); ?>',
		message_connection_error: '<?php echo __('Data loading error. Please try again.', 'http-requests-manager'); ?>',
		group_view: '<?php echo HTTP_Requests_Manager::get_option('view') ?>',
		nonce: '<?php echo wp_create_nonce('vphrm_nonce'); ?>'
	};
</script>

<div class="wrap">
    <h1><?php _e('HTTP Requests Manager', 'http-requests-manager'); ?> <em class="vphrm-header-info"> — by <a href="https://veppa.com/" target="_blank">veppa.com</a></em></h1>

	<h2 class="nav-tab-wrapper">
		<a href="#vphrm-log" class="nav-tab nav-tab-active"><?php _e('Log', 'http-requests-manager'); ?></a>
		<a href="#vphrm-settings" class="nav-tab"><?php _e('Settings', 'http-requests-manager'); ?></a>
		<a href="#vphrm-more-tools" class="nav-tab"><?php _e('More tools', 'http-requests-manager'); ?></a>
	</h2>
	<div class="vphrm-panel-wrapper">
		<!-- vphrm-panel-wrapper --> 
		<div class="vphrm-settings vphrm-panel">
			<!-- settings  -->



			<table class="form-table" role="presentation">

				<tbody>

					<tr>
						<th scope="row"><label for="mode"><?php _e('Operation mode', 'http-requests-manager'); ?></label></th>
						<td>
							<?php $current_mode = HTTP_Requests_Manager::get_mode(); ?>
							<select name="mode" id="mode" class="vphrm-mode" data-current="<?php echo esc_attr($current_mode); ?>">
								<?php
								$modes = HTTP_Requests_Manager::modes();
								foreach($modes as $k => $v)
								{
									echo '<option value="' . esc_attr($k) . '"' . ($k == $current_mode ? ' selected="selected"' : '') . '>'
									. esc_html($v)
									. '</option>';
								}
								?>			
							</select>	
							<p class="description">
								— <a href="#help"><?php echo __('Which one to choose?', 'http-requests-manager'); ?></a>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e('Load before other plugins', 'http-requests-manager'); ?></label></th>
						<td>
							<input type="checkbox" name="load_must_use" value="1" id="load_must_use" <?php echo (HTTP_Requests_Manager::get_option('load_must_use') ? ' checked="checked"' : '') ?> /> 
							<label>Enable priority loading with Must-Use plugin feature.</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e('Logging', 'http-requests-manager'); ?></label></th>
						<td>
							<input type="checkbox" name="disable_logging" value="1" id="disable_logging" <?php echo (HTTP_Requests_Manager::get_option('disable_logging') ? ' checked="checked"' : '') ?> /> 
							<label>Disable logging. Faster, no new logs will be added to database. You can still analyze existing logs.</label>
						</td>
					</tr>
				</tbody>
			</table>


			<hr/> 

			<h3>Custom rules for "Smart Block" mode. 
				<a href="#" class="page-title-action vphrm-add-custom-rule vphrm-toggle-action" data-toggle=".vphrm-form-custom-rule">Add New</a>
			</h3>



			<div class="vphrm-form-custom-rule vphrm-toggle-hide vphrm-card vphrm-card-full">
				<form>
					<table class="form-table" role="presentation">
						<tbody>
							<tr>
								<th scope="row"><label for="rule_type">What</label></th>
								<td>
									<?php
									$loaded_plugins = HTTP_Requests_Manager::get_loaded_plugins();
									?>
									<select name="rule_type" id="rule_type">
										<?php echo ($loaded_plugins ? '<option value="plugin">plugin</option>' : '') ?>
										<option value="all">all requests</option>
									</select>

									<?php
									if($loaded_plugins)
									{
										echo '<select name="rule_plugin" id="rule_plugin">';
										foreach($loaded_plugins as $val)
										{
											echo '<option value="' . $val . '">' . $val . '</option>';
										}
										echo '</select>';
									}
									?>
									<a href="#help-custom-rule">(help)</a>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="rule_where">Where</label></th>
								<td>
									<select name="rule_where" id="rule_where">
										<option value="">everywhere</option>
										<?php
										foreach(HTTP_Requests_Manager::$page_types as $v)
										{
											echo '<option value="' . $v . '">' . $v . '</option>';
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">Action</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span>Action</span></legend>
										<label><input type="radio" name="rule_action" value="block" checked="checked">block</label><br>
										<label><input type="radio" name="rule_action" value="allow">allow</label>
									</fieldset>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				<p class="submit">
					<input type="button" name="cancel" id="cancel" class="button vphrm-toggle-action" data-toggle=".vphrm-form-custom-rule" value="Cancel">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
			</div>




			<hr/> 
			<!-- help  -->
			<div class="vphrm-docs">
				<h2 id="help"><?php _e('Help', 'http-requests-manager'); ?></h2>
				<h3><?php _e('Operation mode', 'http-requests-manager'); ?></h3>

				<ul>
					<li><?php _e('<b>Only log HTTP requests</b> — logs all non cron requests. No blocking done.', 'http-requests-manager'); ?></li>
					<li><?php _e('<b>Only log HTTP requests (+ cron requests)</b> — logs all requests including cron. No blocking done.', 'http-requests-manager'); ?></li>
					<li><?php _e('<b>Smart block</b> — logs non cron HTTP requests and blocks request using following rules.', 'http-requests-manager'); ?>
						<ol>
							<li><?php _e('Page processing time exceeded 3 seconds.', 'http-requests-manager'); ?></li>
							<li><?php _e('Number of request for single page reached 3.', 'http-requests-manager'); ?></li>
							<li><?php _e('Sets timeout for each request (except file downloads) to 2 second.', 'http-requests-manager'); ?></li>
							<li><?php _e('Sets number of redirects for request to 1.', 'http-requests-manager'); ?></li>				
							<li><?php _e('Apply custom rules for "Smart block" defined in settings.', 'http-requests-manager'); ?></li>				
							<li><?php _e('Prevent some built in requests: happy browser, maybe update, self pings, do_enclose.', 'http-requests-manager'); ?></li>	
							<li><?php _e('Skip some limitations listed above for: file downloads (plugin, theme, other), requests inside cron jobs.', 'http-requests-manager'); ?></li>	
						</ol>
					</li>
					<li><?php _e('<b>Block external requests</b> — all requests not matching your current domain will be blocked. No updates for WordPress core, plugins and themes.', 'http-requests-manager'); ?>
						<b><?php _e('+ Smart block features.', 'http-requests-manager'); ?></b></li>
					<li><?php _e('<b>Block external requests (allow WordPress.org only)</b> — all requests not matching your current domain and wordpress.org will be blocked. Allows updates for WordPress core, plugins and themes coming from wordpress.org website.', 'http-requests-manager'); ?>
						<b><?php _e('+ Smart block features.', 'http-requests-manager'); ?></b></li>
				</ul>
				<p><b><a href="https://veppa.com/http-requests-manager/?utm_source=wp&utm_medium=plugin&utm_campaign=options#doc" target="_blank"><?php _e('Learn more', 'http-requests-manager') ?> →</a></b></p>




				<h3 id="help-custom-rule"><?php _e('Custom rule data', 'http-requests-manager'); ?></h3>
				<ol>
					<li><?php _e('Domains generated from existing logs. Run plugin for couple days with logging enabled to populate more domains.', 'http-requests-manager'); ?></li>
					<li><?php _e('Plugins generated from current active plugins.', 'http-requests-manager'); ?></li>
					<li><?php _e('Maximum 10 custom rules allowed. Please contact if you need more.', 'http-requests-manager'); ?></li>
				</ol>
				
				<p><b><a href="https://veppa.com/allow-wp_http-request/?utm_source=wp&utm_medium=plugin&utm_campaign=options#doc" target="_blank"><?php _e('Allow Request Tutorial', 'http-requests-manager') ?> →</a></b></p>
				<p><b><a href="https://veppa.com/block-wp_http-request/?utm_source=wp&utm_medium=plugin&utm_campaign=options#doc" target="_blank"><?php _e('Block Request Tutorial', 'http-requests-manager') ?> →</a></b></p>

			</div>
			<!-- settings END  -->
		</div>


		<div class="vphrm-log vphrm-panel vphrm-panel-active">
			<!-- log  -->
			<p>		
				<button class="button vphrm-clear" onclick="VPHRM.clear()"><?php _e('Clear log', 'http-requests-manager'); ?></button>
				<button class="button vphrm-refresh" onclick="VPHRM.refresh()"><?php _e('Refresh', 'http-requests-manager'); ?></button>
				<select name="group" id="group" class="vphrm-group-view vphrm-hidden">
					<?php
					$arr_group_view = array(
						'group-no'				 => 'no group',
						'group-req-url'			 => 'Group by request URL',
						'group-req-domain'		 => 'Group by request domain',
						'group-page-url'		 => 'Group by page URL',
						'group-page-type'		 => 'Group by page type',
						'group-request-source'	 => 'Group by initiator',
						'group-request-group'	 => 'Group by initiator type',
						'group-response'		 => 'Group by response'
					);

					$current_view = HTTP_Requests_Manager::get_option('view');

					foreach($arr_group_view as $k => $v)
					{
						echo '<option value="' . $k . '"' . ($current_view === $k ? ' selected="selected"' : '') . '>' . $v . '</option>';
					}
					?>
				</select>
			</p>
			<div class="vphrm-wrap"></div>
			<hr/>
			<!-- log END  -->
		</div>

		<div class="vphrm-more-tools vphrm-panel">
			<!-- more  -->
			<p>
				<a class="vphrm-card vphrm-card-wide" href="https://veppa.com/share-button/?utm_source=wp&utm_medium=plugin&utm_campaign=options" target="_blank">
					<span class="vphrm-card-val">⦿</span>
					<span class="vphrm-card-name"><b class="vphrm-card-h3">Share button without plugin</b>
						Fast load times, tiny code, no negative effect on Page Speed score. Free.</span>
				</a>
				<a class="vphrm-card vphrm-card-wide" href="https://veppa.com/speed/?utm_source=wp&utm_medium=plugin&utm_campaign=options" target="_blank">
					<span class="vphrm-card-val">⪫</span>
					<span class="vphrm-card-name"><b class="vphrm-card-h3">90+ PageSpeed</b>
					Download PDF checklist that I use to get 90+ score on WordPress websites.</span>
				</a>
				<a class="vphrm-card vphrm-card-wide" href="https://youtu.be/W9wroFTw2mM" target="_blank">
					<span class="vphrm-card-val">▷</span>
					<span class="vphrm-card-name"><b class="vphrm-card-h3">PageSpeed Score 100</b>
					Video tutorial optimizing WordPress with 25 plugins, 2 external JS, YouTube video embeds.</span>
				</a>
			</p>
			<!-- more END  -->
		</div>

		<div class="vphrm-log vphrm-panel vphrm-panel-active">
			<!-- settings-log-footer  -->
			<div class="vphrm-docs">

				<h3><?php _e('Documentation', 'http-requests-manager'); ?></h3>

				<p>
					<a class="vphrm-card vphrm-card-wide" href="https://veppa.com/http-requests-manager/?utm_source=wp&utm_medium=plugin&utm_campaign=options#doc" target="_blank">
						<span class="vphrm-card-val">§</span>
						<span class="vphrm-card-name"><?php _e('Read documentation', 'http-requests-manager'); ?></span>
					</a>
					<a class="vphrm-card vphrm-card-wide" href="https://wordpress.org/support/plugin/http-requests-manager" target="_blank">
						<span class="vphrm-card-val">𝔖</span>
						<span class="vphrm-card-name"><?php _e('Support forum', 'http-requests-manager'); ?></span>
					</a>
					<a class="vphrm-card vphrm-card-wide" href="https://youtube.com/playlist?list=PLvn-qBzU0II7b5D4OYDnKpNpuvxiM0f4b" target="_blank">
						<span class="vphrm-card-val">►</span>
						<span class="vphrm-card-name"><?php _e('Video tutorials', 'http-requests-manager'); ?></span>
					</a>
				</p>

				<h3><?php _e('Do you like HTTP Requests Manager?', 'http-requests-manager'); ?></h3>
				<p><?php _e('If you\'re happy with plugin, there\'s a few things you can do to let others know:', 'http-requests-manager'); ?></p>

				<p>
					<a class="vphrm-card vphrm-card-wide" href="https://twitter.com/" target="_blank">
						<span class="vphrm-card-val">※</span>
						<span class="vphrm-card-name"><?php _e('Write about it in your blog, twitter, create a video review on youtube and other social meida accounts.', 'http-requests-manager'); ?></span>
					</a>
					<a class="vphrm-card vphrm-card-wide" href="https://wordpress.org/support/plugin/http-requests-manager/reviews/?rate=5#new-post" target="_blank">
						<span class="vphrm-card-val">★</span>
						<span class="vphrm-card-name"><?php _e('Give a good rating on WordPress.org', 'http-requests-manager'); ?></span>
					</a>
					<a class="vphrm-card vphrm-card-wide" href="https://www.paypal.com/donate/?hosted_button_id=LZ4LP4MQJDH7Y" target="_blank">
						<span class="vphrm-card-val">$</span>
						<span class="vphrm-card-name"><?php _e('Sponsor our open-source initiative', 'http-requests-manager'); ?></span>
					</a>
				</p>
				
				<p><b><?php _e('Thank you!', 'http-requests-manager') ?></b></p>

				<p><a href="#top"><?php _e('Back to top', 'http-requests-manager') ?> ↑</a></p>		
			</div>
			<!-- settings-log-footer END  -->
		</div>
		<!-- vphrm-panel-wrapper END -->
	</div>

</div>

<!-- Modal window -->
<div id="vphrm-modal" class="media-modal vphrm-modal">
	<div class="vphrm-modal-buttons">
		<button class="button-link media-modal-close prev"><span class="media-modal-icon"></span></button>
		<button class="button-link media-modal-close next"><span class="media-modal-icon"></span></button>
		<button class="button-link media-modal-close"><span class="media-modal-icon"></span></button>
	</div>
    <div class="media-modal-content">
        <div class="media-frame">
            <div class="media-frame-title">
                <h1><?php _e('HTTP Request', 'http-requests-manager'); ?></h1>
            </div>
            <div class="media-frame-content">
                <div class="modal-content-wrap">
                    <p>
						<b>Request <span class="http-request-runtime"></span>:</b> 
                        [<span class="http-request-id"></span>]
                        <span class="http-url vphrm-break-word"></span>
                    </p>
					<p>
						<b>Page <span class="http-page-runtime"></span>:</b> 
                        <span class="http-page vphrm-break-word"></span>
                    </p>

					<p class="nav-tab-wrapper wp-clearfix">
						<a href="#vphrm-request-response" class="nav-tab nav-tab-active">Request</a>
						<a href="#vphrm-cp" class="nav-tab">Check point</a>
					</p>					
					<div class="vphrm-panel-wrapper">
						<div class="vphrm-request-response vphrm-panel vphrm-panel-active wrapper">
							<div class="box">
								<h3>Request</h3>
								<div class="http-request-args vphrm-pre-300"></div>
							</div>
							<div class="box">
								<h3>Response</h3>
								<div class="http-response vphrm-pre-300"></div>
							</div>						
						</div>
						<div class="vphrm-cp vphrm-panel"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="media-modal-backdrop"></div>
<!-- Modal window END -->