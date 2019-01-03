<?php
namespace UnionPay;
use Dotenv\Dotenv;   
private $frontRequestUrl;
/** Background request URL. */
private $backRequestUrl;
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
/** Security key (used in calculation of SHA256 and SM3) */
private $secureKey;
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

        $this->frontRequestUrl=getenv('UPOP.FRONTTRANSURL');
        $this->backRequestUrl=getenv('UPOP.BACKTRANSURL');
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
        $this->signCertDir=getenv('UPOP.SIGNCERT.PATH');
        $this->signCertPwd=getenv('UPOP.SIGNCERT.PWD');
        $this->signCertType=getenv('UPOP.SIGNCERT.TYPE');
        $this->secureKey=getenv('UPOP.ENCRYPTCERT.PATH');
        $this->middleCertPath=getenv('UPOP.MIDDLECERT.PATH');
        $this->rootCertPath=getenv('UPOP.ROOTCERT.PATH');
final class Utils{
    public static function validatePhpInput($raw_input, array $required_params){
        $res_arr = null;
        $res_obj = null;
        if($raw_input){
            foreach($required_params as $param){
                if(!property_exists($raw_input, $param) || empty($raw_input->$param) || !(is_string($raw_input->$param) || is_int($raw_input->$param))){
                    die($param . ' is required');
                }
                else{
                    $res_arr[$param] = $raw_input->$param;
                }
            }
            $res_obj = (object) $res_arr;
        }
        else{
            throw new Exception('The following parameters are required ' . json_encode($required_params));
        }
        //var_dump($res_obj);
    return $res_obj;
    }
    public static function formatError(\Exception $e, $error_desc){
        $message = ((json_decode($e->getMessage()) == null) ? $e->getMessage() : json_decode($e->getMessage()));
        $err_obj = (object) ['Desc' => $error_desc,
            'Line' => $e->getLine(),
            'File' => basename($e->getFile()),
            'Message' => $message,
            'StackTrace' => $e->getTraceAsString()
        ];
        return json_encode($err_obj);
    }
    public static function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    self::array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    self::array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    public static function logger(array $logs){
        //this line can be replaced with typehint of string if php>=7.1
        $logs = json_encode($logs);

        $filepath = Utils::getLogFile();
        file_put_contents($filepath, $logs."\n", FILE_APPEND | LOCK_EX);
    }
    public static function getLogFile(){
        $dotenv = new Dotenv(__DIR__.'/secure');
        $dotenv->load();
        $dirname = getenv('LOGDIR');

        if(!is_string($dirname)){
            throw new \InvalidArgumentException('dirname must be a string');
        }
        else{
            // $logs    = (is_array($logs))? json_encode($logs, JSON_PRETTY_PRINT): (string)$logs;
            
            $dir = $dirname;

            $base_dir = dirname(__dir__).'/';

            $save_dir = $base_dir.$dirname;

            $dir_exists = (file_exists($save_dir) && is_dir($save_dir));

            if(!$dir_exists){
                if(!mkdir($save_dir, 0755, true)){
                    throw new \Exception('Unable to create directory');
                }
            }
            $dir = $save_dir;

        }
        return  $dir."/".date("Y-m-d").'.log';
    }
    public static function suddenDeath(){
        /**
        @author Jude
        date:19/10/2018
        This will cater for sudden program termination that is non recoverabel resulting from making non existent calls. 
        **/

        $filepath = Utils::getLogFile();

        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            // fatal error has occured
             $logs = json_encode($error);
            file_put_contents($filepath, $logs."\n", FILE_APPEND | LOCK_EX);

        }

    }

}
?>