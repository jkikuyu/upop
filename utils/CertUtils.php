<?php
/*
 * @file    CertUtils.php
 * @author  Jude
 * @date    02/01/2019
 * @version $Revision$
 * @Purpose Class deals with certificate information
 */
namespace UnionPay;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
final class CertUtils{
    /** Path of signed certificate. */
    public $signCertPath;
    /** Password of signed certificate. */
    public $signCertPwd;
    /** Type of signed certificate. */
    public $signCertType;
    /** Path of encrypted public key certificate. */
    public $encryptCertPath;
    /** Authenticate the catalog of signed public key certificates. */
    public $validateCertDir;
    /** Read the catalog of specified signed certificates according to client codes. */
    public $signCertDir;
    /** Security key (used in calculation of SHA256 and SM3) */
    public $secureKey;
	/** algorithm for signing data**/
	public $alg;
    
	private static $keystore = null;
	/** Encryption public key and certificate for sensitive information */
	private static $encryptCert = null;
	/** Encryption public key for magnetic tracks */
	private static $encryptTrackKey = null;
	/** Verify the messages, signatures, and certificates returned from China UnionPay. */
	private static $validateCert = null;
	/** Authenticate the signatures of intermediate certificates */
	private static $middleCert = null;
	/** Authenticate the signatures of root certificates */
	private static $rootCert = null;
    
	private $frontRequestUrl;
	
	
	/** Single query */

	private $singleQueryUrl;
	/** Batch query */
	private $batchQueryUrl;
	/** Batch transaction */
	private $batchTransUrl;
	/** File transmission */
	private $fileTransUrl;
	/** Path of signed certificate. */
	//private $signCertPath;
	/** Password of signed certificate. */
	//private $signCertPwd;
	/** Type of signed certificate. */
	//private $signCertType;
	/** Path of encrypted public key certificate. */
	//private $encryptCertPath;
	/** Authenticate the catalog of signed public key certificates. */
	//private $validateCertDir;
	/** Read the catalog of specified signed certificates according to client codes. */
	//private $signCertDir;
	/** Path of encrypted certificates for magnetic tracks. */
	private $encryptTrackCertPath;
	/** Module of encrypted public keys for magnetic tracks. */
	private $encryptTrackKeyModulus;
	/** Exponent of encrypted public keys for magnetic tracks. */
	private $encryptTrackKeyExponent;
	/** Card transaction. */
	private $cardRequestUrl;
	/** App transaction */
	private $appRequestUrl;
	/** Certificate usage mode (single certificate/multi-certificate) */
	private $singleMode;

	/** Path of intermediate certificates  */
	private $middleCertPath;
	/** Path of root certificates  */
	private $rootCertPath;
	/** For whether to verify the CNs of the certificates for verifying certificates, all certificates except the ones for which this parameter has been set to false should be authenticated.  */
	private $ifValidateCNName = true;
	/** For whether to authenticate the https certificate, all certificates need not to be authenticated by default.  */
	private $ifValidateRemoteCert = false;

	/*Payment-related addresses*/
	private $jfFrontRequestUrl;
	private $jfBackRequestUrl;
	private $jfSingleQueryUrl;
	private $jfCardRequestUrl;
	private $jfAppRequestUrl;

	private $qrcBackTransUrl;
	private $qrcB2cIssBackTransUrl;
	private $qrcB2cMerBackTransUrl;
	private static $log;
	private $logfile;
	
/*
	$this->frontRequestUrl=getenv('UPOP.FRONTTRANSURL');
	$this->singleQueryUrl=getenv('UPOP.SINGLEQUERYURL');
	$this->batchQueryUrl=getenv('UPOP.BATCHTRANSURL');
	$this->batchTransUrl=getenv('UPOP.FILETRANSURL');
	$this->fileTransUrl=getenv('UPOP.APPTRANSURL');
	$this->cardTransUrl=getenv('UPOP.CARDTRANSURL');
	$this->jfFrontRequestUrl=getenv('UPOP.JFFRONTTRANSURL');
	$this->jfBackRequestUrl=getenv('UPOP.JFBACKTRANSURL');
	$this->jfSingleQueryUrl=getenv('UPOP.JFSINGLEQUERYURL');
	$this->jfCardRequestUrl=getenv('UPOP.JFCARDTRANSURL');
	$this->jfAppRequestUrl=getenv('UPOP.JFAPPTRANSURL');
	$this->ifValidateCNName=getenv('UPOP.IFVALIDATECNNAME');
	$this->ifValidateRemoteCert=getenv('UPOP.IFVALIDATEREMOTECERT');
*/


    public static function init(){
        $signCertPath=getenv('UPOP.SIGNCERT.PATH');
        $signCertPwd=getenv('UPOP.SIGNCERT.PWD');
        $signCertType=getenv('UPOP.SIGNCERT.TYPE');
        $encryptCert=getenv('UPOP.ENCRYPTCERT.PATH');
        $middleCertPath=getenv('UPOP.MIDDLECERT.PATH');
        $rootCertPath=getenv('UPOP.ROOTCERT.PATH');
		$alg = getenv('UPOP.ALG');
		$logfile = Utils::getLogFile();
		$log = new Logger('Upop');
		$log->pushHandler(new StreamHandler($logfile , Logger::INFO));

    }

    public static function initCert(){
		$success =false;
        $signCertPath=getenv('UPOP.SIGNCERT.PATH');
        $signCertType=getenv('UPOP.SIGNCERT.TYPE');
        $signCertPwd=getenv('UPOP.SIGNCERT.PWD');

        $logfile = Utils::getLogFile();
		$log = new Logger('Upop');
		$log->pushHandler(new StreamHandler($logfile , Logger::INFO));

        if ($signCertType =='PKCS12'){
			  if ($cert_store = file_get_contents($signCertPath)) {


					if (openssl_pkcs12_read($cert_store, self::$keystore, $signCertPwd)){
					   $log->info("Signed Certicate loaded Successfully");
						$success=true;
					}
					else{
						$log->info("unable to read file");

						throw new \Exception("Error: Unable to read file");

					}

			}
			else{
				$log->error("unable to read file");
				throw new \Exception("Error: Unable to read the cert file\n");
			}
		}
		return $success ;

        }
	public static function getkeyStore(){
		self::init();
		$success = self::initCert();
		
		return self::$keystore;
	}

	public static function generateSignature($privateKey, $data=null){
		/* Ensure raw data is encoded using UTF-8, apply hasing. IMPORTANT that resulting hash is encoded 
		 * again using UTF-8
		 */
        $logfile = Utils::getLogFile();
		$log = new Logger('Upop');
		$log->pushHandler(new StreamHandler($logfile , Logger::INFO));


		$alg = getenv('UPOP.ALG');

		$utf8=   utf8_encode ($data);
		$sha256 = hash ("sha256",$utf8);
		$utf8_enc=   utf8_encode ($sha256); //enchode the hashed data
		$b64_enc = null;
    	if (openssl_sign ( $utf8_enc , $signature ,  $privateKey, $alg)){

			$b64 = base64_encode($signature);
		}
		else{
			$log->error("unable to read cert file");
			throw new \Exception("Error: Unable to read the cert file\n");
    	}
    return $b64;
	}


   /* public static function initMiddleCert(){
        $success = false
        if ($fp =fopen(this->middleCertPath, "r")){
            $success = true;

            self::$middleCert= fread($fp, 8192);
            fclose($fp); 
            Utils::logger("INFO: middle Certicate loaded Successfully");

        }
        else{
            Utils::logger("ERROR: Unable to initialize middle cert file\n");

            throw new Exception("ERROR: Unable to initialize middle cert file\n");
           
        }
        return $success;
    }
    
     public static function initRootCert(){
        $success = false
        if ($fp =fopen(this->rootCertPath, "r")){
            $success = true;

            self::$rootCert= fread($fp, 8192);
            fclose($fp); 
            Utils::logger("INFO: root Certicate loaded Successfully");

        }
        else{
            Utils::logger("ERROR: Unable to initialize middle cert file\n");

            throw new Exception("ERROR: Unable to initialize middle cert file\n");
           
        }
        return $success;
    }
           
         public static function initEcryptCert(){
        $success = false
        if ($fp =fopen(this->secureKey, "r")){
            $success = true;

            self::$encryptCert= fread($fp, 8192);
            fclose($fp); 
            Utils::logger("INFO: root Certicate loaded Successfully");

        }
        else{
            Utils::logger("ERROR: Unable to initialize middle cert file\n");

            throw new Exception("ERROR: Unable to initialize middle cert file\n");
           
        }
        return $success;
    }
	*/
    
}
?>