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

$page_title = "Terms and Conditions";
include('includes/header.php');
?>

<?php include('includes/log_in_bar.php'); ?>

<div class="center_it">
	<div class="main_content" >
	
	<?php include ('includes/sidebar.php'); ?>
	
		<div class="main_right">

			<div class="right_art">
				<div class="text">

<h2>Jobsession Terms and Conditions ("Agreement")</h2>
<br>
<p>This Agreement was last modified on April 08, 2014.</p><br />

<p>Please read these Terms and Conditions ("Agreement", "Terms and Conditions") carefully before using jobsession.com.au ("the Site") operated by Jobsession ("us", "we", or "our"). This Agreement sets forth the legally binding terms and conditions for your use of the Site at jobsession.com.au.</p>
<p>By accessing or using the Site in any manner, including, but not limited to, visiting or browsing the Site or contributing content or other materials to the Site, you agree to be bound by these Terms and Conditions. Capitalized terms are defined in this Agreement.</p>

<p><br /><strong>Intellectual Property</strong><br /><br />The Site and its original content, features and functionality are owned by Jobsession and are protected by international copyright, trademark, patent, trade secret and other intellectual property or proprietary rights laws.</p>

<p><br /><strong>Termination</strong><br /><br />We may terminate your access to the Site, without cause or notice, which may result in the forfeiture and destruction of all information associated with you. All provisions of this Agreement that by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity, and limitations of liability.</p>

<p><br /><strong>Links To Other Sites</strong><br /><br />Our Site may contain links to third-party sites that are not owned or controlled by Jobsession.</p>
<p>Jobsession has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party sites or services. We strongly advise you to read the terms and conditions and privacy policy of any third-party site that you visit.</p>

<p><br /><strong>Governing Law</strong><br /><br />This Agreement (and any further rules, polices, or guidelines incorporated by reference) shall be governed and construed in accordance with the laws of Western Australia, Australia, without giving effect to any principles of conflicts of law.</p>

<p><br /><strong>Changes To This Agreement</strong><br /><br />We reserve the right, at our sole discretion, to modify or replace these Terms and Conditions by posting the updated terms on the Site. Your continued use of the Site after any such changes constitutes your acceptance of the new Terms and Conditions.</p>
<p>Please review this Agreement periodically for changes. If you do not agree to any of this Agreement or any changes to this Agreement, do not use, access or continue to access the Site or discontinue any use of the Site immediately.</p>

<p><br /><strong>Contact Us</strong><br /><br />If you have any questions about this Agreement, please contact us.</p>

<p style="font-size: 85%; color: #999;">Generated with permission from <a href="http://termsfeed.com/terms-conditions/generator/" title="TermsFeed" style="color: #999; text-decoration: none;">TermsFeed Generator</a>.</p>

				</div><!-- text -->
			</div><!-- right_art -->

		</div><!-- main_right -->
	</div><!-- main_content -->
</div><!-- center_it -->

<?php 
include('includes/footer.php');
?>