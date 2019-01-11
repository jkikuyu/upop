<?php
/*
 * @file    CertUtils.php
 * @author  Jude
 * @date    02/01/2019
 * @version $Revision$
 * @Purpose Class deals with certificate information
 */
namespace UnionPay;
use Dotenv/Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
final class CertUtils{
    /** Path of signed certificate. */
    private $signCertPath;
    /** Password of signed certificate. */
    private $signCertPwd;
    /** Type of signed certificate. */
    private $signCertType;
    /** Path of encrypted public key certificate. */
    private $encryptCertPath;
    /** Authenticate the catalog of signed public key certificates. */
    private $validateCertDir;
    /** Read the catalog of specified signed certificates according to client codes. */
    private $signCertDir;
    /** Security key (used in calculation of SHA256 and SM3) */
    private $secureKey;
	/** algorithm for signing data**/
	private $alg;
    
	private static keyStore = null;
	/** Encryption public key and certificate for sensitive information */
	private static encryptCert = null;
	/** Encryption public key for magnetic tracks */
	private static encryptTrackKey = null;
	/** Verify the messages, signatures, and certificates returned from China UnionPay. */
	private static validateCert = null;
	/** Authenticate the signatures of intermediate certificates */
	private static middleCert = null;
	/** Authenticate the signatures of root certificates */
	private static rootCert = null;
    
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
	private $signCertPath;
	/** Password of signed certificate. */
	private $signCertPwd;
	/** Type of signed certificate. */
	private $signCertType;
	/** Path of encrypted public key certificate. */
	private $encryptCertPath;
	/** Authenticate the catalog of signed public key certificates. */
	private $validateCertDir;
	/** Read the catalog of specified signed certificates according to client codes. */
	private $signCertDir;
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
	private boolean ifValidateCNName = true;
	/** For whether to authenticate the https certificate, all certificates need not to be authenticated by default.  */
	private boolean ifValidateRemoteCert = false;

	/*Payment-related addresses*/
	private $jfFrontRequestUrl;
	private $jfBackRequestUrl;
	private $jfSingleQueryUrl;
	private $jfCardRequestUrl;
	private $jfAppRequestUrl;

	private $qrcBackTransUrl;
	private $qrcB2cIssBackTransUrl;
	private $qrcB2cMerBackTransUrl;
	private $log;
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
        $this->signCertPath=getenv('UPOP.SIGNCERT.PATH');
        $this->signCertPwd=getenv('UPOP.SIGNCERT.PWD');
        $this->signCertType=getenv('UPOP.SIGNCERT.TYPE');
        $this->encryptCert=getenv('UPOP.ENCRYPTCERT.PATH');
        $this->middleCertPath=getenv('UPOP.MIDDLECERT.PATH');
        $this->rootCertPath=getenv('UPOP.ROOTCERT.PATH');
		$this->alg = getenv('UPOP.ALG');
		$this-$logfile = Utils::getLogFile();
		$this-$log = new Logger('Upop');
		$this-$log->pushHandler(new StreamHandler($logfile , Logger::INFO));

    }

    public static function initCert(){
        $success =false;
        if ($this->signCertType=='PKCS12'){
        
            if ($cert_store = file_get_contents(this->signCertPath)) {
                if (openssl_pkcs12_read($cert_store, self::$keyStore, this->signCertPwd)){
                   this->log->info("Signed Certicate loaded Successfully");
                    $success=true;
				}
                else{
					this->log->info("unable to read file");

                    throw new \Exception("Error: Unable to read file");
                }
			}
            else{
				this->log->error("unable to read file");
                throw new \Exception("Error: Unable to read the cert file\n");
            }
        }
        return $success;
	}
	public static function getkeyStore(){
		return self::$keyStore;
	}
	public static function generateSignature(array $keyStore=null, $data=null){
		/* Ensure raw data is encoded using UTF-8, apply hasing. IMPORTANT that resulting hash is encoded 
		 * again using UTF-8
		 */
		$utf8=   utf8_encode ($data);
		$sha256 = hash ("sha256",$utf8);
		$utf8_enc=   utf8_encode ($sha256); //enchode the hashed data
		$privateKey = $keystore['pkey']; //retrieve private key from keystore
		$b64_enc = null;
    	if (openssl_sign ( $utf8_enc , $signature ,  $privateKey, $this->alg)){
			$b64 = base64_encode($signature);
		}
		else{
			this->log->error("unable to read cert file");
			throw new \Exception("Error: Unable to read the cert file\n");
    	}
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