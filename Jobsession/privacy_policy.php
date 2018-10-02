<?php
session_start();
require('../../connect_db.php');
require('includes/first_declarations.php');
require('includes/register_functions.php');
require('includes/job_sub_functions.php');
require('includes/PHPMailer/PHPMailerAutoload.php');
include('includes/grab_all_data.php');
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['url1'] = $url;

$page_title = "Privacy Policy";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<div class="right_art">
				<div class="text">
				
<h1>Jobsession Privacy Policy</h1><br />
<p>This Privacy Policy was last modified on March 19, 2014.</p><br />
<p>Jobsession ("us", "we", or "our") operates jobsession.com.au (the "Site"). This page informs you of our policies regarding the collection, use and disclosure of Personal Information we receive from users of the Site.</p><br />
<p>We use your Personal Information only for providing and improving the Site. By using the Site, you agree to the collection and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, accessible at jobsession.com.au.</p>

<p><br /><strong>Information Collection And Use</strong><br /><br />While using our Site, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. Personally identifiable information may include, but is not limited to, your name, email address, postal address and phone number ("Personal Information").</p>

<p><br /><strong>Log Data</strong><br /><br />Like many site operators, we collect information that your browser sends whenever you visit our Site ("Log Data"). This Log Data may include information such as your computer's Internet Protocol ("IP") address, browser type, browser version, the pages of our Site that you visit, the time and date of your visit, the time spent on those pages and other statistics.</p>

<p><br /><strong>Cookies</strong><br /><br />Cookies are files with small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a web site and stored on your computer's hard drive.</p>
<p>Like many sites, we use "cookies" to collect information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Site.</p>

<p><br /><strong>Security</strong><br /><br />The security of your Personal Information is important to us, but remember that no method of transmission over the Internet, or method of electronic storage, is 100% secure. While we strive to use commercially acceptable means to protect your Personal Information, we cannot guarantee its absolute security.</p>

<p><br /><strong>Links To Other Sites</strong><br /><br />Our Site may contain links to other sites that are not operated by us. If you click on a third party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit.</p>
<p>Jobsession has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party sites or services.</p>

<p><br /><strong>Changes To This Privacy Policy</strong><br /><br />Jobsession may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on the Site. You are advised to review this Privacy Policy periodically for any changes.</p>

<p><br /><strong>Contact Us</strong><br /><br />If you have any questions about this Privacy Policy, please contact us.</p>

<p style="font-size: 85%; color: #999;">Generated with permission from <a href="http://termsfeed.com/privacy-policy/generator/" title="TermsFeed" style="color: #999; text-decoration: none;">TermsFeed Generator</a>.</p>


				</div><!-- text -->
			</div><!-- right_art -->

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>