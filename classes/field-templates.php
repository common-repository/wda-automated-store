<?php

/*-------------------------------------------
*  Exit if accessed directly
*-------------------------------------------*/
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WDAAUTOS_Field_Templates' ) ) {
	class WDAAUTOS_Field_Templates {
		private $args;
		private $input_value;
	
	
		/*-------------------------------------------
		*  Rendering Settings Form
		*-------------------------------------------*/
		public function render_form ( $args  ) {
			$this->args = $args;
			$this->input_value = get_option( $this->args['option_name'], '' );

			?>
			<label>
				<?php $this->render_input( $this->args ); ?>
				<p
					class="description"
					id="<?php echo esc_attr( $this->args['option_name'] ); ?>-description"
				><?php echo esc_html( $this->args['description'] ); ?></p>
			</label>
			<?php
		}
	
	
		/*-------------------------------------------
		*  Rendering Input Field
		*-------------------------------------------*/
		private function render_input ( $args ) {
			$type		= $args['type'];
			$option_key = $args['option_name'];
			$value 		= $type !== 'checkbox' ? $this->input_value : 'yes';
			$checked	= $this->input_value && $type == 'checkbox' ? checked( 'yes', $this->input_value, false) : '';
			$custom 	= array_key_exists( 'custom_arg', $args ) ? $args['custom_arg'] : '';
			$args['options'] = array_key_exists( 'options', $args ) ? $args['options'] : [];
			
			
			
			switch ( $type ) {
				case 'select':
					if ( count($args['options']) ) :
					?>
					<select
						id="<?php echo esc_attr( $option_key ); ?>"
						name="<?php echo esc_attr( $option_key ); ?>"
					><?php $this->options( $args['options'] ); ?></select><?php
					endif;
					break;

				case 'checkbox':
					if ( count($args['options']) ) {
						$this->checkbox( $value, $args['options'] );
					}
					break;

				case 'radio':
					if ( count($args['options']) ) {
						$this->radio( $option_key, $args['options'] );
					}
					break;
				
				default:
					?>
					<input
						id="<?php echo esc_attr( $option_key ); ?>"
						name="<?php echo esc_attr( $option_key ); ?>"
						type="<?php echo esc_attr( $type ); ?>"
						value="<?php echo esc_attr( $value ? $value : (array_key_exists('value', $args) ? $args['value'] : '') ); ?>"
						<?php echo esc_attr( $checked ) . ' ' . esc_attr( $custom ); ?>
					/>
					<?php
					break;
			}
		}

		private function options ( $options ) {
			foreach ($options as $key => $value) :
				?><option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $key == $this->input_value ? 'selected' : '' ); ?>><?php echo esc_html($value); ?></option><?php
			endforeach;
		}

		private function checkbox ( $value, $options ) {
			foreach ($options as $key => $label) :
				?>
				 <input
				 	type="checkbox"
					id="<?php echo esc_attr( $key ); ?>"
					name="<?php echo esc_attr( $key ); ?>"
					value="yes"
					<?php echo esc_attr($value == 'yes' ? 'checked' : ''); ?>
				/>
				 <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label><br>
				 <?php
			endforeach;
		}

		private function radio ( $input_key, $options ) {
			foreach ($options as $key => $value) :
				?>
				 <input
				 	type="radio"
					id="<?php echo esc_attr( $key ); ?>"
					name="<?php echo esc_attr( $input_key ); ?>"
					value="<?php echo esc_attr( $key ); ?>"
				/>
				 <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></label><br>
				 <?php
			endforeach;
		}

		public function settings_template ( $page_title, $option_group, $page_slug, $submit_btn = [] ) {
			// check user capabilities
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
		
			// add error/update messages
		
			// check if the user have submitted the settings
			// WordPress will add the "settings-updated" $_GET parameter to the url
			if ( isset( $_GET['settings-updated'] ) ) {
				// add settings saved message with the class of "updated"
				add_settings_error( 'wdaautos_messages', 'wdaautos_message', __( 'Settings Saved', 'wda-automated-store' ), 'updated' );
			}
		
			// show error/update messages
			settings_errors( 'wdaautos_messages' );


			?>
			<div class="wrap">
				<h1><?php echo esc_html( $page_title ); ?></h1>
				<form action="options.php" method="post">
					<?php


					/*----- Nonce Field : So that we can verify while saving -----*/
					wp_nonce_field( 'wdaautos_fields_form', 'wdaautos_fields_form_nonce' );



					/*
					* Prints the input fields with names 'nonce', 'action', and 'option_page' in form section of settings page.
					* The 'schedule_settings' is the settings group name, wich should match the group name used in register_settings()
					* The add_settings_section callback is displayed here. For every new section we need to call settings_fields.
					*/
					settings_fields( $option_group );
					
					/*
					* Prints out heading( h2 ) and a table with all settings sections inside the form section of the settings page.
					* 'schedule-settings' is the slug name of the page whose settings section you want to output
					* All the add_settings_field callbacks is displayed here.
					*/
					do_settings_sections( $page_slug );
		
					// Add the submit button to serialize the options
					if ( !count( $submit_btn ) ) {
						submit_button( 'Save Settings' );
					} else {
						submit_button(
							array_key_exists( 'label', $submit_btn ) ? $submit_btn['label'] : 'Save Settings',
							array_key_exists( 'class', $submit_btn ) ? $submit_btn['class'] : 'primary',
							array_key_exists( 'id', $submit_btn )    ? $submit_btn['id'] : ''
						);
					}
					?>
				</form>
			</div>
			<?php
        
 
			/*-------------------------------------------
			*  Bailout if nonce is not verified
			*-------------------------------------------*/
			if ( ! isset( $_POST['wdaautos_fields_form_nonce'] ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['wdaautos_fields_form_nonce'])), 'wdaautos_fields_form' ) ) {
				return;
			}
			
		}
	}
}