<?php
session_start();
$TITLE = "Terms and Conditions";
require('../theme/config.php');
require(ASSETS.'open.php');

//code

include("../theme/header.php");
?>

<p class="pf-14">
This Agreement was last modified on April 08, 2014.

<br><br>Please read these Terms and Conditions ("Agreement", "Terms and Conditions") carefully before using <?php echo WEBURL; ?> ("the Site") operated by <?php echo COMPANYNAME; ?> ("us", "we", or "our"). This Agreement sets forth the legally binding terms and conditions for your use of the Site at <?php echo WEBURL; ?>.
<br>By accessing or using the Site in any manner, including, but not limited to, visiting or browsing the Site or contributing or other materials to the Site, you agree to be bound by these Terms and Conditions. Capitalized terms are defined in this Agreement.

<br><br><strong>Intellectual Property</strong><br><br>The Site and its original content, features and functionality are owned by <?php echo COMPANYNAME; ?> and are protected by international copyright, trademark, patent, trade secret and other intellectual property or proprietary rights laws.

<br><br><strong>Termination</strong><br><br>We may terminate your access to the Site, without cause or notice, which may result in the forfeiture and destruction of all information associated with you. All provisions of this Agreement that by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity, and limitations of liability.

<br><br><strong>Links To Other Sites</strong><br><br>Our Site may contain links to third-party sites that are not owned or controlled by <?php echo COMPANYNAME; ?>.
<br><?php echo COMPANYNAME; ?> has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party sites or services. We strongly advise you to read the terms and conditions and privacy policy of any third-party site that you visit.

<br><br><strong>Governing Law</strong><br><br>This Agreement (and any further rules, polices, or guidelines incorporated by reference) shall be governed and construed in accordance with the laws of Western Australia, Australia, without giving effect to any principles of conflicts of law.

<br><br><strong>Changes To This Agreement</strong><br><br>We reserve the right, at our sole discretion, to modify or replace these Terms and Conditions by posting the updated terms on the Site. Your continued use of the Site after any such changes constitutes your acceptance of the new Terms and Conditions.
<br>Please review this Agreement periodically for changes. If you do not agree to any of this Agreement or any changes to this Agreement, do not use, access or continue to access the Site or discontinue any use of the Site immediately.

<br><br><strong>Contact Us</strong><br><br>If you have any questions about this Agreement, please contact us.
</p>

<?php
include("../theme/footer.php");
?>