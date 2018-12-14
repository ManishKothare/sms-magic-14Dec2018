<?php
/**
 * Template Name: 	Appexchange Trial Form Page
 * Navy Theme:		Appexchange Trial Form Page
 * @package:		WordPress
 * @subpackage:		Navy Theme
 * @version:		1.0
 * @since:			1.0
 */
 
 	// Include framework options
 	$hgr_options = get_option( 'redux_options' );
 
	get_header('appexchange');
 ?>

 <?php
	// Get metaboxes values from database
	$hgr_page_bgcolor		=	get_post_meta( get_the_ID(), '_hgr_page_bgcolor', true );
	$hgr_page_top_padding		=	get_post_meta( get_the_ID(), '_hgr_page_top_padding', true );
	$hgr_page_btm_padding		=	get_post_meta( get_the_ID(), '_hgr_page_btm_padding', true );
	$hgr_page_color_scheme		=	get_post_meta( get_the_ID(), '_hgr_page_color_scheme', true );
	$hgr_page_height		=	get_post_meta( get_the_ID(), '_hgr_page_height', true );
	$hgr_page_title			=	get_post_meta( get_the_ID(), '_hgr_page_title', true );
	$hgr_page_title_color		=	get_post_meta( get_the_ID(), '_hgr_page_title_color', true );
	
	$page_title_color		=	( !empty($hgr_page_title_color) ? ' style="color: '.$hgr_page_title_color.'; "' : ( isset($hgr_options['page_title_h1']['color']) && !empty($hgr_options['page_title_h1']['color']) ? '' : ' style="color: #000; "' ) );
												
												
												
												
	
	// Does this page have a featured image to be used as row background with paralax?!
 	$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( 5600,1000 ), false, '' );

 	if( !empty($src[0]) ) {
		$parallaxImageUrl 	=	" background-image:url('".$src[0]."'); background-size: cover;";
		$backgroundColor	=	'';
	} elseif( !empty($hgr_page_bgcolor) ) {
		$parallaxImageUrl 	=	'';
		$backgroundColor	=	' background-color:'.$hgr_page_bgcolor.'!important; ';
	} else {
		$parallaxImageUrl 	=	'';
		$backgroundColor	=	' ';
	}
	
	$page_title_top_padding = ( isset($hgr_options['page_title_padding']['padding-top']) ? $hgr_options['page_title_padding']['padding-top'] : '0');
	$page_title_btm_padding = ( isset($hgr_options['page_title_padding']['padding-bottom']) ? $hgr_options['page_title_padding']['padding-bottom'] : '0');
	$page_title_lft_padding = ( isset($hgr_options['page_title_padding']['padding-left']) ? $hgr_options['page_title_padding']['padding-left'] : '0');
	$page_title_rgt_padding = ( isset($hgr_options['page_title_padding']['padding-right']) ? $hgr_options['page_title_padding']['padding-right'] : '0');
	$page_offset			= ( isset($hgr_options['page_top_offset']['height']) ? $hgr_options['page_top_offset']['height'] : '0');
 ?>
 
 <?php if( class_exists("WooCommerce") && is_cart() && WC()->cart->get_cart_contents_count() == 0 ) : ?>
 	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 		<?php the_content(); ?>
 	<?php endwhile; endif; ?>
 <?php else : ?>
 
 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <div id="<?php echo esc_html($post->post_name);?>" class="row standAlonePage <?php echo esc_attr($hgr_page_color_scheme);?>" style=" <?php echo esc_attr($backgroundColor);?> ">
  <div class="col-md-12" <?php echo ( isset($page_offset) && $page_offset	!= 0 ? 'style="margin-top:'.$page_offset	.';"' : '');?> >
  <?php if( isset($hgr_options['enable_page_title']) && $hgr_options['enable_page_title'] == 1) : ?>
		<?php if( isset($hgr_page_title) && empty($hgr_page_title) ): ?>
        <div class="page_title_container" style=" <?php echo esc_attr($parallaxImageUrl);?> padding: <?php echo esc_attr($page_title_top_padding);?> <?php echo esc_attr($page_title_rgt_padding);?> <?php echo esc_attr($page_title_btm_padding);?> <?php echo esc_attr($page_title_lft_padding);?>; ">
            <div class="container">
                <h1 class="" <?php echo esc_attr($page_title_color);?> ><?php the_title(); ?></h1>
            </div>
        </div>
      <?php endif;?>
  <?php endif;?>
  
    <div class="container" style=" <?php echo ( !empty($hgr_page_top_padding) ? ' padding-top:'.esc_attr($hgr_page_top_padding).'px!important;' : '' ); echo ( !empty($hgr_page_btm_padding) ? ' padding-bottom:'.esc_attr($hgr_page_btm_padding).'px!important;' : '' );?> ">
      <!--<div class="slideContent vc_col-md-9 vc_col-sm-12 vc_col-xs-12" style="float:right;">-->
      <div class="SMS-Magic_Logo"><a href="<?php echo get_site_url()?>"><img src="<?php echo get_site_url()?>/wp-content/themes/navy-child/img/sms-logo.svg" alt="logo" /></a></div>
      <div style="margin-top:20px;">
        <?php //the_content(); ?>
        
        <div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div><div class="wpb_column vc_column_container vc_col-sm-8"><div class="vc_column-inner "><div class="wpb_wrapper">
	<div class="wpb_raw_code wpb_content_element wpb_raw_html">
		<div class="wpb_wrapper">
			<div id="trialForm-container">
			<form id="trialForm" name="trialForm"><!--<p><span class="red">*</span> <span class="bottom-text">These fields are required</span></p>-->
				<div class="form-wrapper">
				    <div class="form-left-column">
						<?php	
							
							if( isset($_SESSION['name']) && $_SESSION['name']!==null ){
								if(strpos( $_SESSION['name'],' ')!==FALSE){
									$fullname=explode(' ',$_SESSION['name']);
									$fname=$fullname[0];
									$lname=$fullname[1];
									$fnameDissable='readonly="readonly"';
								        $lnameDissable='readonly="readonly"';
								}else{
									$fname=$_SESSION['name'];
									$lname='';
									$fnameDissable='readonly="readonly"';
									$lnameDissable=NULL;
								}								
							}else{
								$fname=NULL;
								$lname=NULL;
								$fnameDissable=NULL;
								$lnameDissable=NULL;
							}
						?>
						<label for="FirstName" id="UserFirstName_lbl">First Name:<span class="red">*</span></label>
						<input type="text" name="FirstName" id="FirstName" value="<?php echo($fname); ?>"  maxlength="255" class="std" placeholder="Enter First Name">
					    <label for="LastName" id="UserLastName_lbl">Last Name:<span class="red">*</span></label>
					    <input type="text" name="LastName" id="LastName" value="<?php echo($lname); ?>" maxlength="255" class="std" placeholder="Enter Last Name">
					    
						<?php	
							if( isset($_SESSION['emailId']) && $_SESSION['emailId']!==null ){
								$email=$_SESSION['emailId'];
								//$emailDissable='readonly="readonly"';
								
							}else{
								$email=NULL;
								//$emailDissable=NULL;
							}
						?>
						<label for="Email" id="UserEmail_lbl">Email Address:<span class="red">*</span></label>
					    <input type="text" name="Email" id="Email" value="<?php echo($email); ?>" maxlength="255" class="std" placeholder="Enter Email Address">
						
						<?php	
							if( isset($_SESSION['phoneNumber']) && $_SESSION['phoneNumber']!==null ){
								$phone=$_SESSION['phoneNumber'];
								$phoneDissable='readonly="readonly"';
							}else{
								$phone=NULL;
								$phoneDissable=NULL;
							}
						?>
						<label for="Phone" id="UserPhone_lbl">Phone Number:<span class="red">*</span></label>
					    <input type="text" name="Phone" id="Phone" value="<?php echo($phone); ?>" maxlength="255" class="std" placeholder="Enter Phone Number">
						
						<label for="Title" id="UserTitle_lbl">Job Title:<span class="red">*</span></label>
					    <input type="text" name="Title" id="Title" maxlength="255" class="std" placeholder="Enter Job Title">
				    </div>
				    <div class="form-right-column">
					  	<label for="JobFunction" id="JobFunction_lbl">Job Function:<span class="red">*</span></label>
						<select name="JobFunction" id="JobFunction" class="std">
						  <option value="0">-- Select One --</option>
						  	<option value="Marketing">Marketing</option>
							<option value="Sales">Sales</option>
							<option value="Service">Service</option>
							<option value="Technical">Technical</option>
							<option value="Management">Management</option>
							<option value="Other">Other</option>
						</select>
						
						<?php	
							if( isset($_SESSION['companyName']) && $_SESSION['companyName']!==null ){
								$company=$_SESSION['companyName'];
								$companyDissable='readonly="readonly"';
							}else{
								$company=NULL;
								$companyDissable=NULL;
							}
						?>
						<label for="Company" id="CompanyName_lbl">Company:<span class="red">*</span></label>
					    <input type="text" name="Company" id="Company" value="<?php echo($company); ?>" maxlength="255" class="std" placeholder="Enter Company Name">
					    
						<label for="NumberOfEmployees" id="CompanyEmployees_lbl">Employees:<span class="red">*</span></label>
					    <select name="NumberOfEmployees" id="NumberOfEmployees" class="std">
							<option value="0">-- Select One --</option>
							<option value="5">Less than 8 employees</option>
							<option value="15">8 - 20 employees</option>
							<option value="60">21 - 100 employees</option>
							<option value="150">101 - 200 employees</option>
							<option value="300">201 - 400 employees</option>
							<option value="450">401 - 500 employees</option>
							<option value="625">501 - 750 employees</option>
							<option value="875">751 - 1,000 employees</option>
							<option value="1500">1,000 - 2,000 employees</option>
							<option value="5000">More than 2,000</option>
					    </select>
					    
						<label for="Country" id="CompanyCountry_lbl">Country:<span class="red">*</span></label>
					    <select name="Country" id="Country" class="std">
							<option value="0">-- Select One --</option>
							<option value="USA">United States</option>
							<option value="Canada">Canada</option>
							<option value="Australia">Australia</option>
							<option value="UK">United Kingdom</option>
							<option value="India">India</option>
							<option value="Afghanistan">Afghanistan</option>
							<option value="Albania">Albania</option>
							<option value="Algeria">Algeria</option>
							<option value="American Samoa">American Samoa</option>
							<option value="Andorra">Andorra</option>
							<option value="Angola">Angola</option>
							<option value="Anguilla">Anguilla</option>
							<option value="Antigua and Barbuda">Antigua and Barbuda</option>
							<option value="Argentina">Argentina</option>
							<option value="Armenia">Armenia</option>
							<option value="Aruba">Aruba</option>
							<option value="Austria">Austria</option>
							<option value="Azerbaijan">Azerbaijan</option>
							<option value="Bahamas">Bahamas</option>
							<option value="Bahrain">Bahrain</option>
							<option value="Bangladesh">Bangladesh</option>
							<option value="Barbados">Barbados</option>
							<option value="Belarus">Belarus</option>
							<option value="Belgium">Belgium</option>
							<option value="Belize">Belize</option>
							<option value="Benin">Benin</option>
							<option value="Bermuda">Bermuda</option>
							<option value="Bhutan">Bhutan</option>
							<option value="Bolivia">Bolivia</option>
							<option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
							<option value="Botswana">Botswana</option>
							<option value="Brazil">Brazil</option>
							<option value="Brunei">Brunei</option>
							<option value="Bulgaria">Bulgaria</option>
							<option value="Burkina Faso">Burkina Faso</option>
							<option value="Burundi">Burundi</option>
							<option value="Burma">Burma</option>
							<option value="Cambodia">Cambodia</option>
							<option value="Cameroon">Cameroon</option>
							<option value="Cape Verde">Cape Verde</option>
							<option value="Cayman Islands">Cayman Islands</option>
							<option value="Central African Republic">Central African Republic</option>
							<option value="Chad">Chad</option>
							<option value="Chile">Chile</option>
							<option value="China">China</option>
							<option value="Christmas Island">Christmas Island</option>
							<option value="Colombia">Colombia</option>
							<option value="Comoros">Comoros</option>
							<option value="Congo (Democratic Rep)">Congo (Democratic Rep)</option>
							<option value="Cook Islands">Cook Islands</option>
							<option value="Costa Rica">Costa Rica</option>
							<option value="Croatia">Croatia</option>
							<option value="Cuba">Cuba</option>
							<option value="Curacao">Curacao</option>
							<option value="Cyprus">Cyprus</option>
							<option value="Czech Republic">Czech Republic</option>
							<option value="Denmark">Denmark</option>
							<option value="Djibouti">Djibouti</option>
							<option value="Dominica">Dominica</option>
							<option value="Dominican Republic">Dominican Republic</option>
							<option value="East Timor">East Timor</option>
							<option value="Ecuador">Ecuador</option>
							<option value="Egypt">Egypt</option>
							<option value="El Salvador">El Salvador</option>
							<option value="Equatorial Guinea">Equatorial Guinea</option>
							<option value="Eritrea">Eritrea</option>
							<option value="Estonia">Estonia</option>
							<option value="Ethiopia">Ethiopia</option>
							<option value="Falkland Islands">Falkland Islands</option>
							<option value="Faroe Islands">Faroe Islands</option>
							<option value="Fiji">Fiji</option>
							<option value="Finland">Finland</option>
							<option value="France">France</option>
							<option value="French Guiana">French Guiana</option>
							<option value="French Polynesia">French Polynesia</option>
							<option value="Gabon">Gabon</option>
							<option value="Gaza">Gaza</option>
							<option value="Gambia">Gambia</option>
							<option value="Georgia">Georgia</option>
							<option value="Germany">Germany</option>
							<option value="Ghana">Ghana</option>
							<option value="Gibraltar">Gibraltar</option>
							<option value="Greece">Greece</option>
							<option value="Greenland">Greenland</option>
							<option value="Grenada">Grenada</option>
							<option value="Guadeloupe">Guadeloupe</option>
							<option value="Guam">Guam</option>
							<option value="Guatemala">Guatemala</option>
							<option value="Guinea">Guinea</option>
							<option value="Guinea-Bissau">Guinea-Bissau</option>
							<option value="Guyana">Guyana</option>
							<option value="Haiti">Haiti</option>
							<option value="Honduras">Honduras</option>
							<option value="Hong Kong">Hong Kong</option>
							<option value="Hungary">Hungary</option>
							<option value="Iceland">Iceland</option>
							<option value="Indonesia">Indonesia</option>
							<option value="Iran">Iran</option>
							<option value="Iraq">Iraq</option>
							<option value="Ireland (Republic)">Ireland (Republic)</option>
							<option value="Israel">Israel</option>
							<option value="Italy">Italy</option>
							<option value="Ivory Coast">Ivory Coast</option>
							<option value="Jamaica">Jamaica</option>
							<option value="Japan">Japan</option>
							<option value="Jordan">Jordan</option>
							<option value="Kazakhstan">Kazakhstan</option>
							<option value="Kenya">Kenya</option>
							<option value="Kiribati">Kiribati</option>
							<option value="Korea North">Korea North</option>
							<option value="Korea South">Korea South</option>
							<option value="Kosovo">Kosovo</option>
							<option value="Kuwait">Kuwait</option>
							<option value="Kyrgyzstan">Kyrgyzstan</option>
							<option value="Laos">Laos</option>
							<option value="Latvia">Latvia</option>
							<option value="Lebanon">Lebanon</option>
							<option value="Lesotho">Lesotho</option>
							<option value="Liberia">Liberia</option>
							<option value="Libya">Libya</option>
							<option value="Liechtenstein">Liechtenstein</option>
							<option value="Lithuania">Lithuania</option>
							<option value="Luxembourg">Luxembourg</option>
							<option value="Macao">Macao</option>
							<option value="Macedonia">Macedonia</option>
							<option value="Madagascar">Madagascar</option>
							<option value="Malawi">Malawi</option>
							<option value="Malaysia">Malaysia</option>
							<option value="Maldives">Maldives</option>
							<option value="Mali">Mali</option>
							<option value="Malta">Malta</option>
							<option value="Marshall Islands">Marshall Islands</option>
							<option value="Mauritania">Mauritania</option>
							<option value="Mauritius">Mauritius</option>
							<option value="Mayotte">Mayotte</option>
							<option value="Mexico">Mexico</option>
							<option value="Micronesia">Micronesia</option>
							<option value="Moldova">Moldova</option>
							<option value="Monaco">Monaco</option>
							<option value="Mongolia">Mongolia</option>
							<option value="Montenegro">Montenegro</option>
							<option value="Montserrat">Montserrat</option>
							<option value="Morocco">Morocco</option>
							<option value="Mozambique">Mozambique</option>
							<option value="Namibia">Namibia</option>
							<option value="Nauru">Nauru</option>
							<option value="Nepal">Nepal</option>							
							<option value="Netherlands">Netherlands</option>
							<option value="New Caledonia">New Caledonia</option>
							<option value="New Zealand">New Zealand</option>
							<option value="Nicaragua">Nicaragua</option>
							<option value="Niger">Niger</option>
							<option value="Nigeria">Nigeria</option>
							<option value="Niue">Niue</option>
							<option value="Norfolk Island">Norfolk Island</option>
							<option value="Norway">Norway</option>
							<option value="Oman">Oman</option>
							<option value="Pakistan">Pakistan</option>
							<option value="Palau">Palau</option>
							<option value="Panama">Panama</option>
							<option value="Papua New Guinea">Papua New Guinea</option>
							<option value="Paraguay">Paraguay</option>
							<option value="Peru">Peru</option>
							<option value="Philippines">Philippines</option>
							<option value="Pitcairn Islands">Pitcairn Islands</option>
							<option value="Poland">Poland</option>
							<option value="Portugal">Portugal</option>
							<option value="Puerto Rico">Puerto Rico</option>
							<option value="Qatar">Qatar</option>
							<option value="Reunion">Reunion</option>
							<option value="Romania">Romania</option>
							<option value="Russian">Russian</option>
							<option value="Rwanda">Rwanda</option>
							<option value="Saint Helena">Saint Helena</option>
							<option value="St Kitts &amp; Nevis">St Kitts &amp; Nevis</option>
							<option value="St Lucia">St Lucia</option>
							<option value="Saint Vincent &amp; the Grenadines">Saint Vincent &amp; the Grenadines</option>
							<option value="Samoa">Samoa</option>
							<option value="San Marino">San Marino</option>
							<option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
							<option value="Saudi Arabia">Saudi Arabia</option>
							<option value="Senegal">Senegal</option>
							<option value="Serbia">Serbia</option>
							<option value="Seychelles">Seychelles</option>
							<option value="Sierra Leone">Sierra Leone</option>
							<option value="Singapore">Singapore</option>
							<option value="Slovakia">Slovakia</option>
							<option value="Slovenia">Slovenia</option>
							<option value="Solomon Islands">Solomon Islands</option>
							<option value="Somalia">Somalia</option>
							<option value="South Africa">South Africa</option>
							<option value="South Sudan">South Sudan</option>
							<option value="Spain">Spain</option>
							<option value="Sri Lanka">Sri Lanka</option>
							<option value="St. Vincent and Grenadines">St. Vincent and Grenadines</option>
							<option value="Sudan">Sudan</option>
							<option value="Suriname">Suriname</option>
							<option value="Svalbard">Svalbard</option>
							<option value="Swaziland">Swaziland</option>
							<option value="Sweden">Sweden</option>
							<option value="Switzerland">Switzerland</option>
							<option value="Syria">Syria</option>
							<option value="Taiwan">Taiwan</option>
							<option value="Tajikistan">Tajikistan</option>
							<option value="Tanzania">Tanzania</option>
							<option value="Thailand">Thailand</option>
							<option value="Togo">Togo</option>
							<option value="Tokelau">Tokelau</option>
							<option value="Tonga">Tonga</option>
							<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
							<option value="Tunisia">Tunisia</option>
							<option value="Turkey">Turkey</option>
							<option value="Turkmenistan">Turkmenistan</option>
							<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
							<option value="Tuvalu">Tuvalu</option>
							<option value="Uganda">Uganda</option>
							<option value="Ukraine">Ukraine</option>
							<option value="UAE">United Arab Emirates</option>
							<option value="Uruguay">Uruguay</option>
							<option value="Uzbekistan">Uzbekistan</option>
							<option value="Vanuatu">Vanuatu</option>
							<option value="Vatican City">Vatican City</option>
							<option value="Venezuela">Venezuela</option>
							<option value="Vietnam">Vietnam</option>
							<option value="Yemen">Yemen</option>
							<option value="Zambia">Zambia</option>
							<option value="Zimbabwe">Zimbabwe</option>						
						</select>
					    
						<label for="State" id="CompanyState_lbl">State/Province:<span id="state_req_span" style="display:inline"><span class="red">*</span></span></label>
						<div class="state_input" style="display:none;">
							<input type="text" name="StateInput" id="StateInput" maxlength="255" class="std" placeholder="Enter State/Province">
						</div>
						<div class="state_select">
							<select name="State" id="State" class="std">
								<option value="0">-- USA / Canada / Australia Only --</option>
								<optgroup label="USA State">
									<option value="AL">Alabama</option>
									<option value="AK">Alaska</option>
									<option value="AZ">Arizona</option>
									<option value="AR">Arkansas</option>
									<option value="CA">California</option>
									<option value="CO">Colorado</option>
									<option value="CT">Connecticut</option>
									<option value="DE">Delaware</option>
									<option value="DC">District of Columbia</option>
									<option value="FL">Florida</option>
									<option value="GA">Georgia</option>
									<option value="HI">Hawaii</option>
									<option value="ID">Idaho</option>
									<option value="IL">Illinois</option>
									<option value="IN">Indiana</option>
									<option value="IA">Iowa</option>
									<option value="KS">Kansas</option>
									<option value="KY">Kentucky</option>
									<option value="LA">Louisiana</option>
									<option value="ME">Maine</option>
									<option value="MD">Maryland</option>
									<option value="MA">Massachusetts</option>
									<option value="MI">Michigan</option>
									<option value="MN">Minnesota</option>
									<option value="MS">Mississippi</option>
									<option value="MO">Missouri</option>
									<option value="MT">Montana</option>
									<option value="NE">Nebraska</option>
									<option value="NV">Nevada</option>
									<option value="NH">New Hampshire</option>
									<option value="NJ">New Jersey</option>
									<option value="NM">New Mexico</option>
									<option value="NY">New York</option>
									<option value="NC">North Carolina</option>
									<option value="ND">North Dakota</option>
									<option value="OH">Ohio</option>
									<option value="OK">Oklahoma</option>
									<option value="OR">Oregon</option>
									<option value="PA">Pennsylvania</option>
									<option value="RI">Rhode Island</option>
									<option value="SC">South Carolina</option>
									<option value="SD">South Dakota</option>
									<option value="TN">Tennessee</option>
									<option value="TX">Texas</option>
									<option value="UT">Utah</option>
									<option value="VT">Vermont</option>
									<option value="VA">Virginia</option>
									<option value="WA">Washington</option>
									<option value="WV">West Virginia</option>
									<option value="WI">Wisconsin</option>
									<option value="WY">Wyoming</option>
								</optgroup>
								<optgroup label="Canadian Provinces">
									<option value="AB">Alberta</option>
									<option value="BC">British Columbia</option>
									<option value="MB">Manitoba</option>
									<option value="NB">New Brunswick</option>
									<option value="NL">Newfoundland</option>
									<option value="NT">Northwest Territories</option>
									<option value="NS">Nova Scotia</option>
									<option value="NU">Nunavut</option>
									<option value="ON">Ontario</option>
									<option value="PE">Prince Edward Island</option>
									<option value="QC">Quebec</option>
									<option value="SK">Saskatchewan</option>
									<option value="YT">Yukon</option>
								</optgroup>
								<optgroup label="Australian State/Territory">
									<option value="ACT">Australian Capital Territory</option>
									<option value="NSW">New South Wales</option>
									<option value="NT">Northern Territory</option>
									<option value="QLD">Queensland</option>
									<option value="SA">South Australia</option>
									<option value="TAS">Tasmania</option>
									<option value="VIC">Victoria</option>
									<option value="WA">Western Australia</option>
								</optgroup>				    
							</select>
						</div>
				    </div>
				</div>
				<div class="clear"></div>
				<p class="helptext consentline">By supplying your contact information, you authorize SMS-Magic to send you educational and promotional materials. You may withdraw your consent at any time. Please refer to our <a href="<?php echo(get_site_url()) ?>/privacy-policy/" target="_blank">privacy policy</a> for more information or <a href="<?php echo(get_site_url()) ?>/contact/" target="_blank">contact us</a> with questions.<br/><label for="consent"><input type="checkbox" name="consent" id="consent"> I Agree</label></p>
				<div class=""><input id="TrialFormBtn" type="submit" name="submit" value="Submit" class="fill-blue-btn" /></div>
				</form>
				</div>
			</div>
		</div>
	</div></div></div><div class="wpb_column vc_column_container vc_col-sm-2"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div></div>        
      </div>
	
	<script src="//app-ab21.marketo.com/js/forms2/js/forms2.min.js"></script>
	<form id="mktoForm_1533" style="display:none;"></form>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			$.ajax({
				type: 'GET',
				url: "<?php echo get_stylesheet_directory_uri().'/includes/freeemaildomains.txt'; ?>",
				dataType: 'text',
				success: function(data){
					invalidDomains=data.split(/\r\n|\n/); //store data in container variable
				}
			});
						
			$("#Country").on("change", function(){
				$(".state_select").hide();
				$(".state_input").hide();
				var thisCountry = $(this).val(); console.log(thisCountry);
				if( (thisCountry=="USA") || (thisCountry=="Canada") || (thisCountry=="Australia") ){
					$(".state_select").show();
					$(".state_input").hide();
				}else{
					$(".state_input").show();
					$(".state_select").hide();
				}
			});

			$.validator.addMethod("company_email", function(value, element, arg){
				return isEmailGood(value);
			}, "Please enter a company email id.");
			$.validator.addMethod("custom_number", function(phone_number, element) {
				phone_number = phone_number.replace(/\s+/g, "");
				return this.optional(element) || phone_number.length > 9 && phone_number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
			}, "Please Enter a valid phone number!");
			$.validator.addMethod("jobfunctionNonZero", function(value, element, arg){
				return arg !== value;
			}, "Please select job function.");
			$.validator.addMethod("empnumNonZero", function(value, element, arg){
				return arg !== value;
			}, "Please select number of employees.");
			$.validator.addMethod("countryNonZero", function(value, element, arg){
				return arg !== value;
			}, "Please select country.");
			$.validator.addMethod("stateSelectBlock", function(value, element, arg){
				return arg !== value;
			}, "Please select state.");
			$.validator.addMethod("stateInputBlock", function(value, element, arg){
				return arg !== value;
			}, "Please enter state.");
			$.validator.addMethod("lettersonly", function(value, element) {
				return this.optional(element) || /^[a-z]+$/i.test(value);
			}, "Letters only please");

			$('#TrialFormBtn').click(function(){

				$('#trialForm').validate({
					rules: {
						FirstName: {
							required: true,
							lettersonly: true
						},
						LastName: {
							required: true,
							lettersonly: true
						},
						Email: {
							required: true,
							email: true,
							company_email: true
						},
						Phone: {
							required: true,
							custom_number: true
						},
						Title: {
							required: true
						},
						JobFunction: { jobfunctionNonZero: "0" },
						Company: {
							required: true
						},
						NumberOfEmployees: { empnumNonZero: "0" },
						Country: { countryNonZero: "0" },
						State: {
							stateSelectBlock: "0"
						},
						StateInput:{
							required: true,
							lettersonly: true,
							stateInputBlock: {
								depends: function(element){
									return $('.state_input').is(':visible');
								}
							}
						}
					}
				});

				$("#trialForm").on('submit', function(e){
					var isvalid = $("#trialForm").valid();
					//var ajx_loader_img = '<?php echo site_url(); ?>/wp-content/themes/navy-child/img/loader.gif';
					if (isvalid) {
						e.preventDefault();
						
						$('.ajaxLoader').show();						
						
						var fnameValue = document.forms["trialForm"]["FirstName"].value;
						var lnameValue = document.forms["trialForm"]["LastName"].value;
						var emailValue = document.forms["trialForm"]["Email"].value;
						var phoneValue = document.forms["trialForm"]["Phone"].value;
						var jobtitleValue = document.forms["trialForm"]["Title"].value;
						var jobfunctionValue = document.forms["trialForm"]["JobFunction"].value;
						var companyValue = document.forms["trialForm"]["Company"].value;
						var employeenumValue = document.forms["trialForm"]["NumberOfEmployees"].value;
						var countryValue = document.forms["trialForm"]["Country"].value;
						if($(".state_select").is(":visible")){
							var stateValue = document.forms["trialForm"]["State"].value;
						}
						if($(".state_input").is(":visible")){
							var stateValue = document.forms["trialForm"]["StateInput"].value;
						}
						var consent=$('#consent').is(':checked');
						if( consent == true ){
						  var consentValue = 'Yes';
						  var consentDBValue = 1;
						  var consentTimestamp = "<?php echo( gmdate('Y-m-d H:i:s') ); ?>";
						} else { 
						  var consentValue = '';
						  var consentDBValue = 0;
						  var consentTimestamp = null;
						}

						/*save all form to backend database via local ajax*/
						$.ajax({
							type:'POST',
							url: '<?php echo admin_url("admin-ajax.php");?>',
							data: {
								action: 'smsmagic_appex_leads',
								security: '<?php echo wp_create_nonce( "smsmagic-appex-leads" ); ?>',
								"FirstName":fnameValue,
								"LastName":lnameValue,
								"Email":emailValue,
								"MobilePhone":phoneValue,
								"Title":jobtitleValue,
								"JobFunction": jobfunctionValue,
								"Company":companyValue,
								"NumberOfEmployees": employeenumValue,
								"Country":countryValue,
								"State":stateValue,
								"UseCase": "<?php echo($_COOKIE['selected_usecase']); ?>",
								"TermsAcceptance": "<?php echo($_COOKIE['tandc_accepted_datetime']); ?>",
								"EmailConsent": consentDBValue,
								"EmailConsentTime":  consentTimestamp,
								"CreatedTime":"<?php echo( gmdate('Y-m-d H:i:s') ); ?>",
								"MktoSubmitted":0
							},
							success:function(data){
								console.log('WP DB Action Response'); //#####
								var jsonObj=$.parseJSON(data);
								var result=jsonObj.dbresult;
								console.log('WP DB Action Response '+result); //#####
								if(result=='Success'){
									<?php $currentdomain=currentServer(); if( (currentServer()=='localhost') || (FALSE!==strpos(currentServer(), 'launch')) ){ ?>
										location.href = "<?php echo( get_site_url().'/email-reminder-page/' ); ?>"; //for launch.sms-magic.com
									<?php }else{ ?>
										location.href = window.location.href+'/success/'; //for www.sms-magic.com
									<?php } ?>
								}
							}
						});

						/*
						MktoForms2.loadForm("//app-ab21.marketo.com", "707-UFB-065", 1533, function(form){
							form.addHiddenFields({
								//These are the values which will be submitted to Marketo
								"FirstName":fnameValue,
								"LastName":lnameValue,
								"Email":emailValue,
								"MobilePhone":phoneValue,
								"Title":jobtitleValue,
								"Job_Function__c": jobfunctionValue,
								"Company":companyValue,
								"NumberOfEmployees": employeenumValue,
								"Country":countryValue,
								"State":stateValue,
								"Trial_Org_Usecase__c": "<?php echo($_COOKIE['selected_usecase']); ?>",
								"Terms_Acceptance__c": "<?php echo($_COOKIE['tandc_accepted_datetime']); ?>",
								"Email_Consent__c": consentValue,
								"Email_Consent_Timestamp__c":  consentTimestamp,
							});
							form.submit();
							form.onSuccess(function(values, followUpUrl) {
								//form.getFormElem().hide();
								<?php $currentdomain=currentServer(); if( (currentServer()=='localhost') || (FALSE!==strpos(currentServer(), 'launch')) ){ ?>
									location.href = "<?php echo( get_site_url().'/email-reminder-page/' ); ?>"; //for launch.sms-magic.com
								<?php }else{ ?>
									location.href = window.location.href+'/success/'; //for www.sms-magic.com
								<?php } ?>
								return false;
							});
						});
						*/
					}
				});

			}); //Trial Form Submit Ends
			
			function isEmailGood(email){
				for(var i=0; i < invalidDomains.length; i++){
					var domain = invalidDomains[i];
					if (email.indexOf(domain) != -1){
						return false;
					}
				}
				return true;
			}

		});//document.ready ends
	</script>
        
        <?php if(is_paged()) : ?>
      <?php paginate_comments_links(); ?>
      <?php endif;?>
      <?php comments_template(); ?>
      <?php if(is_paged()) : ?>
      <?php paginate_comments_links(); ?>
      <?php endif;?>
      
      <!--</div>-->
      
       <!-- sidebar -->
    <!--<div class="vc_col-md-3 vc_col-sm-12 vc_col-xs-12" style="float:left;">
      <?php 
		//if ( is_active_sidebar( 'page-widgets' ) ) { 
			//dynamic_sidebar('page-widgets');
		//}
	 ?>
    </div>-->
    <!-- / sidebar -->
    <div class="clearfix"></div>
    </div>
  </div>
    
</div>
<?php endwhile; endif; ?>

<?php endif;?>

<?php 
 	get_footer('appexchange');
 ?>
<div class="ajaxLoader" style="display:none;"><img class="ajaxLoader-img" src="<?php echo site_url(); ?>/wp-content/themes/navy-child/img/loader.gif"></div>