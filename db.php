<?php 
/**
 *  @file db.php
 *  @author Anas Yassine <anasyy@hotmail.com>
 * @date 2016/07/07
 */
 
 
 require_once 'config.php' ;
 require_once dirname(__FILE__).'/salesforce-php-toolkit/soapclient/SforcePartnerClient.php' ; 
 require_once dirname(__FILE__).'/salesforce-php-toolkit/soapclient/SforceHeaderOptions.php' ; 
 ini_set('soap.wsdl_cache_enable', '0');
 
 try
     {
     $client = new SforcePartnerClient() ;
     $client->createConnection($sf_wsdl);
     
     
     $loginResult=$client->login($sf_username,$sf_password);
     
     
     $q = 'SELECT Id,UserId_On_Bot__c FROM Domestic_Helper__c' ;
     
     $r = new QueryResult($client->query($q));
     for($r->rewind;$r->pointer<$r->size;$r->next())
     {
         print_r($r->current());
         
     }
}
catch ( Exception $e )
{
   // print_r($client->getLastRequest())   ;
    echo $e->faultstring ;
}

?>