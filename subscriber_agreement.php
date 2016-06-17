<?php require_once('Connections/ContactLogin.php'); ?>
<?php
$Contact_find = $ContactLogin->newFindCommand('sup-conLogin');
$Contact_findCriterions = array('conWebUser'=>'=='.fmsEscape($_SESSION["ContactLogin_tableLogin"]["user"]),'conWebPass'=>'=='.fmsEscape($_SESSION["ContactLogin_tableLogin"]["pass"]),);
foreach($Contact_findCriterions as $key=>$value) {
    $Contact_find->AddFindCriterion($key,$value);
}

fmsSetPage($Contact_find,'Contact',1); 

$Contact_result = $Contact_find->execute(); 

if(FileMaker::isError($Contact_result)) fmsTrapError($Contact_result,"error.php"); 

if(FileMaker::isError($SubscriberAgreement_result)) fmsTrapError($SubscriberAgreement_result,"error.php"); 

fmsSetLastPage($Contact_result,'Contact',1); 

$Contact_row = current($Contact_result->getRecords());

$Contact__Sup_portal = fmsRelatedRecord($Contact_row, 'Sup');
$Contact__calc_portal = fmsRelatedRecord($Contact_row, 'calc');
$Contact__SupJobCmpPRINTER_portal = fmsRelatedRecord($Contact_row, 'SupJobCmpPRINTER');
 

// FMStudio Pro - do not remove comment, needed for DreamWeaver support ?>
<?php $_SESSION['cmp_ID'] = $Contact_row->getField('con_FK_cmp'); ?>
<?php $_SESSION['conName'] = $Contact_row->getField('conNameFull'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="IE=8" >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>SpecTrak: Subscriber Agreement</title>
<link href="css/specStyle.css" rel="stylesheet" type="text/css" /><!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]-->

<link href="SpryAssets/SpryMenuBar.js.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

<script type="text/javascript">
	// TOGGLE ITEMS
	function toggleItems(obj){
		$(obj).next().slideToggle("normal");
	}
	
	function ChangeColor(tableRow, highLight)
	{
	if (highLight)
	{
	  tableRow.style.backgroundColor = '#EFEFEF';
	  tableRow.style.cursor = 'pointer';
	}
	else
	{
	  tableRow.style.backgroundColor = '';
	}
	}
	
	function gotoSKU(url){
		window.location = url;
	}
	
	function userResponse(){
		//alert("Agree");
		$("#user_response").submit();
	}
  </script>


</head>

<body class="twoColHybLtHdr">


<div id="container">
  <div id="header">
    <div id="logo">
	<ul id="clientscape_header" class="headerLogo">
		<li><a href="index.php"><img src="img/spectrak_header.png" width="220" height="25" border="0"></a></li>
	  </ul>
	</div>
    <h1>
      <!-- end #header -->
      <?php echo $Contact_row->getField('Sup::cmpCompany'); ?> | <?php echo $Contact_row->getField('conNameFull'); ?></h1>
  </div>
  
  <!-- SIDEBAR -->
  <div id="sidebar1">

  <ul id="links" class="MenuBarVertical">
    <li><a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>">Logout</a></li>
  </ul>

<!-- end #sidebar1 --></div>
  
<!-- MAIN CONTENT -->
  <div id="mainContent">
  <div class="pass">
  	Welcome to SpecTrak.<br />
  	As a first time subscriber, we ask that you review and accept our agreement and terms below. </div>
  	<h1>SPECTRAK.DAYMON.COM SUBSCRIBER AGREEMENT AND TERMS OF USE </h1>
    <p>UPDATED ON November 17, 2010<br />
      <br>
      PLEASE SCROLL DOWN AND READ THE SUBSCRIBER AGREEMENT AND TERMS OF USE BELOW.<br>
      This Subscriber Agreement and Terms of Use govern your use of Spectrak.Daymon.com (The &ldquo;Site&rdquo;), and unless other terms and conditions expressly govern, any other electronic services from Galileo Global Branding Group Inc. (&ldquo;Daymon&rdquo;) that may be made available from time to time (each, a &quot;Service&quot;). <br>
    If you agree to be bound by the terms of this Agreement, you should click on the &quot;I AGREE&quot; button at the end of this Agreement. If you do not agree to be bound by the terms of this Agreement, you should click &quot;I DISAGREE.&quot; If you click &quot;I DISAGREE,&quot; you will not be able to proceed with the registration process for the respective Service and become a subscriber. To the extent you have access to, or are using, a Service without having completed Daymon&rsquo;s registration process or clicked on an &quot;I AGREE&quot; button, you are hereby notified that your continued use of a Service is subject to all of the terms and conditions of this Agreement.</p>
    <ol>
      <li><strong>Changes to Subscriber Agreement.</strong> Daymon may change the terms of this Agreement at any time by notifying you of the change in writing or electronically (including without limitation, by email or by posting a notice on the Service that the terms have been &quot;updated&quot;). The changes also will appear in this document, which you can access at any time by going to the Terms and Conditions link of a Service. You signify that you agree to be bound by such changes by using a Service after changes are made to this Agreement.</li>
      <li><strong>Privacy and Your Account.</strong> Registration data and other information about you are subject to the Privacy Policy which you may access by going to the Privacy Policy link of the Site. Your information may be stored and processed in the United States or any other country where Daymon has facilities, and by subscribing to a Service, you consent to the transfer of information outside of your country. If your access to a Service has been provided by or through a third party (for example, your employer) (each, a &quot;Third Party&quot;), the Third Party may have provided Daymon with information about you to enable Daymon to provide you with access to the Service and distinguish you from other subscribers (such as your email address or name). If you access a Service using a password, you are solely responsible for maintaining the confidentiality of that password. If you provide someone else with access to your password to a Service, they will have the ability to view information about your account and make changes through the website for the Service. You agree to notify Daymon promptly if you change your address or email so we can continue to contact you and send any notices required hereunder. If you fail to notify Daymon promptly of a change, then any notice Daymon sends to your old address or email shall be deemed sufficient notice.</li>
      <li><strong>Limitations on Use. </strong> 
        <ol type="a">
          <li>Only one individual may access a Service at the same time using the same user name or password, unless we agree otherwise. You shall not disclose to or share Your User ID or password with any third party or use Your User ID or password for any purpose not permitted by this Agreement.&nbsp; You shall not access, or attempt to access areas of Daymon.com for which you are not authorized.</li>
          <li>The text, graphics, images, video, metadata, design, organization, compilation, look and feel, advertising and all other protectable intellectual property (the &quot;Content&quot;) available through the Services are Daymon’s property or the property of other users and licensors and are protected by copyright and other intellectual property laws.</li>
          <li>You agree not to rearrange or modify the Content. You agree not to create abstracts from, scrape or display our content for use on another web site or service.</li>
          <li>You agree not to use the Services for any unlawful purpose. Daymon reserves the right to terminate or restrict your access to a Service if, in Daymon&rsquo;s opinion, your use of the Service may violate any laws, regulations or rulings, infringe upon another person's rights or violate the terms of this Agreement. Also, Daymon may refuse to grant you a user name that impersonates someone else, is protected by trademark or other proprietary right law, or is vulgar or otherwise offensive.</li>
          <li>You agree not to take any action that disrupts the normal functioning of <a href="https://spectrak.daymon.com">spectrak.daymon.com</a> or imposes an unreasonable or disproportionately large load on Daymon&rsquo;s infrastructure.&nbsp; You shall not use, transmit, or cause to be transmitted, any device, software, computer programming routine, information, data, virus, time bomb, &ldquo;Trojan horse,&rdquo; &ldquo;easter egg&rdquo;, worm, cancelbot or other matter that may damage, interfere with, intercept, impair or expropriate any Daymon.com systems, software, data or functionality, or those of any user of Daymon&rsquo;s Services.&nbsp; You shall not use any robot, spider, other automatic device or manual process to monitor or copy Daymon&rsquo;s web pages or the content contained therein without Daymon&rsquo;s prior express written permission.&nbsp; You shall not harvest or attempt to harvest or otherwise collect information about other users (including e-mail addresses) for any purpose other than authorized use of specktrak.daymon.com without the consent of such users and the express written consent of Daymon. </li>
        </ol>
      </li>
      <li><strong>User Generated Content.</strong>
        <ol type="a">
          <li>User Name. Daymon requires you to register to have access to the Site and requires that you use your own first and/or last name as your user name. With certain exceptions, when you register, Daymon will prefill your user name with your own name. If you have concerns or believe that someone is using your password without your authority, please contact Customer Service. Daymon reserves the right to disclose any information about you, including registration data, in order to comply with any applicable laws and/or requests under legal process, to operate our systems properly, to protect Daymon’s property or rights, and to safeguard the interests of others.</li>
          <li>User Generated Content.
            <ol type="i">
              <li>User Content. Daymon offers you the opportunity to post content on the Site. Any content, information, graphics, audio, images, and links you submit as part of creating your profile or in connection with any of the Services is referred to as &quot;User Content&quot; in this Agreement and is subject to various terms and conditions as set forth below.</li>
              <li>Grant of Rights and Representations by You. If you upload, post or submit any User Content on a Service, you represent to Daymon that you have all the necessary legal rights to upload, post or submit such User Content and it will not violate any law or the rights of any person.</li>
              <li>Daymon may also remove any User Content for any reason and without notice to you. This includes all materials related to your use of the Services or membership, including email accounts, postings, profiles or other personalized information you have created while on the Services.</li>
              <li>Copyright/IP Policy. It is Daymon’s policy to respond to notices of alleged infringement that comply with the Digital Millennium Copyright Act.</li>
            </ol>
          </li>
        </ol>
      </li>
      <li><strong>Third Party Web Sites, Services and Software. </strong>Daymon may link to, or promote, web sites or services from other companies or offer you the ability to download software from other companies. You agree that Daymon is not responsible for, and do not control, those web sites, services and software.</li>
      <li><strong>DISCLAIMERS OF WARRANTIES AND LIMITATIONS ON LIABILITY. </strong>YOU AGREE THAT YOUR ACCESS TO, AND USE OF, THE SERVICES AND THE CONTENT AVAILABLE THROUGH THE SERVICES IS ON AN &quot;AS-IS&quot;, &quot;AS AVAILABLE&quot; BASIS AND WE SPECIFICALLY DISCLAIM ANY REPRESENTATIONS OR WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, ANY REPRESENTATIONS OR WARRANTIES OF MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. DAYMON WORLDWIDE AND ITS SUBSIDIARIES, AFFILIATES, SHAREHOLDERS, DIRECTORS, OFFICERS, EMPLOYEES AND LICENSORS (&quot;THE DAYMON PARTIES&quot;) WILL NOT BE LIABLE (JOINTLY OR SEVERALLY) TO YOU OR ANY OTHER PERSON AS A RESULT OF YOUR ACCESS OR USE OF THE SERVICES FOR INDIRECT, CONSEQUENTIAL, SPECIAL, INCIDENTAL, PUNITIVE, OR EXEMPLARY DAMAGES, INCLUDING, WITHOUT LIMITATION, LOST PROFITS, LOST SAVINGS AND LOST REVENUES (COLLECTIVELY, THE &quot;EXCLUDED DAMAGES&quot;), WHETHER OR NOT CHARACTERIZED IN NEGLIGENCE, TORT, CONTRACT, OR OTHER THEORY OF LIABILITY, EVEN IF ANY OF THE DAYMON PARTIES HAVE BEEN ADVISED OF THE POSSIBILITY OF OR COULD HAVE FORESEEN ANY OF THE EXCLUDED DAMAGES, AND IRRESPECTIVE OF ANY FAILURE OF AN ESSENTIAL PURPOSE OF A LIMITED REMEDY. IF ANY APPLICABLE AUTHORITY HOLDS ANY PORTION OF THIS SECTION TO BE UNENFORCEABLE, THEN THE DAYMON PARTIES' LIABILITY WILL BE LIMITED TO THE FULLEST POSSIBLE EXTENT PERMITTED BY APPLICABLE LAW.</li>
      <li><strong>General.</strong> This Agreement contains the final and entire agreement regarding your use of the Services and supersedes all previous and contemporaneous oral or written agreements regarding your use of the Services. Daymon may discontinue or change the Services, or their availability to you, at any time. This Agreement is personal to you, which means that you may not assign your rights or obligations under this Agreement to anyone. No third party is a beneficiary of this Agreement. You agree that this Agreement, as well as any and all claims arising from this Agreement will be governed by and construed in accordance with the laws of the State of Connecticut, United States of America applicable to contracts made entirely within Connecticut, and wholly performed in Connecticut, without regard to any conflict or choice of law principles. The sole jurisdiction and venue for any litigation arising out of this Agreement will be an appropriate federal or state court located in Connecticut. This Agreement will not be governed by the United Nations Convention on Contracts for the International Sale of Goods.</li>
      <li><strong>Confidentiality</strong>.&nbsp;&nbsp;&nbsp; This notice serves as a reminder of the confidentiality of the information to which you have access on the Site and the obligation that goes along with said access.&nbsp; Respecting the treatment of Confidential Information disclosed, or to be disclosed, to Daymon and its employees you hereby acknowledge and agree that you will keep Confidential Information in the strictest of confidence and will not disclose directly or indirectly Confidential Information to any unauthorized person, or use for your own purposes or for the benefit of any unauthorized third party any Confidential Information belonging to Daymon or disclosed to Daymon or you by any third party.<br />
        <br />
        As used herein, the term Confidential Information includes, but is not limited to information relating to: (i) products; (ii)&nbsp;packaging; (iii) costs, prices or pricing structures; (iv) sales or purchase data; (v)&nbsp;new products, developments, methods and processes, whether patentable or unpatentable and whether or not reduced to practice; (vi) customers and customer lists; (vii) technology and trade secrets; (viii) financial condition and results of operations of Daymon or any other person that uses Our services; (ix) &nbsp;general corporate information; (x) operating data; (xi) names and addresses of consultants and agents; (xii) data furnished to Daymon or any other user of Daymon.com, relating to any of the foregoing Confidential Information; and (xiii) any information or documents designated &ldquo;CONFIDENTIAL&rdquo; (or similar designation).<br />
        <br />
        Confidential Information does not include any information that is or becomes generally known to the public (other than as the result of unauthorized disclosure by any person) prior to the date you disclose or use such information.<br />
        <br />
        You have agreed, and hereby confirm Your obligation, to maintain Confidential Information in the strictest of confidence and neither disclose to others (nor cause to be disclosed) nor use personally (nor cause to be used) such Confidential Information other than in the performance of duties for your employer.&nbsp; You further agree to take reasonable precautions to prevent the inadvertent exposure of Confidential Information to unauthorized persons or entities.<br />
        <br />
      This agreement does not replace nor negate, but, rather, supplements, any confidentiality agreements previously entered into by you with Galileo Global Branding Group Inc. or any of its affiliates.</li>
      <li><strong>Scheduled downtime and outages</strong>.&nbsp;&nbsp;&nbsp; Daymon may periodically schedule system downtime for system maintenance and other purposes.&nbsp; Daymon makes commercially reasonable efforts to maintain 100% system availability, except during scheduled downtime periods described above.&nbsp; Daymon does not, however, guarantee continuous, uninterrupted or secure access to the <a href="https://spectrak.daymon.com">spectrak.daymon.com</a> website, and operation of the <a href="https://spectrak.daymon.com">spectrak.daymon.com</a> website may be interfered with by numerous factors outside of our control.&nbsp; Despite our efforts at maintaining our systems, <a href="https://spectrak.daymon.com">spectrak.daymon.com</a> may experience unexpected outages during which you may not be able to access the <a href="https://spectrak.daymon.com">spectrak.daymon.com</a> website.</li>
    </ol>
    <p>
	  <input type="button" name="agree" id="agree" value="I Agree" tabindex="1" onclick="userResponse()">
	  <a href="<?php echo fmsLogoutLink('ContactLogin', 'index.php'); ?>"><input type="button" name="disagree" id="disagree" value="I Disagree"></a></p>
	<form action="subscriber_agreement_response.php" method="post" name="user_response" id="user_response">
        <input name="contact" type="hidden" value="<?php echo $Contact_row->getRecordId(); ?>" id="contact">
    </form>
  </div>
	  <!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats -->
	  <br class="clearfloat" />
  </h1>
	<div id="footer">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" valign="MIDDLE">© Galileo Global Branding Group, Inc.</td>
        <td valign="MIDDLE" height="25">&nbsp;</td>
        <td valign="MIDDLE" height="25" width="200"></td>
        <td valign="MIDDLE" height="25" width="50"><a href="<?php echo fmsLogoutLink('ContactLogin', 'login.php'); ?>">Logout</a></td>
      </tr>
    </table>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("links", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
