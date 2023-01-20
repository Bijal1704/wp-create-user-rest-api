<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class WP_USER_API {	

	public function __construct() {
		// Initialize class variables
	}

	/**
	 * Register custom rest api route
	 * 
	 * @return void
	 * 
	 * */
	public function wp_user_api_rest_route() {

		/**
		 * Register new end point for create user
		 * 
		 * @TODO Didn't implemented authentication as it needs to accesible publicly
		 * If required, We can implement authentication using Application password or JWP autnentication
		 **/
		register_rest_route( 'wp_user_api/v1', '/users/', array(
			'methods'  => 'POST',
			'callback' => array($this, 'wp_user_api_rest_callback'),	
		) );
	}

	
	/**
	 * Create user Callback
	 * 
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 **/
	function wp_user_api_rest_callback( WP_REST_Request $request ) {
		
	     // Validate and sanitize the request data	
		$password = sanitize_text_field( $request->get_param('password') );
		$email = sanitize_email( $request->get_param('email') );
		$first_name = sanitize_text_field( $request->get_param('first_name') );
		$last_name = sanitize_text_field( $request->get_param('last_name') );
		$address = sanitize_text_field( $request->get_param('address') );
		$city = sanitize_text_field( $request->get_param('city') );
		$state = sanitize_text_field( $request->get_param('state') );
		$zip = sanitize_text_field( $request->get_param('zip') );
		$phone = sanitize_text_field( $request->get_param('phone') );
		$birthdate = sanitize_text_field( $request->get_param('birthdate') );

	    // Prepare array for creating user
		$user_data = array(
			'user_login' => sanitize_user( $email ),
			'user_pass'  => $password,
			'user_email' => $email,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'role'       => WP_USER_API_CUSTOM_ROLE,
		);

		// Insert user
		$user_id = wp_insert_user( $user_data );
		if ( is_wp_error( $user_id ) ) {
	        // There was an error creating the user
			$response = new WP_Error( 'user_creation_error', $user_id->get_error_message(), array( 'status' => 400 ) );
			return $response;
		} else {
	        // The user was created successfully
	        // Save the additional user data
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'address', $address );
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'city', $city );
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'state', $state );
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'zip', $zip );
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'phone', $phone );
			update_user_meta( $user_id, WP_USER_API_META_PREFIX .'birthdate', $birthdate );

			$response = array(
				'id'   => $user_id,
				'code' => 'user_created',
				'message' => 'User created successfully',
			);

			return new WP_REST_Response( $response, 200 );
		}		
	}

	/**
     * add hooks
     * 
     * 
     * @package WP User API
     * @since 1.0.0
     */
	public function add_hooks() {

		add_action( 'rest_api_init', [ $this, 'wp_user_api_rest_route' ] );
	}
}