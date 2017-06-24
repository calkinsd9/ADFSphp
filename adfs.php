<?php
#define('DRUPAL_ROOT', getcwd());
#include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
#drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// PingFederate dropoff settings
#define('PF_URL', "https://sso.bdo.com");
#define('PF_DROPOFF', "/ext/ref/dropoff");
#define('PF_ADAPTERID', "AllianceReferenceAdapter");
#define('PF_AUTH', "BDORestUser:BDO_Rest_User");

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once(__DIR__.'/lightSAML/autoload.php');

global $user;

session_start();

if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {

    if ($_POST['username']) {
        processSSO();
    } else {
	    show_form();
	}
} else {
	show_form();
}

function show_form() {
?>
<html>
	<head>
	    <style type="text/css">
		    * {
				margin: 0px;
				padding: 0px;
			}
			html, body {
				height: 100%;
				width: 100%;
				background-color: #ffffff;
				color: #000000;
				font-weight: normal;
				font-family: Arial;
				min-width: 500px;
			}
			#brandingWrapper::after {
				position: absolute;
				top: 44px;
				z-index: 100;
				background-color: #fafafa;
				height: 66px;
				display: block;
				content: " ";
				width: 100%;
			}
			input.text {
				height: 28px;
				padding: 0px 3px 0px 3px;
				border: solid 1px #BABABA;
				width: 342px;
				max-width: 100%;
				font-family: inherit;
				margin-bottom: 8px;
			}
			#loginMessage {
				font-size: 1.25em;
				margin-bottom: 30px;
			}
			input[type="submit"] {
				border: none;
				background-color: #ed1a3b;
				min-width: 80px;
				width: auto;
				height: 30px;
				padding: 4px 20px 6px 20px;
				border-style: solid;
				border-width: 1px;
				transition: background 0s;
				color: rgb(255, 255, 255);
				cursor: pointer;
				margin-bottom: 8px;
				text-transform: uppercase;
				-ms-user-select: none;
				-moz-transition: background 0s;
				-webkit-transition: background 0s;
				-o-transition: background 0s;
				-webkit-touch-callout: none;
				-webkit-user-select: none;
				-khtml-user-select: none;
				-moz-user-select: none;
				-o-user-select: none;
				user-select: none;
			}
		</style>
	</head>
	<body>
    <div id="brandingWrapper" style="background-color: #313131; position: absolute; z-index: 100; height: 44px; width:100%;">
		<span id="title" style="font-family: Arial; font-size: 2em; font-weight: bold; text-align: left; color: #3a3a3a; position: absolute; top: 60px; left: 150px; z-index: 103; text-transform: uppercase;">Sign In</span>
	</div>
	<div id="contentWrapper" style="width: 500px; background-color: #ffffff; margin: 0 auto; text-align: left;">
	    <div id="content" style="min-height: 100%; height: auto !important; margin: 0 auto -55px auto; padding: 0px 150px 0px 50px;">
	        <div id="header" style="font-size: 2em; font-weight: lighter; font-family: 'Segoe UI Light' , 'Segoe' , 'SegoeUI-Light-final', Tahoma, Helvetica, Arial, sans-serif; padding-top: 90px; margin-bottom: 60px; min-height: 100px; overflow: hidden;">
                <img class="logoImage" style="z-index: 101; position: absolute; top: 58px; left: 20px; float: left;" src="https://auth.bdo.com/adfs/portal/logo/logo.png?id=75AE1DAC408370EA4685CE29CF453F8C7340AAC19C699420E3AF07BA68C11874" alt="BDO USA">
            </div>
			<div id="workArea">
                 <div id="authArea" class="groupMargin">
				     <div id="loginArea">        
						<div id="loginMessage" class="groupMargin">Mimic attributes returned from Drupal API</div>
							<form method="post" id="loginForm" >
								<div id="formsAuthenticationArea">
								    <div id="userNameArea">
									    <input id="userNameInput" name="username" type="text" value="" tabindex="1" class="text fullWidth" placeholder="Username" spellcheck="false" autocomplete="off">     
									</div>
									<div id="emailArea">
									    <input id="emailInput" name="email" type="email" tabindex="2" class="text fullWidth" placeholder="Email" autocomplete="off">                                   
									</div>
                                    <div id="firstnameArea">
									    <input id="firstnameInput" name="firstname" type="firstname" tabindex="3" class="text fullWidth" placeholder="First Name" autocomplete="off">                                   
									</div>
                                    <div id="lastnameArea">
									    <input id="lastnameInput" name="lastname" type="lastname" tabindex="4" class="text fullWidth" placeholder="Last Name" autocomplete="off">                                   
									</div>
				                    <div id="submissionArea" class="submitMargin">
                                        <input type="submit" name="submit" value="Sign In"></input>
                                    </div>
								</div>
							</form>
						</div>
				</div>
		</div>
	</div>
		</div>
	</div>
		<div id="footer" style="height: 20px; position: absolute; color: #fff; background-color: #313131; font-size: 0.78em; bottom: 0; left: 0; right: 0; text-align: center; padding-top: 2px;">
		    <div id="bdo-footer">© BDO USA, LLP. a Delaware Company 2016. All Rights Reserved.</div>
		</div>
	</div>
	</body>
</html>
<?php
}

function full_url() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function processSSO() {
    $userId = $_POST['username'];
    $email = $_POST['email'];

    // First Name
    $first_name = $_POST['firstname'];
    
    // Last Name
    $last_name = $_POST['lastname'];

    
    //BUILD SAML RESPONSE
    $destination = "https://authdev.bdo.com/adfs/ls";
    $issuer = "bdo:saml2:php:dev";
    $cert = "/ADFSphpCert.pem";  //cert file location
    $key = "/ADFSphpKey.pem";  //name of the private key

    $certificate = \LightSaml\Credential\X509Certificate::fromFile($cert);
    $privateKey = \LightSaml\Credential\KeyHelper::createPrivateKey($key, 'ADFSphp', true);


    $response = new \LightSaml\Model\Protocol\Response();
    $response
       ->addAssertion($assertion = new \LightSaml\Model\Assertion\Assertion())
        ->setID(\LightSaml\Helper::generateID())
        ->setIssueInstant(new \DateTime())
        ->setDestination($destination)
        ->setIssuer(new \LightSaml\Model\Assertion\Issuer($issuer))
        ->setStatus(new \LightSaml\Model\Protocol\Status(new \LightSaml\Model\Protocol\StatusCode('urn:oasis:names:tc:SAML:2.0:status:Success')))
        ->setSignature(new \LightSaml\Model\XmlDSig\SignatureWriter($certificate, $privateKey));

    $assertion
        ->setId(\LightSaml\Helper::generateID())
        ->setIssueInstant(new \DateTime())
        ->setIssuer(new \LightSaml\Model\Assertion\Issuer($issuer))
        ->setSubject(
                (new \LightSaml\Model\Assertion\Subject())
                    ->setNameID(new \LightSaml\Model\Assertion\NameID(
                        $userId,
                        \LightSaml\SamlConstants::NAME_ID_FORMAT_UNSPECIFIED
                    ))
                ->addSubjectConfirmation(
                        (new \LightSaml\Model\Assertion\SubjectConfirmation())
                       ->setMethod(\LightSaml\SamlConstants::CONFIRMATION_METHOD_BEARER)
                       ->setSubjectConfirmationData(
                                (new \LightSaml\Model\Assertion\SubjectConfirmationData())
//								   ->setInResponseTo('id_of_the_request')
                               ->setNotOnOrAfter(new \DateTime('+1 MINUTE'))
                               ->setRecipient($destination)
                            )
                    )
            )
            ->setConditions(
                (new \LightSaml\Model\Assertion\Conditions())
                    ->setNotBefore(new \DateTime())
                    ->setNotOnOrAfter(new \DateTime('+1 MINUTE'))
                    ->addItem(
                        new \LightSaml\Model\Assertion\AudienceRestriction([$destination])
                    )
            )
        ->addItem(
                (new \LightSaml\Model\Assertion\AttributeStatement())
                ->addAttribute(new \LightSaml\Model\Assertion\Attribute(
                        \LightSaml\ClaimTypes::EMAIL_ADDRESS,
                        $email
                    ))
                ->addAttribute(new \LightSaml\Model\Assertion\Attribute(
                        \LightSaml\ClaimTypes::GIVEN_NAME,
                        $first_name
                    ))
                ->addAttribute(new \LightSaml\Model\Assertion\Attribute(
                        \LightSaml\ClaimTypes::SURNAME,
                        $last_name
                    ))
            )
        ->addItem(
                (new \LightSaml\Model\Assertion\AuthnStatement())
                ->setAuthnInstant(new \DateTime('-1 MINUTE'))
                ->setSessionIndex('_some_session_index')
                ->setAuthnContext(
                        (new \LightSaml\Model\Assertion\AuthnContext())
                       ->setAuthnContextClassRef(\LightSaml\SamlConstants::AUTHN_CONTEXT_PASSWORD_PROTECTED_TRANSPORT)
                    )
            )
        ;
        
        //SEND SAML RESPONSE
        $bindingFactory = new \LightSaml\Binding\BindingFactory();
        $postBinding = $bindingFactory->create(\LightSaml\SamlConstants::BINDING_SAML2_HTTP_POST);
        $messageContext = new \LightSaml\Context\Profile\MessageContext();
        $messageContext->setMessage($response)->asResponse();

        $httpResponse = $postBinding->send($messageContext);
        print $httpResponse->getContent()."\n\n";  

        //TODO: not sure how relay state works with ADFS acting as SP. we need to somehow get the target application in this script and to ADFS so that it knows where to post its own valid SAML response
        
        
        
        
        
        
        
        
        
}
