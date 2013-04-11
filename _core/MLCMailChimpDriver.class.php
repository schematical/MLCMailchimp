<?php
abstract class MLCMailChimpDriver{
	protected static $objClient = null;
	public static function Init(){
		if(is_null(self::$objClient)){
			self::$objClient = new MCAPI(__MAILCHIMP_API_KEY__);
		}
	}
    public static function ListSubscribe($strListId, $strEmail, $arrVars){
        self::Init();
        $retval = self::$objClient->listSubscribe($strListId, $strEmail, $arrVars );

        if (self::$objClient->errorCode){
            throw new Exception(self::$objClient->errorCode . '-' . self::$objClient->errorMessage);
        } else {
            return $retval;
        }

    }
	public static function GetAnalytics($strCampaignId){
		
		self::Init();
		
		$arrStats = self::$objClient->campaignAnalytics($strCampaignId);
		
		if (self::$objClient->errorCode){
			throw new Exception(self::$objClient->errorCode . '-' . self::$objClient->errorMessage);
			return null;
		} else {
			return $arrStats;
		}
		
	}
	public static function GetCampaigns($arrParams = array()){		
		self::Init();		
		$arrCampaigns = self::$objClient->campaigns($arrParams);
		
		if (self::$objClient->errorCode){
			return null;
		} else {
			return $arrCampaigns;
		}		
	}
	public static function GetCampaignAnalytics($arrParams){
	
		$arrCampaigns = self::GetCampaigns();
		$arrData = array();
		foreach($arrCampaigns['data'] as $arrCampaignData){
			$arrData[$arrCampaignData['title']] = self::GetAnalytics($arrCampaignData['id']);
		}
		return $arrData;
	}
}
